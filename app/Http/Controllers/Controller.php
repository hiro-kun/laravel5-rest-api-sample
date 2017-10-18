<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function errorResponse($message, $field, $code, $requestId)
    {
        $errorResponse["request_id"]      = $requestId;
        $errorResponse["message"]         = $message;
        $errorResponse["errors"]["field"] = $field;
        $errorResponse["errors"]["code"]  = $code;

        return $errorResponse;
    }
}
