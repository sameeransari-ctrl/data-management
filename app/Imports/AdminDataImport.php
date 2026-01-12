<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Designation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class AdminDataImport implements ToModel,WithHeadingRow,WithBatchInserts,WithChunkReading,WithValidation,SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $_rows = 0;
    private $_totalRows = 0;
    private $_skipMsg = "";
    protected $datas;
    
    /**
     * Method prepareForValidation
     *
     * @param $data  $data [explicite description]
     * @param $index $index [explicite description]
     *
     * @return void
     */
    public function prepareForValidation($data, $index)
    {
        ++$this->_totalRows;
        $this->datas[$index] = $data;
        return $data;
    }

    public function model(array $row)
    {
        DB::transaction(function () use ($row) {

            $companiesData = [
                'email' => $row['email'],
                'company_name' => $row['company_name'],
                'company_website' => $row['company_website'],
                'company_industries' => $row['company_industry'],
                'num_of_employees' => $row['no_of_employees'],
                'company_size' => $row['company_size'],
                'company_revenue_range' => $row['company_revenue_range'],
                'company_linkedin_url' => $row['company_linkedin_url'],
                'company_phone_number' => $row['company_phone_number'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'title' => $row['title'],
                'person_linkedin_url' => $row['person_linkedin_url'],
                'source_url' => $row['source_url'],
                'person_location' => $row['person_location'],
            ];

            /** @var Data $data */
            $data = Data::firstOrCreate(
                ['email' => $row['email']],
                $companiesData
            );

            // 👇 Handle multiple titles
            // if (!empty($row['title'])) {

            //     // Split by comma
            //     $titles = explode(',', $row['title']);

            //     $designationIds = [];

            //     foreach ($titles as $title) {
            //         $designationIds[] = $this->_getTitle($title);
            //     }

            //     // Attach without removing old ones
            //     $data->designations()->syncWithoutDetaching($designationIds);
            // }
            // 👇 Handle multiple titles
            if (!empty($row['title'])) {

                // Split by comma, slash, &, pipe, or the word "and"
                $titles = preg_split('/\s*(,|\/|&|\||\band\b)\s*/i', $row['title']);

                $designationIds = [];

                foreach ($titles as $title) {
                    $title = trim($title);

                    if ($title !== '') {
                        $designationIds[] = $this->_getTitle($title);
                    }
                }

                // Attach without removing old ones
                $data->designations()->syncWithoutDetaching($designationIds);
            }
        });
    }


    // public function model(array $row)
    // {
    //     $companiesData = [
    //         'email' => $row['email'],
    //         'company_name' => $row['company_name'],
    //         'company_website' => $row['company_website'],
    //         'company_industries' => $row['company_industry'],
    //         'num_of_employees' => $row['no_of_employees'],
    //         'company_size' => $row['company_size'],
    //         'company_revenue_range' => $row['company_revenue_range'],
    //         'company_linkedin_url' => $row['company_linkedin_url'],
    //         'company_phone_number' => $row['company_phone_number'],
    //         'first_name' => $row['first_name'],
    //         'last_name' => $row['last_name'],
    //         'title' => $row['title'],
    //         'person_linkedin_url' => $row['person_linkedin_url'],
    //         'source_url' => $row['source_url'],
    //         'person_location' => $row['person_location'],
    //     ];

    //     Data::firstOrCreate(['email' => $row['email']], $companiesData);
    // }

    private function _getTitle(string $title): int
    {
        $title = trim($title);

        $designation = Designation::firstOrCreate(
            ['name' => $title]
        );

        return $designation->id;
    }


    public $requiredHeading = [
        "email",
    ];


    /**
     * Method rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.email' => ['required', 'unique:datas,email'],
        ];
    }

    


    /**
     * Method customValidationMessages
     *
     * @return void
     */
    public function customValidationMessages()
    {
        return [
            '*.email.required' => 'This field is required.',
        ];
    }

    /**
     * Method batchSize
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Method chunkSize
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    
}
