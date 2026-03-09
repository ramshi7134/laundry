<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function getBranchCustomers($branchId)
    {
        return $this->model->where('branch_id', $branchId)->orderBy('name')->get();
    }

    public function searchByBranch($branchId, string $search)
    {
        return $this->model->where('branch_id', $branchId)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(30)
            ->get();
    }
}
