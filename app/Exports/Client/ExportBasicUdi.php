<?php

namespace App\Exports\Client;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBasicUdi implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $basicUdidRepository;

    /**
     * Method __construct
     *
     * @param array $params              [explicite description]
     * @param $basicUdidRepository $basicUdidRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $basicUdidRepository)
    {
        $this->params = $params;
        $this->basicUdidRepository = $basicUdidRepository;
    }

    /**
     * Method collection
     *
     * @return void
     */
    public function collection()
    {
        $basicudi = $this->basicUdidRepository->getBasicUdiList($this->params);
        $exports = array();
        foreach ($basicudi as $key => $item) {
            $data['id'] = $key + 1;
            $data['name'] = ($item->name) ? $item->name : "N/A";
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
            'Basic UDI No.',
        ];
    }
}
