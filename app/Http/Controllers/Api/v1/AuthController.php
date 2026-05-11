<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\DefaultEnum;
use App\Enums\StatusEnum;
use App\Helpers\ApiTokenHelper;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\BaseResource;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends BaseApiController
{
    /**
     * Handle user login and token generation.
     * @unauthenticated
     * @response array{success: string, message: string, status: string, code: integer, data: User, token: string}
     */
    public function login(LoginRequest $request)
    {
        $user = $request->user();
        throw_if(!$user, new \Exception('Invalid credentials.'));

        $deviceName = $request->userAgent() ?? 'device_'.$user->id.'_'.now()->timestamp;
        $token = $user->createToken($deviceName)->accessToken;
        
        $responseData = [
            'data' => new BaseResource($user),
            'token' => $token
        ];

        return $this->respondOK($responseData, 'Login successful');
    }

    /**
     * Register a new user.
     * @unauthenticated
     * @response array{success: string, message: string, status: string, code: integer, data: User, token: string}
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());

        $deviceName = $request->userAgent() ?? 'device_'.$user->id.'_'.now()->timestamp;
        $token = $user->createToken($deviceName)->accessToken;

        $responseData = [
            'data' => new BaseResource($user),
            'token' => $token
        ];

        return $this->respondOK($responseData, 'Registration successful');
    }

    /**
     * Retrieve the authenticated user's information.
     * @response array{success: string, message: string, status: string, code: integer, data: User, valid: boolean}
     */
    public function me(Request $request)
    {
        $user = $request->user();
        throw_if(!$user, new NotFoundHttpException('Token is invalid.'));

        $responseData = [
            'data' => new BaseResource($user),
            'valid' => true,
        ];

        return $this->respondOK($responseData, 'Token is valid');
    }

    /**
     * Handle user logout and token revocation.
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        throw_if(!$user, new AccessDeniedHttpException('User not authenticated.'));

        $user->last_login_at = now();
        $user->save();
        $token = $user->token();
        $token->revoke();

        return $this->respondOK(null, 'Logout successful');
    }
    
    public function generateApiToken(Request $request)
    {
        return $this->respondOK([
            'header' => DefaultEnum::APITOKEN,
            'token' => ApiTokenHelper::generate()
        ], 'API token generated successfully');
    }
}
