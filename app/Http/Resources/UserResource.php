<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
