<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;
use Throwable;

class Controller extends BaseController
{
    public function fail(int $httpCode, $data, ?string $message = null): JsonResponse {
        return response()->json(
            [
                'status' => 'fail',
                'message' => $message,
                'data' => $data
            ]
        )->setStatusCode($httpCode);
    }

    public function error(
        int $httpCode,
        int $errorCode,
        $data,
        ?string $message = null,
        ?Throwable $throwable = null
    ): JsonResponse {
        if ($throwable) {
            if (!$data) {
                $data = [];
            }

            $data['exception'] = (string) $throwable;
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => $message,
                'code' => $errorCode,
                'data' => $data
            ]
        )->setStatusCode($httpCode);
    }
}
