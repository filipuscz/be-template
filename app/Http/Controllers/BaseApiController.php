<?php

namespace App\Http\Controllers;

use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller
{
    protected $statusCode = 200;
    protected $statusMsg = "success";
    protected $recordLimit = 25;

    public function __construct()
    {
    }

    /**
     * Respond with a JSON response.
     *
     * @param mixed $data The data to be returned.
     * @param int|null $status The HTTP status code.
     * @param array $headers Additional headers to be sent with the response.
     * @return JsonResponse
     */
    public function respond($data, $status = null, $headers = []): JsonResponse
    {
        return Response::json($data, $status ?? $this->statusCode, $headers);
    }

    /**
     * Respond with a JSON response with a message.
     *
     * @param string $message The message to be returned.
     * @param bool $success Indicates if the response is successful.
     * @param array $extras Additional data to be included in the response.
     * @return JsonResponse
     */
    public function respondDetail($message, $success = true, $extras = []): JsonResponse
    {
        $responseArray = [
            'success' => $success,
            'message' => $message,
            'status' => $this->statusMsg,
            'code' => $this->statusCode,
        ];

        if (!empty($extras)) {
            $responseArray = array_merge($responseArray, $extras);
        }

        return $this->respond($responseArray);
    }

    public function setStatusCode($statusCode): BaseApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setStatusMsg($message): BaseApiController
    {
        $this->statusMsg = $message;
        return $this;
    }

    public function generateResponse($statusCode, $extras = null, $message = '', $success = true): JsonResponse
    {
        return $this->setStatusCode($statusCode)->respondDetail($message, $success, $extras);
    }

    public function respondOK($extras = null, $message = 'Success!', $success = true): JsonResponse
    {
        return $this->generateResponse(200, $extras, $message, $success);
    }

    public function respondCreated($extras = null, $message = 'The resource has been created', $success = true): JsonResponse
    {
        return $this->generateResponse(201, $extras, $message, $success);
    }

    public function respondDeleted($extras = null, $message = 'The resource has been deleted', $success = false): JsonResponse
    {
        return $this->generateResponse(204, $extras, $message, $success);
    }

    public function respondRedirect(string $url, $status = 302, array $headers = []): JsonResponse
    {
        return $this->generateResponse($status, ['redirect_uri' => $url], null, true, $headers);
    }

    public function respondBadRequest($extras = null, $message = 'Bad request!', $success = false): JsonResponse
    {
        return $this->setStatusMsg("failed")->generateResponse(400, $extras, $message, $success);
    }

    public function respondUnauthorized($extras = null, $message = 'Unauthorized!', $success = false): JsonResponse
    {
        return $this->setStatusMsg("failed")->generateResponse(401, $extras, $message, $success);
    }

    public function respondForbidden($extras = null, $message = 'Forbidden!', $success = false): JsonResponse
    {
        return $this->setStatusMsg("failed")->generateResponse(403, $extras, $message, $success);
    }

    public function respondNotFound($extras = null, $message = 'Not found!', $success = true): JsonResponse
    {
        return $this->setStatusMsg("failed")->generateResponse(404, $extras, $message, $success);
    }

    public function respondConflict($extras = null, $message = 'Conflict!', $success = false): JsonResponse
    {
        return $this->generateResponse(409, $extras, $message, $success);
    }

    public function respondInternalError($extras = null, $message = 'Internal error!', $success = false): JsonResponse
    {
        return $this->setStatusMsg("failed")->generateResponse(500, $extras, $message, $success);
    }

    public function respondUnprocessableEntity($extras = null, $message = 'Unprocessable entity!', $success = false): JsonResponse
    {
        return $this->generateResponse(422, $extras, $message, $success);
    }

    public function respondNotAcceptable($extras = null, $message = 'Not acceptable!', $success = false): JsonResponse
    {
        return $this->generateResponse(406, $extras, $message, $success);
    }

    public function respondTooManyRequests($extras = null, $message = 'Too many requests!', $success = false): JsonResponse
    {
        return $this->generateResponse(429, $extras, $message, $success);
    }

    public function respondNotImplemented($extras = null, $message = 'Not implemented!', $success = false): JsonResponse
    {
        return $this->generateResponse(501, $extras, $message, $success);
    }

    public function respondServiceUnavailable($extras = null, $message = 'Service unavailable!', $success = false): JsonResponse
    {
        return $this->generateResponse(503, $extras, $message, $success);
    }

    /**
     * Paginate the response data.
     *
     * @param LengthAwarePaginator|Collection $paginatedData The paginated data.
     * @param string|null $resource The resource class name for transformation.
     * @return array The paginated response data.
     */
    public function paginateResponse(LengthAwarePaginator|Collection $paginatedData, $resource = null): array
    {
        if ($paginatedData instanceof LengthAwarePaginator) {
            if (is_subclass_of($resource, JsonResource::class)) {
                $items = $resource::collection($paginatedData->items());
            } else {
                $items = $paginatedData->items();
            }
            return [
                'data' => $items,
                'meta' => [
                    'total' => $paginatedData->total(),
                    'per_page' => $paginatedData->perPage(),
                    'current_page' => $paginatedData->currentPage(),
                    'last_page' => $paginatedData->lastPage(),
                    'total_page' => ceil($paginatedData->total() / $paginatedData->perPage())
                ],
                'links' => [
                    'first' => $paginatedData->url(1),
                    'last' => $paginatedData->url($paginatedData->lastPage()),
                    'prev' => $paginatedData->previousPageUrl(),
                    'next' => $paginatedData->nextPageUrl(),
                ],
            ];
        } elseif ($paginatedData instanceof Collection) { // Case when the data is not paginated / limit is -1
            if (is_subclass_of($resource, JsonResource::class)) {
                $items = $resource::collection($paginatedData->all());
            } else {
                $items = $paginatedData->all();
            }

            return [
                'data' => $items,
                'meta' => [
                    'total' => $paginatedData->count(),
                    'limit' => $paginatedData->count(),
                    'last_page' => 1,
                    'total_page' => 1
                ],
                'links' => [
                    'first' => null,
                    'last' => null,
                    'prev' => null,
                    'next' => null,
                ],
            ];
        }

        throw new \InvalidArgumentException('Invalid type for $paginatedData');
    }

    public function formatErrors($errors): array
    {
        $bag = [];

        foreach ($errors as $value) {
            $key = explode(' ', $value)[0];
            $bag[] = [
                'name' => $key,
                'message' => $value,
            ];
        }

        return $bag;
    }

    /**
     * Prepare the $request for findByIndexes method.
     *
     * @param Request $request The request object.
     * @return array The prepared indexes.
     */
    public function prepareIndexes(array $indexes): array
    {
        $filters = $indexes['filters'] ?? [];
        $orderByColumns = $indexes['orderByColumns'] ?? [];
        $orderBy = $orderByColumns ? explode(",", $orderByColumns) : [];
        $any = $indexes['any'] ?? false;
        $any = filter_var($any, FILTER_VALIDATE_BOOLEAN);
        $limit = $indexes['limit'] ?? 10;
        $comparator = $indexes['comparator'] ?? 'ilike';
        $qcomparator = QueryAcceptedComparatorEnum::tryFrom($comparator) ?? QueryAcceptedComparatorEnum::EQUAL;

        if (isset($indexes['ignoreIds'])) {
            $indexes['ignore'] = explode(',', $indexes['ignoreIds']);
            unset($indexes['ignoreIds']);
        }

        return [
            'indexes' => $indexes,
            'any' => $any,
            'limit' => $limit,
            'orderBy' => $orderBy,
            'qcomparator' => $qcomparator,
            'filters' => $filters
        ];
    }
}
