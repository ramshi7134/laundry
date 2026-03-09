<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getBranchOrders($branchId)
    {
        return $this->model->where('branch_id', $branchId)->with(['customer', 'items.service'])->get();
    }

    public function updateStatus($id, $status)
    {
        $order = $this->find($id);
        if ($order) {
            $order->status = $status;
            $order->save();
            return $order;
        }
        return null;
    }
}
