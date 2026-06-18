<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Setting\BulkUpdateRequest;
use App\Http\Requests\Setting\IndexRequest;
use App\Http\Resources\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends BaseApiController
{
    public function __construct(public SettingService $settingService) {}

    public function index(IndexRequest $request): JsonResponse
    {
        $indexes = $this->prepareIndexes($request->all());
        $results = $this->settingService->findByIndexes(
            $indexes['indexes'],
            $indexes['any'],
            $indexes['limit'],
            $indexes['orderBy'],
            $indexes['qcomparator'],
            $indexes['filters']
        );

        $formattedResponse = $this->paginateResponse($results, SettingResource::class);

        return $this->setStatusMsg($formattedResponse['meta']['total'] ? 'success' : 'failed')
            ->respondOK($formattedResponse, $formattedResponse['meta']['total'] ? __('messages.data_found') : __('messages.data_not_found'));
    }

    public function bulkUpdate(BulkUpdateRequest $request): JsonResponse
    {
        $settings = $request->input('settings', []);
        $this->settingService->setMany($settings);

        return $this->respondOK(null, __('messages.resources_updated', ['count' => count($settings)]), true);
    }
}
