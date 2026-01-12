<?php

namespace App\Exports\Admin;

use App\Models\User;
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
        $users = $this->userRepository->getUserList($this->params);
        $exports = array();
        foreach ($users as $key => $item) {
            $data['id'] = $key + 1;
            $data['name'] = ($item->name) ? $item->name : "N/A";
            $data['email'] = ($item->email) ? $item->email : "N/A";
            $data['created_at'] = ($item->created_at) ? convertDateToTz($item->created_at, 'd M Y, h:i A') : "N/A";
            $data['address'] = ($item->address) ? $item->address : "N/A";
            $data['country'] = ($item->country) ? $item->country->name : "N/A";
            $data['city'] = ($item->city) ? $item->city->name : "N/A";
            $data['user_type'] = ($item->user_type) ? ucfirst($item->user_type) : "N/A";
            $data['status'] = ($item->status) ? ucfirst($item->status) : "N/A";
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
            'Registration Date',
            'Address',
            'Country',
            'City',
            'User Type',
            'Status',
        ];
    }
}
