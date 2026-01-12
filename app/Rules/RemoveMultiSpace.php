<?php

namespace App\Rules;

use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Validation\Rule;

/**
 * RemoveMultiSpace
 */
class RemoveMultiSpace implements Rule
{
    
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute 
     * @param mixed  $value 
     * 
     * @return boolean 
     */
    public function passes($attribute, $value)
    {

        $multiSpace = Config::get('constants.regex_validation.multi_space');
        if (preg_match($multiSpace, $value)) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.single_space');
    }
}
