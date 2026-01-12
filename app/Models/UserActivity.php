<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    public $fillable = ['activity_time', 'user_id'];

    /**
     * Get the country associated with the state.
     *
     * @return void
     */
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
