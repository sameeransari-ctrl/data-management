<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * StrictPasswordRule
 */
class StrictPasswordRule implements Rule
{
    
    /**
     * Determine if the validation rule passes.
     *
     * @param $attribute string
     * @param $value     mixed
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!preg_match('/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,15}$/u', $value)) {
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
        return trans('validation.password.strict');
    }
}
