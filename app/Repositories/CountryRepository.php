<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\ProductScanner;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    protected $model;


    /**
     * Method __construct
     *
     * @param Country $model [explicite description]
     *
     * @return void
     */
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    /**
     * Get all record
     *
     * @return Collection
     */
    public function getAll()
    {
        //return $this->model->get();
        $cacheKey = Config::get('cache.cacheKey');

        return Cache::remember(
            $cacheKey, 60, function () {
                return $this->model->where('status', 'active')->get();
            }
        );
    }

    /**
     * Method createCountry
     *
     * @param array $data [explicite description]
     * @param bool  $doVerify [explicite description]
     *
     * @return Country
     */
    public function createCountry(array $data): Country
    {
        return $this->model->create($data);
    }

    /**
     * Method getCountry
     *
     * @param int $id [explicite description]
     *
     * @return Country
     */
    public function getCountry(int $id): ?Country
    {
        return $this->model->find($id);
    }

    /**
     * Method changeStatus
     *
     * @param array $data [explicite description]
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function changeStatus(array $data, int $id)
    {
        $country = $this->getCountry($id);
        if (! empty($country)) {
            return $this->model->where('id', $id)->update(
                [
                    'status' => $data['status'],
                ]
            );
        }
    }

    /**
     * Method getCountryWiseUsersCount
     *
     * @return void
     */
    public function getCountryWiseUsersCount()
    {
        $data = [];
        $userCounts = User::selectRaw('country_id, user_type, COUNT(*) as user_count')
            ->whereIn('user_type', [User::TYPE_MEDICAL, User::TYPE_BASIC])
            ->where(
                function ($q) {
                    $q->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->with('country:id,name')
            ->groupBy('country_id', 'user_type')
            ->get();
        $country = [];
        if (!empty($userCounts)) {
            foreach ($userCounts as $row) {
                if ($row->user_type == User::TYPE_BASIC) {
                    $country[$row->country?->name][User::TYPE_BASIC] = $row->user_count;
                }
                if ($row->user_type == User::TYPE_MEDICAL) {
                    $country[$row->country?->name][User::TYPE_MEDICAL] = $row->user_count;
                }
            }
        }
        $data[] = ["Country", "Basic Users", "Medical Users"];
        if (!empty($country)) {
            foreach ($country as $key=>$val) {
                $basics = (isset($val[User::TYPE_BASIC])) ? $val[User::TYPE_BASIC] : 0;
                $medicals = (isset($val[User::TYPE_MEDICAL])) ? $val[User::TYPE_MEDICAL] : 0;
                $array = [
                    $key,
                    $basics,
                    $medicals,
                ];
                $data[] = $array;
            }
        }
        if (count($data) == 1) {
            $data[] = ["", 0, 0];
        }
        return $data;
    }

    /**
     * Method getCountryWiseScannedProductsCount
     *
     * @return void
     */
    public function getCountryWiseScannedProductsCount()
    {
        $productsScanned = ProductScanner::join('products', 'product_scanners.product_id', '=', 'products.id')
            ->join('users', 'products.client_id', '=', 'users.id')
            ->selectRaw('COUNT(DISTINCT CONCAT(product_scanners.product_id, "_", product_scanners.country_id)) as total_scans, product_scanners.country_id')
            ->with('country:id,name')
            ->where('products.client_id', auth()->user()->id)
            ->groupBy('product_scanners.country_id')
            ->get();

           $data[] = ["Country", "Products"];

        if (!empty($productsScanned)) {
            foreach ($productsScanned as $val) {
                $totalCounts = (isset($val->total_scans)) ? $val->total_scans : 0;
                $array = [
                    $val->country?->name,
                    $totalCounts,
                ];
                $data[] = $array;
            }
        }
        if (count($data) == 1) {
            $data[] = ["", 0];
        }
        return $data;
    }
}
