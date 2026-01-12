<?php

namespace App\Exports\Client;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportUsers implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $userRepository;

    /**
     * Method __construct
     *
     * @param array $params         [explicite description]
     * @param $userRepository $userRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $userRepository)
    {
        $this->params = $params;
        $this->userRepository = $userRepository;
    }

    /**
     * Method collection
     *
     * @return void
     */
    public function collection()
    {
        $users = $this->userRepository->getMyScannedProductUserList($this->params);
        $exports = array();
        foreach ($users as $key => $item) {
            $data['id'] = $key + 1;
            $data['name'] = ($item->name) ? $item->name : "N/A";
            $data['email'] = ($item->email) ? $item->email : "N/A";
            $data['phone_number'] = ($item->phone_number) ? '+'.$item->phone_code.' '.$item->phone_number : "N/A";
            $data['address'] = ($item->address) ? $item->address : "N/A";
            $data['country'] = ($item->country) ? $item->country->name : "N/A";
            $data['city'] = ($item->city) ? $item->city->name : "N/A";
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
            'S.No.',
            'Name',
            'Email',
            'Mobile Number',
            'Address',
            'Country',
            'City',
        ];
    }
}
