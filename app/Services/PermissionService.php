<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionService extends BaseService
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }
}
