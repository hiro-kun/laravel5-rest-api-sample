<?php

namespace App\Library\Validation;

class MemberValidation
{
    public static function memberValidate($request)
    {
        $validationResult = [
            "isError" => false,
            "message" => NULL,
            "field"   => NULL,
        ];

        $request['email'] = $request['email'] ?? '';
        $request['name']  = $request['name'] ?? '';
        $request['sex']   = $request['sex'] ?? '';

        $emailCheckResult = \App\Library\Validation\EmailValidation::isActiveEmail($request['email']);
        if ($emailCheckResult !== true) {
            $validationResult["isError"] = true;
            $validationResult["field"]   = "email";
            $validationResult["message"] = $emailCheckResult;
        }
        $nameCheckResult = \App\Library\Validation\NameValidation::isActiveName($request['name']);
        if ($nameCheckResult !== true) {
            $validationResult["isError"] = true;
            $validationResult["field"]   = "name";
            $validationResult["message"] = $nameCheckResult;
        }
        $sexCheckResult = \App\Library\Validation\SexValidation::isActiveSex($request['sex']);
        if ($sexCheckResult !== true) {
            $validationResult["isError"] = true;
            $validationResult["field"]   = "sex";
            $validationResult["message"] = $sexCheckResult;
        }

        return $validationResult;
    }
}
