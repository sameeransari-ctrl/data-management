<?php

namespace App\Exports\Admin;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportDatas implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $dataRepository;
    /**
     * Method __construct
     *
     * @param array $params [explicite description]
     * @param $dataRepository $dataRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $dataRepository)
    {
        $this->params = $params;
        $this->dataRepository = $dataRepository;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $datas = $this->dataRepository->getDataList($this->params);
        $exports = array();
        foreach ($datas as $key => $item) {
            // $data['id'] = $key + 1;
            $data['company_name'] = ($item->company_name) ? ucfirst($item->company_name) : "N/A";
            // $data['title'] = ($item->title) ? $item->title : "N/A";
            $data['title'] = $item->designations->pluck('name')->filter()->implode(', ') ?: 'N/A';
            // $data['company_website'] = ($item->company_website) ? $item->company_website : "N/A";
            $data['company_website'] = optional($item->companyWebsite)->website ?? 'N/A';
            $data['company_industries'] = ($item->company_industries) ? $item->company_industries : "N/A";
            $data['num_of_employees'] = ($item->num_of_employees) ? $item->num_of_employees : "N/A";
            $data['company_size'] = ($item->company_size) ? $item->company_size : "N/A";
            $data['company_address'] = ($item->company_address) ? $item->company_address : "N/A";
            $data['company_revenue_range'] = ($item->company_revenue_range) ? $item->company_revenue_range : "N/A";
            $data['company_linkedin_url'] = ($item->company_linkedin_url) ? $item->company_linkedin_url : "N/A";
            $data['company_phone_number'] = ($item->company_phone_number) ? $item->company_phone_number : "N/A";
            $data['first_name'] = ($item->first_name) ? $item->first_name : "N/A";
            $data['last_name'] = ($item->last_name) ? $item->last_name : "N/A";
            $data['email'] = ($item->email) ? $item->email : "N/A";
            $data['person_linkedin_url'] = ($item->person_linkedin_url) ? $item->person_linkedin_url : "N/A";
            $data['source_url'] = ($item->source_url) ? $item->source_url : "N/A";
            $data['person_location'] = ($item->person_location) ? $item->person_location : "N/A";
            $data['created_at'] = ($item->created_at) ?  convertDateToTz($item->created_at, 'd M Y, h:i A') : "N/A";
            $exports[] = $data;
        }

        return collect($exports);
    }

    /**
     * Method headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            // 'S.No.',
            'Company Name',
            'Title',
            'Company Website',
            'Company Industries',
            'No. of Employees',
            'Company Size',
            'Company Address',
            'Company Revenue Range',
            'Company Linkedin URL',
            'Company Phone No.',
            'First Name',
            'Last Name',
            'Email',
            'Person Linkedin URL',
            'Source URL',
            'Person Location',
            'Created Date & Time',
        ];
    }
}
