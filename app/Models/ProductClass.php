<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductClass extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 1; //active

    public const STATUS_INACTIVE = 0; //inactive

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Method product
     *
     * @return void
     */
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
