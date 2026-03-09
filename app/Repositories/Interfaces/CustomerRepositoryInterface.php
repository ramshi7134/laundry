<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function getBranchCustomers($branchId);
    public function searchByBranch($branchId, string $search);
}
