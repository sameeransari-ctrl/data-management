<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Repositories\{UserRepository, ProductRepository, ProductScannerRepository, UserActivityRepository};
use Illuminate\Http\Request;
class ReportAnalyticsController extends Controller
{

    protected $userRepository;
    protected $productRepository;
    protected $productScannerRepository;
    protected $userActivityRepository;

    /**
     * Method __construct
     *
     * @param UserRepository           $userRepository           [explicite description]
     * @param ProductRepository        $productRepository        [explicite description]
     * @param ProductScannerRepository $productScannerRepository [explicite description]
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        ProductScannerRepository $productScannerRepository,
        UserActivityRepository $userActivityRepository
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->productScannerRepository = $productScannerRepository;
        $this->userActivityRepository = $userActivityRepository;
    }
       
    /**
     * Method index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $recentUserList = $this->userRepository->getRecentUserList();
        $recentActiveUserList = $this->userActivityRepository->getActiveUserList();
        $recentProductList = $this->productRepository->getRecentProductList();
        $productScannedList = $this->productScannerRepository->getScannedProduct();
        return view('admin.report.index', compact('recentUserList', 'recentActiveUserList', 'recentProductList', 'productScannedList'));
    }
}
