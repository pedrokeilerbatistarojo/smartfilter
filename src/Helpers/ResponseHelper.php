<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Helpers;

use Pedrokeilerbatistarojo\Smartfilter\Dtos\ResponseObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     *
     * Success response method.
     * @param mixed $payload
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public static function sendResponse(mixed $payload, string $message = '', int $code = Response::HTTP_OK): JsonResponse
    {
        if ($payload instanceof LengthAwarePaginator || $payload instanceof AnonymousResourceCollection ) {

            $data = $payload->items();

            $paginationData = [
                'currentPage' => $payload->currentPage(),
                'lastPage' => $payload->lastPage(),
                'itemsPerPage' => $payload->perPage(),
                'total' => $payload->total(),
            ];

            $payload = [
                'items' => $data,
                'metadata' => $paginationData,
                'total' => $payload->total(),
            ];

        }

        $responseObj = new ResponseObject();
        $responseObj->message = $message;
        $responseObj->payload = $payload;

        return response()->json($responseObj, $code);
    }

    /**
     * return error response.
     * @param string $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public static function sendError(string $message, array $errors = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $responseObj = new ResponseObject();
        $responseObj->success = false;
        $responseObj->message = $message;
        $responseObj->errors = count($errors) ? $errors : [$message];
        return response()->json($responseObj, $code);
    }
}
