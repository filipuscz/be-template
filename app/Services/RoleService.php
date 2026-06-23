<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function create(array $data): Model
    {
        /** @var Role $role */
        $role = parent::create($data);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

    public function update(array $data, mixed $idOrSlug): ?Model
    {
        /** @var Role|null $role */
        $role = parent::update($data, $idOrSlug);

        if (isset($data['permissions']) && $role) {
            $role->syncPermissions($data['permissions']);
            $role->load('permissions');
        }

        return $role;
    }

    public function findById($idOrSlug): ?Model
    {
        $role = parent::findById($idOrSlug);
        if ($role) {
            $role->load('permissions');
        }

        return $role;
    }
}
