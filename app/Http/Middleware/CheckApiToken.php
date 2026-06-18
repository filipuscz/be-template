<?php

namespace App\Http\Middleware;

use App\Helpers\ApiTokenHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('X-API-TOKEN');
        throw_if(! $header, new \Exception(__('exceptions.api_token_missing')));

        $boolValidation = ApiTokenHelper::validation($header);
        throw_if(! $boolValidation, new \Exception(__('exceptions.api_token_validation_failed')));

        return $next($request);
    }
}
