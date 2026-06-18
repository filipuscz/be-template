<?php

namespace App\Services;

use App\Mail\UserRegisteredEmail;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

            // Send database greeting notification
            $user->notify(new WelcomeNotification);

            // Check if send_welcome_email setting is enabled
            $isEmailEnabled = app(SettingService::class)->get('send_welcome_email', '0');
            if ($isEmailEnabled === '1' || $isEmailEnabled === 'true') {
                Mail::to($user->email)->send(new UserRegisteredEmail($user));
            }

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
