<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
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
     */
    public function index(Request $request)
    {
        try {
            $indexes = $this->prepareIndexes($request);
            $results = $this->exampleService->findByIndexes(
                $indexes['indexes'],
                $indexes['any'],
                $indexes['limit'],
                $indexes['orderBy'],
                $indexes['qcomparator'],
                $indexes['fields'],
            );
            $formattedResponse = $this->paginateResponse($results, BaseResource::class);
            return $this->setStatusMsg($formattedResponse['meta']['total'] ? 'success' : 'failed')
                ->respondOK($formattedResponse, $formattedResponse['meta']['total'] ? 'Data Found' : 'Data Not Found');
        } catch (\Throwable $e) {
            return $this->respondInternalError(null, $e->getMessage());
        }
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
     */
    public function store(StoreExampleRequest $request)
    {
        try {
            $data = $this->exampleService->create($request->all());
            $response = $this->respondOK(array(
                'data' => new BaseResource($data),
            ));
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idOrSlug)
    {
        try {
            $data = $this->exampleService->findById($idOrSlug);
            throw_if((empty($data)), new NotFoundHttpException(404));

            $response = $this->setStatusMsg("success")->respondOK(array(
                'data' => new BaseResource($data)
            ));
        } catch (\Throwable $th) {
            $response = $this->setStatusMsg("failed")->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idOrSlug)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idOrSlug)
    {
        try {
            $data = $this->exampleService->update($request->all(), $idOrSlug);
            $response = $this->respondOK(array(
                'data' => new BaseResource($data),
            ));
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idOrSlug)
    {
        try {
            $data = $this->exampleService->findById($idOrSlug);
            throw_if(!$data, new NotFoundHttpException("Data Not Found"));

            $data->delete();
            $response = $this->respondOK(null, "The resource has been deleted", true);
        } catch (\Throwable $th) {
            $response = $this->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }
}
