<?php

namespace App\Service;

class ServiceFactory
{
    public static function create($categoryName, $serviceName)
    {
        self::fileExistsCheck($categoryName, $serviceName);
        $className = "\\App\\Service\\" . $categoryName . "\\" . $serviceName . 'Service';

        return new $className();
    }

    private static function fileExistsCheck($categoryName, $serviceName)
    {
        $filePath = dirname(__FILE__) . '/' . $categoryName . '/' . $serviceName . 'Service.php';

        if (file_exists($filePath) === false) {
            throw new \App\Exceptions\ApplicationException();
        }
    }
}
