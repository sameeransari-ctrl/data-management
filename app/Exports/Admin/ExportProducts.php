<?php

namespace App\Exports\Admin;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportProducts implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $params;
    public $productRepository;

    /**
     * Method __construct
     *
     * @param array $params         [explicite description]
     * @param $productRepository $productRepository [explicite description]
     *
     * @return void
     */
    public function __construct(array $params, $productRepository)
    {
        $this->params = $params;
        $this->productRepository = $productRepository;
    }

    /**
     * Method collection
     *
     * @return void
     */
    public function collection()
    {
        $products = $this->productRepository->getProductList($this->params);
        $exports = array();
        foreach ($products as $key => $item) {
            $data['id'] = $key + 1;
            $data['udi_number'] = ($item->udi_number) ? $item->udi_number : "N/A";
            $data['product_name'] = ($item->product_name) ? ucfirst($item->product_name) : "N/A";
            $data['client_name'] = ($item->client) ? $item->client->name : "N/A";
            $data['class'] = ($item->productClass) ? $item->productClass->name : "N/A";
            $data['verification_by'] = Product::$productVerificationTypes[$item->verification_by];
            $data['scan_count'] = count($item->productScanner) > 0 ? count($item->productScanner) : '0';
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
            'UDI No.',
            'Product Name',
            'Client Name',
            'Class',
            'Verification',
            'Total Scan Count',
        ];
    }
}
