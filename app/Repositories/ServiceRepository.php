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

    public function getBranchServices($branchId, bool $activeOnly = true)
    {
        $query = $this->model->where('branch_id', $branchId);

        if ($activeOnly) {
            $query->active();
        }

        return $query->orderBy('name')->get();
    }
}
