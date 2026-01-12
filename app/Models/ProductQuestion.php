<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductQuestion extends Model
{
    use HasFactory, SoftDeletes;

    public const QUESTION_TYPE_BY_REVIEW = 1;
    public const QUESTION_TYPE_BY_PRODUCT = 2;
    
    public const PRODUCT_ANSWER_TYPE_CHECK_BOX_VALUE = 1;
    public const PRODUCT_ANSWER_TYPE_RADIO_BUTTON_VALUE = 2;
    public const PRODUCT_ANSWER_TYPE_INPUT_FIELD_VALUE = 3;

    public const PRODUCT_ANSWER_TYPE_CHECK_BOX_TEXT = "check_box";
    public const PRODUCT_ANSWER_TYPE_RADIO_BUTTON_TEXT = "radio_button";
    public const PRODUCT_ANSWER_TYPE_INPUT_FIELD_TEXT = "input_field";

    public static $productAnswerTypeValue = [
        self::PRODUCT_ANSWER_TYPE_CHECK_BOX_TEXT => self::PRODUCT_ANSWER_TYPE_CHECK_BOX_VALUE,
        self::PRODUCT_ANSWER_TYPE_RADIO_BUTTON_TEXT => self::PRODUCT_ANSWER_TYPE_RADIO_BUTTON_VALUE,
        self::PRODUCT_ANSWER_TYPE_INPUT_FIELD_TEXT => self::PRODUCT_ANSWER_TYPE_INPUT_FIELD_VALUE
    ];

    public static $productQuestionTypes = [
        self::QUESTION_TYPE_BY_REVIEW => "review",
        self::QUESTION_TYPE_BY_PRODUCT => "product",
    ];

    protected $fillable = [
        'product_id',
        'question_type',
        'question_title',
        'answer_type',
        'answer_options',
        'default_answer',
    ];

    /**
     * Method product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
