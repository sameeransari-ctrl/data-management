<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $fillable = ['name', 'state_id', 'name', 'status'];

    /**
     * Get the state.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function state()
    {
        return $this->hasOne(state::class, 'id', 'state_id');
    }

    /**
     * Method users
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
