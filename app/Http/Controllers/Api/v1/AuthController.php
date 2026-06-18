<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\DefaultEnum;
use App\Helpers\ApiTokenHelper;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\BaseResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends BaseApiController
{
    public function __construct(private AuthService $authService) {}

    /**
     * Handle user login and token generation.
     *
     * @unauthenticated
     *
     * @response array{success: string, message: string, status: string, code: integer, data: User, token: string}
     */
    public function login(LoginRequest $request)
    {
        $user = $request->user();
        throw_if(! $user, new \Exception(__('exceptions.invalid_credentials')));

        $result = $this->authService->login($user, $request->userAgent() ?? '');

        $responseData = [
            'data' => new BaseResource($result['user']),
            'token' => $result['token'],
        ];

        return $this->respondOK($responseData, __('messages.login_successful'));
    }

    /**
     * Register a new user.
     *
     * @unauthenticated
     *
     * @response array{success: string, message: string, status: string, code: integer, data: User, token: string}
     */
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register(
            $request->all(),
            $request->userAgent() ?? '',
            $request->ip()
        );

        $responseData = [
            'data' => new BaseResource($result['user']),
            'token' => $result['token'],
        ];

        return $this->respondOK($responseData, __('messages.registration_successful'));
    }

    /**
     * Retrieve the authenticated user's information.
     *
     * @response array{success: string, message: string, status: string, code: integer, data: User, valid: boolean}
     */
    public function me(Request $request)
    {
        $user = $request->user();
        throw_if(! $user, new NotFoundHttpException(__('exceptions.token_invalid')));

        $responseData = [
            'data' => new BaseResource($user),
            'valid' => true,
        ];

        return $this->respondOK($responseData, __('messages.token_valid'));
    }

    /**
     * Handle user logout and token revocation.
     *
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        throw_if(! $user, new AccessDeniedHttpException(__('exceptions.user_not_authenticated')));

        $this->authService->logout($user);

        return $this->respondOK(null, __('messages.logout_successful'));
    }

    public function generateApiToken(Request $request)
    {
        return $this->respondOK([
            'header' => DefaultEnum::APITOKEN,
            'token' => ApiTokenHelper::generate(),
        ], __('messages.api_token_generated'));
    }
}
