<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateStrictPasswordRule implements Rule
{
    protected $staffId;

    /**
     * Method __construct
     *
     * @param $staffId $staffId [explicite description]
     *
     * @return void
     */
    public function __construct($staffId)
    {
        $this->staffId = $staffId;
    }

    /**
     * Method passes
     *
     * @param $attribute $attribute [explicite description]
     * @param string \App\Rules\ $value     $value — [explicite description]
     *
     * @return void
     */
    public function passes($attribute, $value)
    {
        if ($this->staffId == null) {
            if (!preg_match('/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,15}$/u', $value)) {
                return false;
            }
        } else if ($value != null) {
            if (!preg_match('/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,15}$/u', $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Method message
     *
     * @return void
     */
    public function message()
    {
        return trans('validation.password.strict');
    }
}
