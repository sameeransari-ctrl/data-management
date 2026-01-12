<?php

namespace App\Exports\Admin;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportStaffs implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $staffRepository;
    /**
     * Method __construct
     *
     * @param array $params [explicite description]
     * @param $staffRepository $staffRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $staffRepository)
    {
        $this->params = $params;
        $this->staffRepository = $staffRepository;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $staffs = $this->staffRepository->getStaff($this->params);
        $exports = array();
        foreach ($staffs as $key => $item) {
            $data['id'] = $key + 1;
            $data['name'] = ($item->name) ? ucfirst($item->name) : "N/A";
            $data['email'] = ($item->email) ? $item->email : "N/A";
            $data['phone_number'] = ($item->phone_number) ? '+'.$item->phone_code.' '.$item->phone_number : "N/A";
            $data['user_type'] = count($item->roles->pluck('name')) > 0 ? $item->roles->pluck('name')[0]: "N/A";
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
            'Mobile Number',
            'Role',
            'Status',
        ];
    }
}
