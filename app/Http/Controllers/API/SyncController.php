<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SyncQueue;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Cloud pulls pending items queued by POS devices.
     * GET /api/sync/pull?branch_id=&after=
     */
    public function pull(Request $request)
    {
        $branchId = $request->user()->branch_id;

        $after = $request->query('after'); // ISO timestamp

        $query = Order::where('branch_id', $branchId)
                       ->with(['customer', 'items.service', 'payments', 'statusLogs'])
                       ->orderBy('updated_at');

        if ($after) {
            $query->where('updated_at', '>', $after);
        }

        $orders    = $query->limit(200)->get();
        $customers = Customer::where('branch_id', $branchId)
                             ->when($after, fn($q) => $q->where('updated_at', '>', $after))
                             ->limit(200)
                             ->get();
        $services  = Service::where('branch_id', $branchId)
                             ->when($after, fn($q) => $q->where('updated_at', '>', $after))
                             ->limit(200)
                             ->get();

        return response()->json([
            'pulled_at' => now()->toIso8601String(),
            'orders'    => $orders,
            'customers' => $customers,
            'services'  => $services,
        ]);
    }

    /**
     * POS pushes local changes to cloud.
     * POST /api/sync/push
     */
    public function push(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.model_type' => 'required|string',
            'items.*.model_id'   => 'required',
            'items.*.action'     => 'required|in:create,update,delete',
            'items.*.payload'    => 'required|array',
        ]);

        $accepted = 0;
        $skipped  = 0;

        foreach ($request->items as $item) {
            // Skip duplicates already synced
            $exists = SyncQueue::where('model_type', $item['model_type'])
                                ->where('model_id', $item['model_id'])
                                ->where('action', $item['action'])
                                ->where('status', 'synced')
                                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            SyncQueue::create([
                'model_type' => $item['model_type'],
                'model_id'   => $item['model_id'],
                'action'     => $item['action'],
                'payload'    => $item['payload'],
                'status'     => 'pending',
                'attempts'   => 0,
            ]);

            $accepted++;
        }

        // Process pending queue immediately (synchronous, small batches)
        $this->processQueue();

        return response()->json([
            'message'  => 'Sync push received',
            'accepted' => $accepted,
            'skipped'  => $skipped,
        ]);
    }

    /**
     * Return sync queue status for this branch.
     * GET /api/sync/status
     */
    public function status(Request $request)
    {
        return response()->json([
            'pending' => SyncQueue::pending()->count(),
            'failed'  => SyncQueue::where('status', 'failed')->count(),
            'synced'  => SyncQueue::where('status', 'synced')->count(),
        ]);
    }

    /**
     * Retry all failed sync items.
     * POST /api/sync/retry
     */
    public function retry()
    {
        $updated = SyncQueue::where('status', 'failed')
                            ->where('attempts', '<', 5)
                            ->update(['status' => 'pending']);

        $this->processQueue();

        return response()->json(['message' => "{$updated} items queued for retry"]);
    }

    // ──────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────

    private function processQueue(): void
    {
        $items = SyncQueue::pending()->limit(50)->get();

        foreach ($items as $syncItem) {
            DB::transaction(function () use ($syncItem) {
                try {
                    $this->applySync($syncItem);
                    $syncItem->markSynced();
                } catch (\Throwable $e) {
                    $syncItem->markFailed($e->getMessage());
                }
            });
        }
    }

    private function applySync(SyncQueue $syncItem): void
    {
        $modelClass = $this->resolveModelClass($syncItem->model_type);
        if (!$modelClass) throw new \Exception("Unknown model type: {$syncItem->model_type}");

        $payload = $syncItem->payload;

        switch ($syncItem->action) {
            case 'create':
                $modelClass::updateOrCreate(['id' => $syncItem->model_id], $payload);
                break;
            case 'update':
                $record = $modelClass::find($syncItem->model_id);
                if ($record) $record->update($payload);
                break;
            case 'delete':
                $modelClass::destroy($syncItem->model_id);
                break;
        }
    }

    private function resolveModelClass(string $type): ?string
    {
        $map = [
            'Order'    => Order::class,
            'Customer' => Customer::class,
            'Service'  => Service::class,
        ];

        return $map[$type] ?? null;
    }
}
