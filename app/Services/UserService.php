<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = parent::create($data);

            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            $details = $data['details'] ?? [];
            $user->detail()->create($details);

            return $user->load(['roles', 'detail']);
        });
    }

    public function update(array $data, $idOrSlug): ?Model
    {
        return DB::transaction(function () use ($data, $idOrSlug) {
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user = parent::update($data, $idOrSlug);

            if ($user) {
                if (isset($data['roles'])) {
                    $user->syncRoles($data['roles']);
                }

                if (isset($data['details'])) {
                    $user->detail()->updateOrCreate(
                        ['user_id' => $user->id],
                        $data['details']
                    );
                }

                $user->load(['roles', 'detail']);
            }

            return $user;
        });
    }

    public function findById($idOrSlug): ?Model
    {
        $user = parent::findById($idOrSlug);
        if ($user) {
            $user->load(['roles', 'detail']);
        }

        return $user;
    }
}
