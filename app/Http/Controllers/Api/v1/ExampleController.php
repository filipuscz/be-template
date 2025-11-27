<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\BaseBulkDestroyRequest;
use App\Http\Requests\BaseBulkUpdateRequest;
use App\Http\Requests\BaseIndexRequest;
use App\Http\Requests\StoreExampleRequest;
use App\Http\Resources\BaseResource;
use App\Services\BaseService;
use App\Services\ExampleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExampleController extends BaseApiController
{
    public function __construct(public ExampleService $exampleService) {
    }
    /**
     * Display a listing of the resource.
     * @response array{success: string, message: string, status: string, code: integer, data: collection,
     * meta: array{}, links: array{}}
     */
    public function index(BaseIndexRequest $request)
    {
        // getPrintableColumns
        $indexes = $this->prepareIndexes($request->all());
        $results = $this->exampleService->findByIndexes(
            $indexes['indexes'],
            $indexes['any'],
            $indexes['limit'],
            $indexes['orderBy'],
            $indexes['qcomparator'],
            $indexes['filters']
        );
        $formattedResponse = $this->paginateResponse($results, BaseResource::class);
        return $this->setStatusMsg($formattedResponse['meta']['total'] ? 'success' : 'failed')
            ->respondOK($formattedResponse, $formattedResponse['meta']['total'] ? 'Data Found' : 'Data Not Found');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @response array{success: string, message: string, status: string, code: integer, data: array{}}
     */
    public function store(StoreExampleRequest $request)
    {
        $data = $this->exampleService->create($request->all());
        return $this->respondOK(array(
            'data' => new BaseResource($data),
        ));
    }

    /**
     * Display the specified resource.
     * @response array{success: string, message: string, status: string, code: integer, data: array{}}
     */
    public function show(string $idOrSlug)
    {
        $data = $this->exampleService->findById($idOrSlug);
        throw_if((empty($data)), new NotFoundHttpException("Data Not Found"));

        return $this->setStatusMsg("success")->respondOK(array(
            'data' => new BaseResource($data)
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idOrSlug)
    {
    }

    /**
     * Update the specified resource in storage.
     * @response array{success: string, message: string, status: string, code: integer, data: array{}}
     */
    public function update(Request $request, string $idOrSlug)
    {
        $data = $this->exampleService->update($request->all(), $idOrSlug);
        return $this->respondOK(array(
            'data' => new BaseResource($data),
        ));
    }

    /**
     * Update multiple resources in storage.
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function bulkUpdate(BaseBulkUpdateRequest $request)
    {
        $ids = $request->input('ids', []);
        // except ids
        $updateData = $request->except('ids');
        if (empty($ids) || !is_array($ids)) {
            throw new NotFoundHttpException("No IDs provided for update");
        }
        if (empty($updateData) || !is_array($updateData)) {
            throw new NotFoundHttpException("No data provided for update");
        }
        $count = $this->exampleService->updateMany($ids, $updateData);
        return $this->respondOK(null, "$count resources have been updated", true);
    }

    /**
     * Remove the specified resource from storage.
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function destroy(string $idOrSlug)
    {
        $data = $this->exampleService->findById($idOrSlug);
        throw_if(!$data, new NotFoundHttpException("Data Not Found"));

        $this->exampleService->delete($idOrSlug);
        return $this->respondOK(null, "The resource has been deleted", true);
    }

    /** 
     * Remove multiple resources from storage.
     * @response array{success: string, message: string, status: string, code: integer}
     */
    public function bulkDestroy(BaseBulkDestroyRequest $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            throw new NotFoundHttpException("No IDs provided for deletion");
        }
        $count = $this->exampleService->deleteMany($ids);
        return $this->respondOK(null, "$count resources have been deleted", true);
    }
}
