<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Data;

class CompanyWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'website',
        'normalized_key',
    ];

    public function datas()
    {
        return $this->hasMany(Data::class, 'company_website_id');
    }
}
