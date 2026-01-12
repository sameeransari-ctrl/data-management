<?php

namespace App\Repositories;

use Exception;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\User;
use App\Notifications\CreateProduct;

class ProductRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param Product $model [explicite description]
     *
     * @return void
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getProductList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getProductList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'product_name' => 'product_name'
        ];

        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $classId = $data['classId'] ?? '';
        $verificationBy = $data['verificationBy'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }
        if (isset($export) && $export == 'export') {
            $paginate = false;
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);

        $faq = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where(
                        function ($q) use ($search) {
                            $q->where('product_name', 'like', '%'.$search.'%')
                                ->orWhere('udi_number', 'like', '%'.$search.'%');
                        }
                    );
                }
            )
            ->when(
                $classId,
                function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                }
            )
            ->when(
                $verificationBy,
                function ($q) use ($verificationBy) {
                    $q->where('verification_by', $verificationBy);
                }
            )
            ->when(
                (isset($data['current_client']) && !empty($data['current_client'])),
                function ($q) {
                    $q->where('client_id', '=', auth()->user()->id);
                }
            );

        if (! $paginate) {
            $result = $faq->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $faq->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method getScannedProductList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getScannedProductList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'product_name' => 'product_name',
            'status' => 'status',
            'scanned_at' => 'scanned_at'
        ];

        $search = $data['search'] ?? '';
        $scanDate = $data['scan_date'] ?? '';
        $verification = $data['verification'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['scanned_at'];

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $userId = $data['userId'];

        $responsData = $this->model
            ->select('products.*', 'product_scanners.user_id', 'product_scanners.created_at as scanned_at')
            ->leftJoin('product_scanners', 'product_scanners.product_id', 'products.id');

        $responsData->when(
            $search,
            function ($q) use ($search) {
                $q->where(
                    function ($query) use ($search) {
                        $query->where('products.product_name', 'like', '%'.$search.'%')
                            ->orWhere('products.udi_number', 'like', '%'.$search.'%');
                    }
                );
            }
        );

        $responsData->when(
            $scanDate,
            function ($q) use ($scanDate) {
                $q->whereDate('product_scanners.created_at', $scanDate);
            }
        );

        $responsData->when(
            $verification,
            function ($q) use ($verification) {
                $q->where('products.verification_by', $verification);
            }
        );

        $responsData->where('product_scanners.user_id', $userId)
            ->whereNull('product_scanners.deleted_at');

        if (! $paginate) {
            $results = $responsData->orderBy($sort, $sortDirection)->get();
        } else {
            $results = $responsData->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $results;
    }

    /**
     * Method getAllProductClass
     *
     * @return void
     */
    public function getAllProductClass()
    {
        $productClassList = ProductClass::where(['status' => ProductClass::STATUS_ACTIVE])->get();

        return $productClassList;
    }


    /**
     * Method saveProduct
     *
     * @param array $data [explicite description]
     *
     * @return Product
     */
    public function saveProduct(array $data)
    {
        $data['client_id'] = ($data['client_id']=='other') ? null : $data['client_id'];
        $product = $this->create($data);
        // start sent notifications //
        if (auth()->user()->user_type == User::TYPE_CLIENT) {
            $adminData = User::onlyAdmin()->first();
            $adminData->notify(new CreateProduct($product));
        } else if (auth()->user()->user_type == User::TYPE_STAFF) {
            $clientData = User::where('id', $data['client_id'])->first();
            $adminData = User::onlyAdmin()->first();
            $adminData->notify(new CreateProduct($product));
            $clientData->notify(new CreateProduct($product));
        } else {
            $clientData = User::where('id', $data['client_id'])->first();
            $clientData->notify(new CreateProduct($product));
        }
        // end sent notifications //
        return $product;
    }

    /**
     * Method saveProduct
     *
     * @param array $data [explicite description]
     *
     * @return object
     */
    public function updateProduct(array $data)
    {
        $product = [];
        if (!empty($data['id'])) {
            $data['client_id'] = ($data['client_id']=='other') ? null : $data['client_id'];
            $product = $this->update($data, $data['id']);
        }
        return $product;
    }

    /**
     * Method getProductCount
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getProductCount($id)
    {
        return $this->model->where('client_id', $id)->get()->count();
    }


    /**
     * Method getProductClass
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getProductClass($id)
    {
        return ProductClass::where(['status' => ProductClass::STATUS_ACTIVE])->where('id', $id)->first();
    }

    /**
     * Method getWhereProductList
     *
     * @param array $where [explicite description]
     *
     * @return void
     */
    public function getWhereProductList(array $where)
    {
        return $this->model->where($where)->get();
    }

    /**
     * Method getProduct
     *
     * @param int $id [explicite description]
     *
     * @return Product
     */
    public function getProduct(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * Method getTotalProducts
     *
     * @return void
     */
    public function getTotalProducts()
    {
        return $this->model->get();
    }
    
    /**
     * Method getRecentProductList
     *
     * @return void
     */
    public function getRecentProductList()
    {
        return $this->model->orderBy('id', 'DESC')->take(10)->get();
    }
    
}
