<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ApiTokenHelper
{
    public static function generate()
    {
        $timestamp = now()->format('YmdHis');
        $random = bin2hex(random_bytes(8));

        $payload = "$timestamp|$random";

        // Encrypt payload
        $encrypted = Crypt::encryptString($payload);

        /** @phpstan-ignore larastan.noEnvCallsOutsideOfConfig */
        $signature = hash_hmac('sha256', $encrypted, env('API_SECRET_KEY'));

        // Final token
        return base64_encode($encrypted . '.' . $signature);
    }

    public static function validation($header) {
        /** @phpstan-ignore larastan.noEnvCallsOutsideOfConfig */
        $secret = env('API_SECRET_KEY');
        /** @phpstan-ignore larastan.noEnvCallsOutsideOfConfig */
        $expiryHours = (int) env('API_TOKEN_EXPIRE_HOURS', 1);
        // Extract token
        $decoded = base64_decode($header);
        [$encrypted, $signature] = explode('.', $decoded);

        // Validate signature
        $expectedSignature = hash_hmac('sha256', $encrypted, $secret);
        throw_if(!hash_equals($expectedSignature, $signature), new \Exception('Invalid API token signature.'));
        // Decrypt payload
        $decrypted = Crypt::decryptString($encrypted);
        [$timestamp, $random] = explode('|', $decrypted);
        $tokenDate = Carbon::createFromFormat('YmdHis', $timestamp);
        $expiryTime = $tokenDate->clone()->addHours($expiryHours);
        throw_if(now()->greaterThan($expiryTime), new \Exception('API token has expired.'));
        return true;
    }
}