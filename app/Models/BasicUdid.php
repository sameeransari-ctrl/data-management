<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicUdid extends Model
{
    use HasFactory;

    public $fillable = ['name', 'added_by'];

    /**
     * Method client
     *
     * @return void
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
