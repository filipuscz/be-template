<?php

namespace App\Http\Resources;

class RoleResource extends BaseResource
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
