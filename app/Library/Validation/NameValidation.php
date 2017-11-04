<?php

namespace App\Library\Validation;

class NameValidation
{
    public static function isActiveName($name)
    {
        $validateArray         = [];
        $validateArray['name'] = $name;

        $namelValidateRules = [
            "name"  => "required",
        ];
        $namelValidateResult = \Validator::make(
            $validateArray,
            $namelValidateRules
        );

        if ($namelValidateResult->fails() === true) {
            return $namelValidateResult->getMessageBag()->messages()["name"][0];
        }

        return true;
    }
}
