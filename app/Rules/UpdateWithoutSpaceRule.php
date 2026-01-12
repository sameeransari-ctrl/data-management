<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateWithoutSpaceRule implements Rule
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
            if (!preg_match('/^\S*$/u', $value)) {
                return false;
            }
        } else if ($value != null) {
            if (!preg_match('/^\S*$/u', $value)) {
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
        return trans('validation.custom.remove_space');
    }
}
