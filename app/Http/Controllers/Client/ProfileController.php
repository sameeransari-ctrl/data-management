<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\{ChangePasswordRequest, UpdateProfileRequest};
use App\Http\Resources\Collections\CityResource;
use App\Repositories\{CityRepository, CountryRepository, UserCardRepository, UserRepository};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $userRepository;
    protected $countryRepository;
    protected $cityRepository;
    protected $userCardRepository;
    
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        CountryRepository $countryRepository,
        CityRepository $cityRepository,
        UserCardRepository $userCardRepository
    ) {
        $this->userRepository = $userRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->userCardRepository = $userCardRepository;
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        $clientData = $this->userRepository->getUser(Auth::user()->id);
        $countries = $this->countryRepository->getAll();
        $cities = $this->cityRepository->cityList(['country_id' => $clientData->country_id]);
        $userCard = $this->userCardRepository->getUserCard(Auth::user()->id);
        if (!empty($clientData->date_of_birth)) {
            $clientData->date_of_birth = getConvertedDate($clientData->date_of_birth, 'd M, Y');
        }
        return view('client.profile.index', compact('clientData', 'countries', 'cities', 'userCard'));
    }

    /**
     * Method changePassword
     *
     * @param ChangePasswordRequest $request [explicite description]
     *
     * @return void
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $this->userRepository->updatePassword($request->all());
        return $this->successResponse(__('message.success.password-change'));

    }

    /**
     * Method update
     *
     * @param UpdateProfileRequest $request [explicite description]
     *
     * @return void
     */
    public function update(UpdateProfileRequest $request)
    {
        $params = $request->except(['_token', '_method']);
        $this->userRepository->updateUser($params, getLoggedInUserDetail()->id);
        return $this->successResponse(__('message.success.profile_updated'));
    }

    /**
     * Method changeUserPassword
     *
     * @return void
     */
    public function changeUserPassword()
    {
        $userData = $this->userRepository->getUser(Auth::user()->id);
        $fromTimeZone = config('app.timezone');
        return view('client.profile.change-password', compact('userData', 'fromTimeZone'));

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
}
