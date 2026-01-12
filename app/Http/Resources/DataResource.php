<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class DataResource extends JsonResource
{
    /**
     * Method toArray
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public function toArray($request)
    {
        /**
         * Data
         *
         * @var Data $data
         */

        return [
            'id' => $this->id,
            'company_name' => ucwords($this->company_name),
            // 'company_website' => $this->company_website,
            'company_website' => $this->companyWebsite->website ?? null,
            'company_industries' => $this->company_industries,
            'num_of_employees' => $this->num_of_employees,
            'company_size' => $this->company_size,
            'company_address' => $this->company_address,
            'company_revenue_range' => $this->company_revenue_range,
            'company_linkedin_url' => $this->company_linkedin_url,
            'company_phone_number' => $this->company_phone_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'title' => $this->title,
            // 'title' => $this->designations->pluck('name')->implode(', '),
            'person_linkedin_url' => $this->person_linkedin_url,
            'source_url' => $this->source_url,
            'person_location' => $this->person_location,
            'updated_at' => getConvertedDate($this->updated_at, ''),
            // 'status' => !empty($this->status) ? 'active' : 'inactive',
            'status' => $this->status,
            // 'email' => $this->email,
        ];
    }
}
