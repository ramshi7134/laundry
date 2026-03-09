<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DeliveryAssignment;
use App\Models\Order;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;

        $query = DeliveryAssignment::whereHas('order', fn($q) => $q->where('branch_id', $branchId))
            ->with(['order.customer', 'staff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id')) {
            $query->where('delivery_staff_id', $request->staff_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_delivery_at', $request->date);
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'               => 'required|exists:orders,id',
            'delivery_staff_id'      => 'required|exists:users,id',
            'pickup_address'         => 'nullable|string',
            'delivery_address'       => 'required|string',
            'scheduled_pickup_at'    => 'nullable|date',
            'scheduled_delivery_at'  => 'nullable|date',
            'delivery_fee'           => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string',
        ]);

        $order = Order::find($request->order_id);
        if (!$order || $order->branch_id !== $request->user()->branch_id) {
            return response()->json(['message' => 'Order not found or access denied'], 403);
        }

        $assignment = DeliveryAssignment::create($request->all());

        return response()->json($assignment->load(['order.customer', 'staff']), 201);
    }

    public function show(string $id)
    {
        $assignment = DeliveryAssignment::with(['order.customer', 'order.items.service', 'staff'])->find($id);
        if (!$assignment) return response()->json(['message' => 'Delivery not found'], 404);

        return response()->json($assignment);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status'      => 'required|in:assigned,picked_up,in_transit,delivered,failed',
            'proof_photo' => 'nullable|string',
            'notes'       => 'nullable|string',
        ]);

        $assignment = DeliveryAssignment::find($id);
        if (!$assignment) return response()->json(['message' => 'Delivery not found'], 404);

        $update = ['status' => $request->status];

        if ($request->status === 'picked_up') {
            $update['actual_pickup_at'] = now();
        } elseif ($request->status === 'delivered') {
            $update['actual_delivery_at'] = now();
            if ($request->filled('proof_photo')) {
                $update['proof_photo'] = $request->proof_photo;
            }
            // Also mark order as delivered
            $assignment->order->update(['status' => 'delivered']);
        }

        if ($request->filled('notes')) {
            $update['notes'] = $request->notes;
        }

        $assignment->update($update);

        return response()->json($assignment->fresh()->load(['order.customer', 'staff']));
    }
}
