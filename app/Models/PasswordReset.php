<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    public $timestamps = false;

    /**
     * Method boot
     *
     * @return View|\Illuminate\Contracts\View\Factory
     */
    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                $model->created_at = $model->freshTimestamp();
            }
        );
    }

    protected $fillable = [
        'email',
        'token',

    ];
}
