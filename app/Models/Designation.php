<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Data;

class Designation extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    public function datas() {
        return $this->belongsToMany(Data::class, 'data_designation', 'designation_id', 'data_id')->withPivot('phrase');
    }
}
