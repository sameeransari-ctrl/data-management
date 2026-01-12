<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 1; //active
    public const STATUS_INACTIVE = 0; //inactive

    public const VERIFICATION_UDI_EU = 1;

    public const VERIFICATION_EUDAMED = 2;

    public static $productVerificationTypes = [
        self::VERIFICATION_UDI_EU => "UDI.eu Verified",
        self::VERIFICATION_EUDAMED => "Eudamed Verified",
    ];

    protected $fillable = [
        'client_id',
        'client_name',
        'product_name',
        'product_description',
        'udi_number',
        'plain_udi_number',
        'basic_udid_id',
        'class_id',
        'image_url',
        'verification_by',
        'is_import',
        'status',
        'added_by',
        'updated_by',
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * Method getImageUrlAttribute
     *
     * @param $value $value [explicite description]
     *
     * @return void
     */
    public function getImageUrlAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $url = $value;
        } else {
            $url = getImageUrl($value, 'product');
        }
        return $url;
    }

    /**
     * Method productFiles
     *
     * @return void
     */
    public function productFiles()
    {
        return $this->hasMany(ProductFile::class);
    }

    /**
     * Method user
     *
     * @return void
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Method productClass
     *
     * @return void
     */
    public function productClass()
    {
        return $this->belongsTo(ProductClass::class, 'class_id');
    }

    /**
     * Method productQuestions
     *
     * @return void
     */
    public function productQuestions()
    {
        return $this->hasMany(ProductQuestion::class, "product_id");
    }

    /**
     * Method productAnswers
     *
     * @return void
     */
    public function productAnswers()
    {
        return $this->hasMany(ProductAnswer::class, "product_id");
    }

    /**
     * Method productRatingReview
     *
     * @return void
     */
    public function productRatingReview()
    {
        return $this->hasMany(RatingReview::class, "product_id");
    }

    /**
     * Method productScanner
     *
     * @return void
     */
    public function productScanner()
    {
        return $this->hasMany(ProductScanner::class, "product_id");
    }

    /**
     * Method findSafetyNotice
     *
     * @return void
     */
    public function findSafetyNotice()
    {
        return $this->hasMany(FieldSafetyNotice::class);
    }

    /**
     * Method productClass
     *
     * @return void
     */
    public function basicUdid()
    {
        return $this->belongsTo(BasicUdid::class, 'basic_udid_id');
    }

    /**
     * Method findSafetyNotices
     *
     * @return void
     */
    public function findSafetyNotices()
    {
        return $this->hasMany(FieldSafetyNotice::class);
    }

}
