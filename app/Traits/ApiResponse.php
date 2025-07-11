<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function errorResponse($message = 'Something went wrong', $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null
        ], $status);
    }}