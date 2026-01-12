<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * RemoveSpace
 */
class RemoveSpace implements Rule
{
    
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute 
     * @param mixed  $value  
     * 
     * @return bool 
     */
    public function passes($attribute, $value)
    {
        if (trim($value) == '') {
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
        return trans('validation.custom.remove_space');
    }
}
