<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckDescription implements Rule
{
    private $type;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Method passes
     *
     * @param $attribute string
     * @param $value  string
     *
     * @return boolean
     */
    public function passes($attribute, $value)
    {
        $description = strip_tags($value);
        if (empty($description)) {
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
        return ($this->type == 'page_content') ? trans('validation.custom.page_content_required'): trans('validation.custom.answer_required');
    }
}
