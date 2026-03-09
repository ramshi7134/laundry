<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getBranchOrders($branchId);
    public function getBranchOrdersPaginated($branchId, array $filters = [], int $perPage = 20);
    public function updateStatus($id, $status);
}
