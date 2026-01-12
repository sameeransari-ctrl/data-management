<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RegisterRequest;
use App\Http\Resources\Collections\CityResource;
use App\Models\User;
use App\Notifications\RegisterClient;
use App\Repositories\CityRepository;
use App\Repositories\UserRepository;
use App\Repositories\ClientRoleRepository;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $countryRepository;
    protected $clientRoleRepository;
    protected $cityRepository;
    protected $userRepository;

    /**
     * Method __construct
     *
     * @param CountryRepository    $countryRepository    [explicite description]
     * @param ClientRoleRepository $clientRoleRepository [explicite description]
     * @param CityRepository       $cityRepository       [explicite description]
     * @param UserRepository       $userRepository       [explicite description]
     *
     * @return void
     */
    public function __construct(CountryRepository $countryRepository, ClientRoleRepository $clientRoleRepository, CityRepository $cityRepository, UserRepository $userRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->clientRoleRepository = $clientRoleRepository;
        $this->cityRepository = $cityRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        $countries = $this->countryRepository->getAll();
        $clientRoles = $this->clientRoleRepository->getAll();
        return view('client.auth.register', compact('countries', 'clientRoles'));
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
     * Method register
     *
     * @param RegisterRequest $request [explicite description]
     *
     * @return void
     */
    public function register(RegisterRequest $request)
    {
        $params = $request->except(['_token', '_method']);
        $params['user_type'] = User::TYPE_CLIENT;
        $params['status'] = User::STATUS_ACTIVE;
        $result = $this->userRepository->registerClient($params, false);

        if (!empty($result)) {
            $user = $this->userRepository->getUserByField(['email' => $params['email']]);
            $this->userRepository->sendOtp($user, '');
            $redirectionUrl = route('client.login.otp.show', encrypt($user->email));
            $userData = User::onlyAdmin()->first();
            $userData->notify(new RegisterClient($user));
            $data = [
                'success' => true,
                'message' => trans('auth.registration'),
                'redirectionUrl' => $redirectionUrl,
            ];
            return $this->responseSend($data);
        }
    }

    /**
     * Method responseSend
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    protected function responseSend(array $data)
    {
        return response()->json($data);
    }
}
