<?php

namespace App\Rules;

use File;
use Illuminate\Contracts\Validation\Rule;

class ValidateImageUrlRule implements Rule
{
    protected $extentionType;
    protected $allowedExtention;

    /**
     * Method __construct
     *
     * @param $userType $userType 
     *
     * @return void
     */
    public function __construct(string $extentionType)
    {
        $imageExtension = ['jpg', 'jpeg', 'png'];
        $fileExtension = ['png', 'jpg', 'jpeg', 'svg', 'mp4', 'ogx', 'oga', 'ogv', 'ogg', 'webm', '3gp', 'mov', 'xlsx', 'xls'];

        $this->extentionType = $extentionType;
        $this->allowedExtention = ($extentionType=='image') ? $imageExtension : $fileExtension;
    }

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
        $url = $value;
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $data = explode('? ', $url); 
        $data = explode('/', $data[0]); 

        $count = count($data);
        $filename = $data[$count-1];
        $extention = File::extension($filename);
        
        if (!in_array($extention, $this->allowedExtention)) {
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
