<?php

namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Models\Product;
use App\Models\ProductScanner;
use App\Models\User;
use App\Notifications\ScanProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * ProductScannerRepository
 */
class ProductScannerRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param ProductScanner $model
     *
     * @return void
     */
    public function __construct(ProductScanner $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method deleteScannedProduct
     *
     * @param int $productId [explicite description]
     * @param int $userId    [explicite description]
     *
     * @return boolean
     */
    public function deleteScannedProduct($productId, $userId)
    {
        $checkProduct = $this->firstWhere(
            [
                'product_id' => $productId,
                'user_id' => $userId
            ]
        );
        if (!empty($checkProduct)) {
            return $this->deleteWhere($checkProduct->id);
        }
        return false;
    }

    /**
     * Method storeScanProduct
     *
     * @param array $data [explicite description]
     *
     * @return Product
     */
    public function storeScanProduct(array $data)
    {
        if (empty($data['udi_number'])) {
            throw new CustomException(trans('message.error.exception.product_not_found'), 422);
        }
        $udiNumber = removeBracketsFromUdiNumber($data['udi_number']);
        $checkProduct = Product::where('plain_udi_number', $udiNumber)->first();
        if (empty($checkProduct)) {
            throw new CustomException(trans('message.error.exception.product_not_found'), 422);
        }

        $isExist = $this->firstWhere(
            [
                'user_id' => $data['user_id'],
                'product_id' => $checkProduct->id
            ]
        );

        if (!empty($isExist)) {
            throw new CustomException(
                trans('message.error.product.product_already_scanned'), 422
            );
        }
        $data['product_id'] = $checkProduct->id;
        $data['country_id'] = getCountryIdByName($data['country_name']);
        unset($data['country_name']);
        $this->create($data);

        // start sent notification to client about scanned.
        $userData = User::where('id', $data['user_id'])->first();
        $checkProduct->client->notify(new ScanProduct($checkProduct, $userData));
        // end sent notification to client about scanned.

        return $checkProduct;
    }

    /**
     * Method getScannedProductsCount
     *
     * @param $userId $userId [explicite description]
     *
     * @return void
     */
    public function getScannedProductsCount($userId)
    {
        return $this->model->where('user_id', $userId)->count();
    }

    /**
     * Method getTotalScannedProductsCount
     *
     * @return void
     */
    public function getTotalScannedProductsCount()
    {
        return $this->model->get();
    }

    /**
     * Method getScannedReport
     *
     * @return void
     */
    public function getScannedReport()
    {
        $data = [];
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $data['today'] = $this->model->whereDate('created_at', $currentDate)->count();
        $data['month'] = $this->model->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->count();
        $data['weekly'] = $this->model->whereDate('created_at', '>=', $startOfWeek)
            ->whereDate('created_at', '<=', $endOfWeek)->count();
        return $data;
    }

    /**
     * Method getClientScannedProductsCount
     *
     * @param $clientId $clientId [explicite description]
     *
     * @return void
     */
    public function getClientScannedProductsCount($clientId)
    {
        return $this->model
            ->leftJoin('products', 'products.id', 'product_scanners.product_id')
            ->where('products.client_id', $clientId)
            ->select('product_scanners.product_id')
            ->groupBy('product_scanners.product_id')
            ->distinct()
            ->get()
            ->count();
    }

    /**
     * Method getTotalScannedUsers
     *
     * @param $clientId $clientId [explicite description]
     *
     * @return void
     */
    public function getTotalScannedUsers($clientId)
    {
        return $this->model
            ->leftJoin('products', 'products.id', 'product_scanners.product_id')
            ->where('products.client_id', $clientId)
            ->select('product_scanners.user_id')
            ->groupBy('product_scanners.user_id')
            ->distinct()
            ->get()
            ->count();
    }


    /**
     * Method getScannedProduct
     *
     * @return void
     */
    public function getScannedProduct()
    {
        return $this->model->with('product')
            ->leftJoin('products', 'products.id', 'product_scanners.product_id')
            ->select(DB::raw("count(*) as total_scanned"), 'product_scanners.product_id')
            ->groupBy('product_scanners.product_id')
            ->orderBy('total_scanned', 'DESC')
            ->limit(10)
            ->get();
    }
}
