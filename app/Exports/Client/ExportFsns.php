<?php

namespace App\Exports\Client;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportFsns implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $fsnRepository;

    /**
     * Method __construct
     *
     * @param array $params        [explicite description]
     * @param $fsnRepository $fsnRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $fsnRepository)
    {
        $this->params = $params;
        $this->fsnRepository = $fsnRepository;
    }

    /**
     * Method collection
     *
     * @return void
     */
    public function collection()
    {
        $fsns = $this->fsnRepository->getList($this->params);
        $exports = array();
        foreach ($fsns as $key => $item) {
            $data['id'] = $key + 1;
            $data['title'] = ($item->title) ? $item->title : "N/A";
            $data['udi_number'] = ($item->product) ? $item->product->udi_number : "N/A";
            $data['created_at'] = ($item->created_at) ? convertDateToTz($item->created_at, 'd M Y, h:i A') : "N/A";
            $data['product_name'] = ($item->product) ? $item->product->product_name : "N/A";
            $data['attachment_type'] = ($item->attachment_type) ? $item->attachment_type : "N/A";
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
            'Title',
            'UDI No',
            'Date',
            'Poduct Name',
            'Attach Type',
        ];
    }
}
