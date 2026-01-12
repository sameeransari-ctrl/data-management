<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RatingReviewRepository;
use App\Repositories\ProductScannerRepository;
use App\Repositories\FieldSefetyNoticeRepository;

class DashboardController extends Controller
{
    protected $productRepository;
    protected $userRepository;
    protected $ratingReviewRepository;
    protected $productScannerRepository;
    protected $fieldSafetyNotificationRepository;
    protected $countryRepository;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        RatingReviewRepository $ratingReviewRepository,
        UserRepository $userRepository,
        ProductScannerRepository $productScannerRepository,
        FieldSefetyNoticeRepository $fieldSafetyNotificationRepository,
        CountryRepository $countryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->ratingReviewRepository = $ratingReviewRepository;
        $this->userRepository = $userRepository;
        $this->productScannerRepository = $productScannerRepository;
        $this->fieldSafetyNotificationRepository = $fieldSafetyNotificationRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Method index
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->id;
        $uploadedProducts = $this->productRepository->getProductCount($userId);
        $totalFsn = $this->fieldSafetyNotificationRepository->getTotalFsnCount($userId);
        $totalReviews = $this->ratingReviewRepository->getTotalReviewCount($userId);
        $scannedProducts = $this->productScannerRepository->getClientScannedProductsCount($userId);
        $scannedUsers = $this->productScannerRepository->getTotalScannedUsers($userId);

        return view(
            'client.dashboard.index',  compact(
                [
                    'totalFsn',
                    'totalReviews',
                    'uploadedProducts',
                    'scannedProducts',
                    'scannedUsers',
                ]
            )
        );
    }

    /**
     * Method mapData
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function mapData(Request $request)
    {
        $countryUsers = $this->countryRepository->getCountryWiseScannedProductsCount();
        return response()->json($countryUsers);
    }
}
