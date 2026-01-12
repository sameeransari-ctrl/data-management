<?php

namespace App\Repositories;

use Exception;
use App\Models\ProductFile;

class ProductFileRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param ProductFile $model 
     * 
     * @return void 
     */
    public function __construct(ProductFile $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
   
    /**
     * Method saveProductFile
     *
     * @param array $data 
     * @param $product $product 
     *
     * @return object
     */
    public function saveProductFile(array $data, $product)
    {
        $productFiles = [];
        // Instantiate each asset object
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $productFiles[] = new ProductFile(
                    [
                        'file_url' => $data[$key]
                    ]
                );
            }
        }

        $product->productFiles()->delete();
        $product->productFiles()->saveMany($productFiles);
        //$product->productFiles()->sync(array_values($data));
    }
    
    /**
     * Method getAllProductFiles
     *
     * @param int $productId [explicite description]
     *
     * @return void
     */
    public function getAllProductFiles(int $productId)
    {
        $query = $this->model->where(['product_id' => $productId])->orderBy('id', 'ASC');
        $data = $query->get()->toArray();
        return $data;
    }
}
