<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Method successResponse
     *
     * @param string     $message 
     * @param array|null $data 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message = '', $data = null)
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data,
                'message' => $message
            ],
            200
        );
    }

    /**
     * Method errorResponse
     *
     * @param string $message 
     * @param int    $errorCode 
     * @param array  $data 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(
        string $message = '',
        $errorCode = 400,
        $data = null
    ) {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];
        return $errorCode ? response()->json($response, $errorCode) : response()->json($response);
    }

    /**
     * Method errorResponse
     *
     * @param string $message 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteResponse(
        string $message = ''
    ) {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => []
        ];
        return response()->json($response, 204);
    }
}
