<?php

namespace App\Exports\Admin;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportClients implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $clientRepository;


    /**
     * Method __construct
     *
     * @param array $params           [explicite description]
     * @param $clientRepository $clientRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $clientRepository)
    {
        $this->params = $params;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = $this->clientRepository->getClientList($this->params);
        $exports = array();
        foreach ($users as $key => $item) {
            $data['id'] = $key + 1;
            $data['name'] = ($item->name) ? ucfirst($item->name) : "N/A";
            $data['email'] = ($item->email) ? $item->email : "N/A";
            $data['phone_number'] = ($item->phone_number) ? '+'.$item->phone_code.' '.$item->phone_number : "N/A";
            $data['user_type'] = ($item->clientRole->name) ? $item->clientRole->name : "N/A";
            $data['product_count'] = count($item->product);
            $data['created_at'] = ($item->created_at) ?  convertDateToTz($item->created_at, 'd M Y, h:i A') : "N/A";
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
            'Uploded Product Count',
            'Date',
            'Status',
        ];
    }
}
