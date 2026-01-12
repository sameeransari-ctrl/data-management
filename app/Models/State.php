<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public $fillable = ['name', 'country_id', 'status'];

    /**
     * Get the country associated with the state.
     *
     * @return void
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
