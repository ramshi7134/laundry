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
        return $this->model->where('branch_id', $branchId)->get();
    }
}
