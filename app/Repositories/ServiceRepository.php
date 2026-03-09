<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function getBranchServices($branchId)
    {
        return $this->model->where('branch_id', $branchId)->get();
    }
}
