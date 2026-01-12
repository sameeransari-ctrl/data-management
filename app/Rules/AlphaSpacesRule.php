<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * AlphaSpacesRule
 */
class AlphaSpacesRule implements Rule
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
        if (!preg_match('/^[\pL\s]+$/u', $value)) {
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
        return trans('validation.alpha_spaces');
    }
}
