<?php

namespace App\Http\Resources;

class UserResource extends BaseResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        // Hide password hash just in case
        unset($data['password']);

        if ($this->relationLoaded('roles')) {
            $data['roles'] = RoleResource::collection($this->roles);
        }

        if ($this->relationLoaded('detail') && $this->detail) {
            $data['detail'] = new BaseResource($this->detail);
        }

        return $data;
    }
}
