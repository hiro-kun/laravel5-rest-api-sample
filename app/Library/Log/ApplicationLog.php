<?php

namespace App\Library\Log;

class ApplicationLog
{
    public static function makeErrorLog(\Exception $exception)
    {
        $errorInfo            = [];
        $errorInfo['message'] = $exception->getMessage();
        $errorInfo['code']    = $exception->getMessage();
        $errorInfo['trace']   = $exception->getTraceAsString();

        \Log::error("error", ["errorInfo" => $errorInfo]);
    }
}
