<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\StatusEnum;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\LoginAuthRequest;
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
    public function login(LoginAuthRequest $request)
    {
        $user = $request->user();
        if($user->is_active == 0)
        {
            throw new \Exception('Your account is inactive.');
        }

        $deviceName = $request->header('User-Agent', 'unknown');
        $token = $user->createToken($deviceName)->accessToken;
        
        $responseData = array(
            'data' => new BaseResource($user),
            'token' => $token
        );

        return $this->respondOK($responseData, 'Login successful');
    }

    /**
     * Retrieve the authenticated user's information.
     * @response array{success: string, message: string, status: string, code: integer, data: User, valid: boolean}
     */
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            throw new NotFoundHttpException('Token is invalid.');
        }

        $responseData = array(
            'data' => new BaseResource($user),
            'valid' => true,
        );

        return $this->respondOK($responseData, 'Token is valid');
    }

    /**
     * Handle user logout and token revocation.
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->last_login_at = now();
            $user->save();
            $token = $user->token();
            $token->revoke();

            return $this->respondOK(null, 'Logout successful');
        }

        throw new AccessDeniedHttpException('User not authenticated.');
    }
}
