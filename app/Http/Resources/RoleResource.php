<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

/**
 * @mixin Role
 */
class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        // Eager load permissions if loaded
        if ($this->relationLoaded('permissions')) {
            $data['permissions'] = PermissionResource::collection($this->permissions);
        }

        return $data;
    }
}
