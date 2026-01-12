<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAnswer extends Model
{
    use HasFactory, SoftDeletes;

    public const ANSWER_CHECKBOX = 1;

    public const ANSWER_RADIO = 2;

    public const ANSWER_INPUT = 3;

    public static $productAnswerTypes = [
        self::ANSWER_CHECKBOX => "check_box",
        self::ANSWER_RADIO => "radio",
        self::ANSWER_INPUT => "input",
    ];

    public const QUESTION_REVIEW = 1;

    public const QUESTION_PRODUCT = 2;

    public static $productQuestionTypes = [
        self::QUESTION_REVIEW => "review",
        self::QUESTION_PRODUCT => "product",
    ];
    
    protected $fillable = [
        'user_id',
        'product_id',
        'product_question_id',
        'question_type',
        'question_title',
        'answer_type',
        'answer_options',
        'answer',
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

    /**
     * Method product
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
