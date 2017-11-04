<?php

namespace App\Library\Validation;

class SexValidation
{
    public static function isActiveSex($sex)
    {
        $validateArray        = [];
        $validateArray['sex'] = $sex;

        $sexValidateRules = [
            "sex"    => "required|string|in:male,female"
        ];
        $sexValidateMessages = [
            "in"    => "Invalid value. Must enter 'male' ore 'female'.",
        ];
        $sexValidateResult = \Validator::make(
            $validateArray,
            $sexValidateRules,
            $sexValidateMessages
        );

        if ($sexValidateResult->fails() === true) {
            return $sexValidateResult->getMessageBag()->messages()["sex"][0];
        }

        return true;
    }
}
