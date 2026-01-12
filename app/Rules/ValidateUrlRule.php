<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ValidateUrlRule implements Rule
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
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        if (!preg_match($regex, $value)) {
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
        return trans('validation.url');
    }
}
