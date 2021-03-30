<?php
namespace App\Helpers;

class ResponseHelper
{
    public function successResponseData($message,$data) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function successData($data) {
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function successResponse($message) {
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function errorResponse($message) {
        return response()->json([
            'success' => false,
            'message' => $message
        ]);
    }
}