<?php

namespace App\Exports\Admin;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportFsn implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $fieldSefetyNoticeRepository;


    /**
     * Method __construct
     *
     * @param array $params           [explicite description]
     * @param $fieldSefetyNoticeRepository $fieldSefetyNoticeRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $fieldSefetyNoticeRepository)
    {
        $this->params = $params;
        $this->fieldSefetyNoticeRepository = $fieldSefetyNoticeRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $fsnData = $this->fieldSefetyNoticeRepository->getList($this->params);
        $exports = array();
        foreach ($fsnData as $key => $item) {
            $data['id'] = $key + 1;
            $data['title'] = ($item->title) ? $item->title : "N/A";
            $data['udi_number'] = ($item->product) ? $item->product->udi_number : "N/A";
            $data['created_at'] = ($item->created_at) ? convertDateToTz($item->created_at, 'd M Y, h:i A') : "N/A";
            $data['product_name'] = ($item->product) ? $item->product->product_name : "N/A";
            $data['attachment_type'] = ($item->attachment_type) ? $item->attachment_type : "N/A";
            $data['name'] = ($item->user) ? $item->user->name : "N/A";
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
            'Manufacture',
        ];
    }
}
