<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DocCheckService;
use App\Notifications\WelcomeUser;
use App\Exceptions\CustomException;
use App\Notifications\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\SendOtpRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Repositories\UserDeviceRepository;
use App\Http\Requests\Api\VerifyOtpRequest;
use App\Http\Requests\Api\ResetPasswordRequest;



class AuthController extends Controller
{
    protected $userRepository;

    protected $userDeviceRepository;

    protected $doccheckService;

    /**
     * Method __construct
     *
     * @param UserRepository       $userRepository
     * @param UserDeviceRepository $userDeviceRepository
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        UserDeviceRepository $userDeviceRepository,
        DocCheckService $doccheckService
    ) {
        $this->userRepository = $userRepository;
        $this->userDeviceRepository = $userDeviceRepository;
        $this->doccheckService = $doccheckService;
    }

    /**
     * Method user registration
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $post = $request->all();
            $doVerify = getVerificationRequired($post['user_type']);
            $user = $this->userRepository->create($post);
            if ($doVerify) {
                $this->userRepository->sendOtp($user, '');
                return $this->successResponse(__('message.otp_send_success'));
            }
            return new UserResource($user);
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method verify otp
     *
     * @param VerifyOtpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throw Exception
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $post = $request->all();
            if ($post['type'] == User::VERIFY_TYPE_PROFILE) {
                return $this->profileVerifyOtp($request);
            }
            $user = $this->userRepository->verifyOtpApi($post);
            if (in_array($post['type'], [User::VERIFY_TYPE_REGISTER, User::VERIFY_TYPE_LOGIN, User::VERIFY_TYPE_OTP])) {
                $this->_updateDevice($user, $post);
                if ($post['type'] == User::VERIFY_TYPE_REGISTER) {
                    $userData = User::onlyAdmin()->first();
                    $userData->notify(new RegisterUser($user));
                }
                return new UserResource($user);
            }
            return $this->successResponse(__('message.otp_verify_successfully'));
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method Login
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throw Exception
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->_loginUser($request);
            $doVerify = getVerificationRequired($user->user_type);
            if ($doVerify) {
                return $this->successResponse(__('message.otp_send_success'));
            }
            return new UserResource($user);
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method send otp
     *
     * @param SendOtpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throw Exception
     */
    public function sendOtp(SendOtpRequest $request)
    {
        try {
            $post = $request->all();
            if (!empty($post['user_id'])) {

                $user = $this->userRepository->firstWhere(
                    [
                        'id' => $post['user_id']
                    ]
                );
                $this->userRepository->sendOtpProfile($user, $post);
            } else {
                if (isset($post['username_type']) && $post['username_type'] == 'phone_number') {
                    $user = $this->userRepository->firstWhere(
                        [
                            'phone_code' => $post['phone_code'],
                            'phone_number' => $post['phone_number']
                        ]
                    );
                } else {
                    $user = $this->userRepository->firstWhere(
                        [
                            'email' => $post['email']
                        ]
                    );
                }
                $this->userRepository->sendOtp($user, '');
            }

            return $this->successResponse(__('message.otp_send_success'));

        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method forgot password
     *
     * @param ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throw Exception
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $post = $request->all();
            $post['type'] = User::VERIFY_TYPE_RESET;
            $this->userRepository->verifyOtp($post);
            return $this->successResponse(__('message.password_set_success'));
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method logout
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\LogoutResponse
     *
     * @throw Exception
     */
    public function logout(Request $request)
    {
        try {
            /**
             * User object
             *
             * @var User $user
             */
            $user = auth()->user();
            $this->userDeviceRepository->deleteDevice($user->id);
            if (!empty($user)) {
                // Revoke current user token
                $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            }
            return $this->successResponse(__('message.logout_success'));
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 422);
        }
    }

    /**
     * Method _loginUser
     *
     * @param mixed $request
     *
     * @return User
     */
    private function _loginUser($request)
    {
        $post = $request->all();
        $user = $this->userRepository->checkLoginApi($post);
        if (!empty($user)) {
            $doVerify = getVerificationRequired($user->user_type);
            if (!$doVerify && $user->isEmailVerified()) {
                $this->_updateDevice($user, $post);
                Auth::loginUsingId($user->id);
            }
            return $user;
        }
        throw new CustomException(
            __('message.error.invalid_login_details')
        );
    }

    /**
     * Method _updateDevice
     *
     * @param User  $user
     * @param array $deviceData
     *
     * @return bool
     */
    private function _updateDevice(User $user, array $deviceData):bool
    {
        //Revoke all tokens if single device login
        if (config('constants.single_device_login')) {
            $user->tokens()->delete();
        }
        $user->withAccessToken($user->createToken('accessToken')->plainTextToken);

        $deviceData['user_id'] = $user->id;
        $this->userDeviceRepository->createDevice($deviceData);

        return true;
    }

    /**
     * Method profileVerifyOtp
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public function profileVerifyOtp($request)
    {
        $result = $this->userRepository->profileVerifyOtp($request->all());
        if ($result) {
            return $this->successResponse(
                __('message.otp_verify_successfully')
            );
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong'), 422
        );
    }
    
    /**
     * Method loginWithDocCheck
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public function loginWithDocCheck(Request $request)
    {
        try {
            $post = $request->all();
            $docCheckUserData = $this->doccheckService->userData($post['code']);
            $randomNumber = rand(0, 99);
            if ($docCheckUserData) {
                $userData = [
                    "email" => isset($docCheckUserData['email']) ? $docCheckUserData['email'] : "lorem{$randomNumber}@mailinator.com",
                    "name" => isset($docCheckUserData['address_name_first']) ? $docCheckUserData['address_name_first']. '' . $docCheckUserData['address_name_last'] : "Dr. lorem {$randomNumber}",
                    "address" => isset($docCheckUserData['address_street']) ? $docCheckUserData['address_street'] : "Indore {$randomNumber}",
                    "phone_code" => "91",
                    "phone_number" => "999999999".$randomNumber,
                    "address" => "Vijay nagar",
                    "country_id" => isset($docCheckUserData['address_country_id']) ? $docCheckUserData['address_country_id'] : "101",
                    "city_id" => 2201,
                    "zip_code" => isset($docCheckUserData['address_postal_code']) ? $docCheckUserData['address_postal_code'] : "1234{$randomNumber}",
                ];
                $user = $this->userRepository->firstWhere(['uniquekey' => $docCheckUserData['uniquekey']]);
                if (!empty($user)) {
                    $user = $this->userRepository->update($userData, $user->id);
                } else {
                    $userData["email_verified_at"] = Carbon::now();
                    $userData["user_type"] = User::TYPE_MEDICAL;
                    $userData["uniquekey"] =  $docCheckUserData['uniquekey'];
                    $userData["status"] =  'active';
                    $user = $this->userRepository->create($userData);
                    $user->notify(new WelcomeUser($user));
                }
            
                if (!empty($user)) {
                    Auth::loginUsingId($user->id);
                    $this->_updateDevice($user, $post);
                    return new UserResource($user);
                } else {
                    return $this->errorResponse(
                        __('message.error.something_went_wrong'), 422
                    );
                }
            }
            return $this->errorResponse(
                __('message.error.something_went_wrong'), 422
            );
        } catch(Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

}
