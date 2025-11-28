<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityRequirement;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Scramble::ignoreDefaultRoutes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->info->description = 'API for the best Todo app!';
            $openApi->components->securitySchemes['bearer'] = SecurityScheme::http('bearer');
            $openApi->security[] = new SecurityRequirement([
                'bearer' => [],
            ]);
        });
        Scramble::registerApi('api/v1', [
            'api_path' => 'api/v1',
        ]);
        Route::pattern('idOrSlug', '[0-9]+|[a-z0-9-]+');
    }
}
