<?php

namespace App\Repositories\Interfaces;

interface ServiceRepositoryInterface extends BaseRepositoryInterface
{
    public function getBranchServices($branchId, bool $activeOnly = true);
}
