<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'file_url',
    ];


    /**
     * Method products
     *
     * @return void
     */
    public function products()
    {
        return $this->belongsTo(Product::class);
    }

}
