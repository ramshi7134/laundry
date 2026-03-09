<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getBranchOrders($branchId);
    public function updateStatus($id, $status);
}
