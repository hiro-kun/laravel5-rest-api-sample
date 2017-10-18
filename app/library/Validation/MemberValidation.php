<?php

namespace App\library\Validation;

class MemberValidation
{
    public static function memberValidate()
    {
        $validationResult = [
            "isError"        => false,
            "message"   => NULL,
            "field" => NULL,
        ];

        $request = \Request::all();

        if (empty($request["email"])) {
            $validationResult["isError"]        = true;
            $validationResult["field"] = "email";
            $validationResult["message"]   = "Field 'email' not found.";

            return $validationResult;
        }

        if (empty($request["name"])) {
            $validationResult["isError"]        = true;
            $validationResult["field"] = "name";
            $validationResult["message"]   = "Field 'name' not found.";

            return $validationResult;
        }

        if (empty($request["sex"])) {
            $validationResult["isError"]        = true;
            $validationResult["field"] = "sex";
            $validationResult["message"]   = "Field 'sex' not found.";

            return $validationResult;
        }


        $emailValidateRules = [
            "email"  => "required|email",
        ];
        $emailValidateMessages = [
            "email" => "Invalid value. The email must be a valid email address.",
        ];
        $emailValidateResult = \Validator::make(
            $request,
            $emailValidateRules,
            $emailValidateMessages
        );
        if ($emailValidateResult->fails() === true) {
            $validationResult["isError"]        = true;
            $validationResult["field"] = "email";
            $validationResult["message"]   = $emailValidateResult->getMessageBag()->messages()["email"][0];

            return $validationResult;
        }


        $sexValidateRules = [
            "sex"    => "required|string|in:male,female"
        ];
        $sexValidateMessages = [
            "in"    => "Invalid value. Must enter 'male' ore 'female'.",
        ];
        $sexValidateResult = \Validator::make(
            $request,
            $sexValidateRules,
            $sexValidateMessages
        );
        if ($sexValidateResult->fails() === true) {
            $validationResult["isError"]        = true;
            $validationResult["field"] = "sex";
            $validationResult["message"]   = $sexValidateResult->getMessageBag()->messages()["sex"][0];

            return $validationResult;
        }

        return $validationResult;
    }
}
