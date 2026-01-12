<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\{
    CountryResource,
    StateResource,
    CityResource
};
use App\Repositories\{
    StateRepository,
    CountryRepository,
    CityRepository,
    UserRepository
};
use App\Repositories\Interfaces\{
    StateRepositoryInterface,
    CountryRepositoryInterface,
};
use App\Models\{
    User,
};
use Exception;

class SettingController extends Controller
{

    protected $countryRepository;
    protected $stateRepository;
    protected $cityRepository;
    protected $userRepository;

    /**
     * SettingController constructor.
     *
     * @param CountryRepositoryInterface $countryRepository 
     * @param StateRepositoryInterface   $stateRepository 
     * @param CityRepository             $cityRepository 
     * @param UserRepository             $userRepository 
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository,
        StateRepositoryInterface $stateRepository,
        CityRepository $cityRepository,
        UserRepository $userRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get country list.
     *
     * @param Request $request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries(Request $request)
    {
        try {
            $countryList = $this->countryRepository->getAll();
            return CountryResource::collection($countryList);
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Get state list.
     *
     * @param Request $request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function states(Request $request)
    {
        try {
            $post = $request->only(['country_id']);
            $stateList = $this->stateRepository->stateList($post);
            
            return StateResource::collection($stateList);
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Get city list.
     *
     * @param Request $request 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {
        try {
            $post = $request->only(['country_id', 'state_id']);
            $cities = $this->cityRepository->cityList($post);
            return CityResource::collection($cities);
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
   
    /**
     * Method userRoles
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRoles()
    {
        try {
            $userTypes = User::$userTypes;
            return $this->successResponse(
                '',
                $userTypes
            );
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
    
    /**
     * Method updateUserSettings
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function updateUserSettings(Request $request)
    {
        $userId = auth()->user()->id;
        $notificationAlert = $request->notification_alert;
        $data = ['notification_alert' => $notificationAlert];
        $this->userRepository->update($data, $userId);
        return $this->successResponse(
            __('message.success.settings_updated')
        );
    }

}
