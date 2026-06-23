<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseApiController;
use App\Services\LanguageService;
use Illuminate\Http\JsonResponse;

class LanguageController extends BaseApiController
{
    public function __construct(
        protected LanguageService $languageService
    ) {}

    public function show(string $locale): JsonResponse
    {
        try {
            $translations = $this->languageService->getTranslations($locale);

            return $this->respondOK(['data' => $translations]);
        } catch (\Exception $e) {
            return $this->respondNotFound(null, 'Language not found');
        }
    }
}
