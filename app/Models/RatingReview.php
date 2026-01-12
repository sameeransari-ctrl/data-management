<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatingReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'review_by',
        'product_id',
        'rating',
        'review',
        'status',
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
     * Method user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, "review_by", 'id');
    }
}
