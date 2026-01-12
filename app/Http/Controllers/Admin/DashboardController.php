<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RatingReviewRepository;
use App\Repositories\ProductScannerRepository;
use App\Repositories\CountryRepository;
use App\Repositories\RoleRepository;
use App\Repositories\DataRepository;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DashboardController extends Controller
{

    protected $productRepository;
    protected $userRepository;
    protected $ratingReviewRepository;
    protected $productScannerRepository;
    protected $countryRepository;
    protected $staffRepository;

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
        CountryRepository $countryRepository,
        StaffRepository $staffRepository,
        RoleRepository $roleRepository,
        DataRepository $dataRepository
    ) {
        $this->productRepository = $productRepository;
        $this->ratingReviewRepository = $ratingReviewRepository;
        $this->userRepository = $userRepository;
        $this->productScannerRepository = $productScannerRepository;
        $this->countryRepository = $countryRepository;
        $this->staffRepository = $staffRepository;
        $this->roleRepository = $roleRepository;
        $this->dataRepository = $dataRepository;
    }

    /**
     * Method index
     *
     * @param $request Request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if (!(app('auth')->user()->can('admin.dashboard.index'))) {
            return redirect()->route('admin.profile.index');
        }

        // $activeUsers = $this->userRepository->getTotalActiveUsers();
        // $inactiveUsers = $this->userRepository->getTotalInactiveUsers();
        // $activeClients = $this->userRepository->getTotalActiveClients();
        // $inactiveClients = $this->userRepository->getTotalInactiveClients();
        // $uploadedProducts = $this->productRepository->getTotalProducts();
        // $scannedProducts = $this->productScannerRepository->getTotalScannedProductsCount();
        $scannedStatistics = $this->productScannerRepository->getScannedReport();
        $activeStaffs = $this->staffRepository->getTotalActiveStaffs();
        $inactiveStaffs = $this->staffRepository->getTotalInactiveStaffs();
        $activeRoles = $this->roleRepository->getTotalActiveRoles();
        $inactiveRoles = $this->roleRepository->getTotalInactiveRoles();
        // $activeDatas = $this->dataRepository->getTotalActiveDatas();
        // $inactiveDatas = $this->dataRepository->getTotalInactiveDatas();
        
        return view(
            'admin.dashboard.index', compact(
                [
                    // 'activeUsers',
                    // 'inactiveUsers',
                    // 'uploadedProducts',
                    // 'scannedProducts',
                    // 'activeClients',
                    // 'inactiveClients',
                    'scannedStatistics',
                    'activeStaffs',
                    'inactiveStaffs',
                    'activeRoles',
                    'inactiveRoles',
                    // 'activeDatas',
                    // 'inactiveDatas',
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
        $countryUsers = $this->countryRepository->getCountryWiseUsersCount();
        return response()->json($countryUsers);
    }
}
