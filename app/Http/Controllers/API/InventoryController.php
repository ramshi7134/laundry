<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;

        $query = InventoryItem::where('branch_id', $branchId);

        if ($request->boolean('active_only', false)) {
            $query->active();
        }

        if ($request->boolean('low_stock', false)) {
            $query->lowStock();
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'sku'           => 'nullable|string|max:100',
            'quantity'      => 'required|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'minimum_level' => 'required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        $item = InventoryItem::create([
            'branch_id'     => $request->user()->branch_id,
            'name'          => $request->name,
            'sku'           => $request->sku,
            'quantity'      => $request->quantity,
            'unit'          => $request->unit,
            'minimum_level' => $request->minimum_level,
            'cost_per_unit' => $request->cost_per_unit,
            'is_active'     => true,
            'alert_sent'    => false,
        ]);

        return response()->json($item, 201);
    }

    public function show(string $id)
    {
        $item = InventoryItem::with(['transactions' => fn($q) => $q->latest()->limit(20)])->find($id);
        if (!$item) return response()->json(['message' => 'Item not found'], 404);

        return response()->json($item);
    }

    public function update(Request $request, string $id)
    {
        $item = InventoryItem::find($id);
        if (!$item) return response()->json(['message' => 'Item not found'], 404);

        $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'sku'           => 'nullable|string|max:100',
            'unit'          => 'sometimes|required|string|max:50',
            'minimum_level' => 'sometimes|required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'is_active'     => 'nullable|boolean',
        ]);

        $item->update($request->only(['name', 'sku', 'unit', 'minimum_level', 'cost_per_unit', 'is_active']));

        return response()->json($item->fresh());
    }

    public function adjustStock(Request $request, string $id)
    {
        $request->validate([
            'type'        => 'required|in:add,remove,adjust',
            'quantity'    => 'required|numeric|min:0.001',
            'reason'      => 'nullable|string',
            'reference'   => 'nullable|string',
        ]);

        $item = InventoryItem::find($id);
        if (!$item) return response()->json(['message' => 'Item not found'], 404);

        DB::transaction(function () use ($item, $request) {
            $type        = $request->type;
            $qty         = (float) $request->quantity;
            $prevQty     = (float) $item->quantity;

            if ($type === 'add') {
                $newQty = $prevQty + $qty;
            } elseif ($type === 'remove') {
                if ($prevQty < $qty) {
                    throw new \Exception("Insufficient stock. Available: {$prevQty} {$item->unit}");
                }
                $newQty = $prevQty - $qty;
            } else {
                // adjust = set absolute value
                $newQty = $qty;
            }

            $item->update(['quantity' => $newQty, 'alert_sent' => false]);

            InventoryTransaction::create([
                'item_id'        => $item->id,
                'type'           => $type,
                'quantity'       => $qty,
                'quantity_before' => $prevQty,
                'quantity_after' => $newQty,
                'reason'         => $request->reason,
                'reference'      => $request->reference,
                'created_by'     => $request->user()->id,
            ]);
        });

        return response()->json($item->fresh());
    }

    public function lowStock(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $items    = InventoryItem::where('branch_id', $branchId)->active()->lowStock()->get();

        return response()->json($items);
    }
}
