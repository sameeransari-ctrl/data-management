<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ExportUsers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{UserRequest};
use App\Http\Resources\Collections\CityResource;
use App\Http\Resources\{ScannedProductResource, UserResource};
use Illuminate\Http\Request;
use App\Models\{User};
use App\Repositories\{CityRepository, CountryRepository, UserRepository};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    protected $userRepository;
    protected $countryRepository;
    protected $cityRepository;

    /**
     * Method __construct
     *
     * @param UserRepository    $userRepository    [explicite description]
     * @param CountryRepository $countryRepository [explicite description]
     * @param CityRepository    $cityRepository    [explicite description]
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        CountryRepository $countryRepository,
        CityRepository $cityRepository
    ) {
        $this->userRepository = $userRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
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
        $data = $request->all();
        $userTypeList = User::$userTypes;
        $statusList = User::$statusList;
        if ($request->ajax()) {
            return UserResource::collection(
                $this->userRepository->getUserList($data)
            );
        }
        return view('admin.user.index', compact('userTypeList', 'statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = null;
        $userTypeList = User::$userTypes;
        $countries = $this->countryRepository->getAll();
        return view('admin.user._addEdit', compact('user', 'countries', 'userTypeList'));
    }


    /**
     * Method store
     *
     * @param UserRequest $request [explicite description]
     *
     * @return void
     */
    public function store(UserRequest $request)
    {
        $params = $request->except(['_token', '_method']);
        $params['status'] = User::STATUS_ACTIVE;
        $params['email_verified_at'] = Carbon::now();
        $params['phone_number_verified_at'] = Carbon::now();
        $result = $this->userRepository->createUser($params, false);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.user')
                    ]
                )
            );
        }
    }

    /**
     * Method show
     *
     * @param Request $request [explicite description]
     * @param $id      $id [explicite description]
     *
     * @return void
     */
    public function show(Request $request, $id)
    {
        $data = $request->all();
        $user = $this->userRepository->getUserDetail($id);
        if (empty($user)) {
            return redirect()->route('admin.user.index');
        }

        if ($request->ajax()) {
            return ScannedProductResource::collection(
                $this->userRepository->getUserProductDetail($data, $id)
            );
        }
        return view('admin.user.user-details', compact('user'));
    }

    /**
     * Method edit
     *
     * @param int \App\Http\Controllers\Admin\ $id $id — [explicite description]
     *
     * @return void
     */
    public function edit($id)
    {
        $userTypeList = User::$userTypes;
        $countries = $this->countryRepository->getAll();
        $user = $this->userRepository->getUser($id);
        $cities = $this->cityRepository->cityList(['country_id' => $user->country_id]);

        return view('admin.user._addEdit', compact('user', 'countries', 'userTypeList', 'cities'));
    }


    /**
     * Method update
     *
     * @param UserRequest $request [explicite description]
     * @param int         $id      $id [explicite description]
     *
     * @return void
     */
    public function update(UserRequest $request, $id)
    {
        $params = $request->except(['_token', '_method']);
        $result = $this->userRepository->updateUser($params, $id);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.user')
                    ]
                )
            );
        }
    }


    /**
     * Method destroy
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }



    /**
     * Method changeStatus
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function changeStatus(Request $request)
    {
        $params = $request->all();
        $user = $this->userRepository->changeStatus($params, $params['id']);
        return $this->successResponse(trans('message.success.'.$params['status']));
    }

    /**
     * Method getCityByCountryId
     *
     * @param Request $request [explicite description]
     * @param int     $id      [explicite description]
     *
     * @return void
     */
    public function getCityByCountryId(Request $request, int $id)
    {
        $cityList = $this->cityRepository->cityList(['country_id' => $id]);
        return CityResource::collection($cityList);
    }

    /**
     * Method exportCsv
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function exportCsv(Request $request)
    {
        $params = $request->all();
        $exportUser = new ExportUsers($params, $this->userRepository);
        $fileName = 'users' . date('YmdHis') . '.csv';
        return Excel::download(
            $exportUser,
            $fileName
        );
    }
}
