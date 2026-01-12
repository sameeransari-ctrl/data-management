<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Country
 */
class Country extends Model
{
    use HasFactory;

    public $fillable = ['name', 'code', 'phone_code', 'status'];

    protected $appends = [
        'flag_image_url',
    ];

    /**
     * Get the country code.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getFlagImageUrlAttribute()
    {
        return config('app.url').'/assets/images/flags/'.$this->code.'.png';
    }

    /**
     * Method productScanners
     *
     * @return void
     */
    public function productScanners()
    {
        return $this->hasMany(ProductScanner::class);
    }
}
