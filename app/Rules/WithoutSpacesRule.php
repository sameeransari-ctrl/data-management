<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * WithoutSpacesRule
 */
class WithoutSpacesRule implements Rule
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
        if (!preg_match('/^\S*$/u', $value)) {
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
