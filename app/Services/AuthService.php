<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Handle the login logic and token generation.
     */
    public function login(User $user, string $userAgent, ?string $ipAddress = null): array
    {
        // Ensure user details exist and update the IP securely
        $user->detail()->updateOrCreate(
            ['user_id' => $user->id],
            ['last_transaction_ip' => $ipAddress]
        );
        $deviceName = $userAgent ?: 'device_'.$user->id.'_'.now()->timestamp;
        $token = $user->createToken($deviceName)->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle the registration logic, creating the user and their details securely.
     */
    public function register(array $data, string $userAgent, ?string $ipAddress = null): array
    {
        return DB::transaction(function () use ($data, $userAgent, $ipAddress) {
            $user = User::create($data);

            // Wire up the secure transaction profile automatically
            $user->detail()->create([
                'last_transaction_ip' => $ipAddress,
            ]);

            $deviceName = $userAgent ?: 'device_'.$user->id.'_'.now()->timestamp;
            $token = $user->createToken($deviceName)->accessToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        });
    }

    /**
     * Handle the logout logic and token revocation.
     */
    public function logout(User $user): void
    {
        $user->last_login_at = now();
        $user->save();
        $token = $user->token();
        $token->revoke();
    }
}
