<?php

namespace App\library\Log;

class ApplicationLog
{
    public static function makeErrorLog(array $errorInfo)
    {
        \Log::error("error", ["errorInfo" => $errorInfo]);
    }
}
