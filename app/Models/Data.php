<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Designation;
use App\Models\CompanyWebsite;

class Data extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'datas'; // <-- explicitly use the correct table

    protected $fillable = ['company_name', 'company_website_id', 'company_website', 'company_industries', 'num_of_employees', 'company_size', 'company_address', 'company_revenue_range', 'company_linkedin_url', 'company_phone_number', 'first_name', 'last_name', 'email', 'title', 'person_linkedin_url', 'source_url', 'person_location', 'status'];

    public function designations() {
        return $this->belongsToMany(Designation::class, 'data_designation', 'data_id', 'designation_id')->withPivot('phrase');
    }

    public function companyWebsite()
    {
        return $this->belongsTo(CompanyWebsite::class, 'company_website_id');
    }
}
