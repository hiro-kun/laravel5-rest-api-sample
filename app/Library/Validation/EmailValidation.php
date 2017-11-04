<?php

namespace App\Library\Validation;

class EmailValidation
{
    public static function isActiveEmail($emailAdress)
    {
        $validateArray          = [];
        $validateArray['email'] = $emailAdress;

        $emailValidateRules = [
            "email"  => "required|email",
        ];
        $emailValidateMessages = [
            "email" => "Invalid value. The email must be a valid email address.",
        ];
        $emailValidateResult = \Validator::make(
            $validateArray,
            $emailValidateRules,
            $emailValidateMessages
        );

        if ($emailValidateResult->fails() === true) {
            return $emailValidateResult->getMessageBag()->messages()["email"][0];
        }

        return true;
    }
}
