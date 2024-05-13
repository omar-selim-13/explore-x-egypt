<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
    public function apiresponse( $status = 200, $message = null,$data = null)
    {

        $contant = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response($contant, $status);
    }
}
