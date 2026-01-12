<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserCard;
use App\Models\ProductScanner;
use App\Notifications\CreateUser;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OtpVerification;
use App\Mail\ProfileOtpVerification;

class UserRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getUser
     *
     * @param int $id [explicite description]
     *
     * @return User
     */
    public function getUser(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Method getUserByField
     *
     * @param $where $where
     *
     * @return User|null
     */
    public function getUserByField($where): ?User
    {
        return $this->model->where($where)->first();
    }

    /**
     * Method createUser
     *
     * @param array $data     [explicite description]
     * @param bool  $doVerify [explicite description]
     *
     * @return User
     */
    public function createUser(array $data, bool $doVerify = false): User
    {
        if (!empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
        }
        $data['change_password_at'] = Carbon::now();
        $user = $this->create($data);
        $userType = Auth::user()->user_type;
        if (($userType == User::TYPE_STAFF)) {
            $userData = User::onlyAdmin()->first();
            $userData->notify(new CreateUser($user));
        }
        if ($doVerify) {
            $this->sendOtp($user, '');
        } else {
            $user->notify(new WelcomeUser($user));
        }

        return $user;
    }

    /**
     * Method registerClient
     *
     * @param array $data     [explicite description]
     * @param bool  $doVerify [explicite description]
     *
     * @return User
     */
    public function registerClient(array $data, bool $doVerify = false): User
    {
        $client = $this->create($data);
        if (!empty($client)) {
            $userCard = new UserCard();
            $userCard->user_id = $client->id;
            $userCard->card_number = $data['card_number'];
            $userCard->card_holder_name = $data['card_holder_name'];
            $userCard->ifsc_code = $data['ifsc_code'];
            $userCard->iban_number = $data['iban_number'];
            $userCard->gtin_number = $data['gtin_number'];
            $userCard->save();
        }
        return $client;
    }

    /**
     * Method updateUser
     *
     * @param array $data [explicite description]
     * @param int   $id   [explicite description]
     * @param ?User $user [explicite description]
     *
     * @return User
     */
    public function updateUser(array $data, int $id, ?User $user = null): User
    {
        if (!$user) {
            $user = $this->getUser($id);
        }
        if (!empty($user) && !empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
            if (!empty($user->profile_image)) {
                deleteFile($user->profile_image);
            }
        } else {
            unset($data['profile_image']);
        }
        $updated = $this->update($data, $id);
        if ($updated) {
            $userCard =  UserCard::where('user_id', $id)->first();
            if (!empty($userCard)) {
                if (isset($data['card_number'])) {
                    $userCard->card_number = $data['card_number'];
                }
                if (isset($data['card_holder_name'])) {
                    $userCard->card_holder_name = $data['card_holder_name'];
                }
                if (isset($data['ifsc_code'])) {
                    $userCard->ifsc_code = $data['ifsc_code'];
                }
                if (isset($data['iban_number'])) {
                    $userCard->iban_number = $data['iban_number'];
                }
                if (isset($data['gtin_number'])) {
                    $userCard->gtin_number = $data['gtin_number'];
                }
                $userCard->save();
            }
            return $this->getUser($user->id);
        }

        return $user;
    }

    /**
     * Method sendOtp
     *
     * @param User $user [explicite description]
     * @param $type $type [explicite description]
     *
     * @return bool
     */
    public function sendOtp(User $user, $type = null): bool
    {
        $otpExpiryTime = Carbon::now()->addMinutes(config('constants.otp.max_time'));
        $user->otp = generateOtp();
        $user->otp_expires_at = $otpExpiryTime;
        $user->save();
        $user->notify(new OtpVerification($user, $type));
        return true;
    }
    /**
     * Method sendOtpProfile
     *
     * @param User $user [explicite description]
     * @param $post $post [explicite description]
     *
     * @return bool
     */
    public function sendOtpProfile(User $user, $post): bool
    {
        $otpExpiryTime = Carbon::now()->addMinutes(config('constants.otp.max_time'));
        $user->otp = generateOtp();
        $user->otp_expires_at = $otpExpiryTime;
        $changedEmail = '';
        if (isset($post['username_type']) && $post['username_type'] == 'phone_number') {
            $user->temp_contact = $post['phone_code'].'#'.$post['phone_number'];
        } else {
            $user->temp_contact = $post['email'];
            $changedEmail = $post['email'];
        }
        $user->save();
        if ($changedEmail != '') {
            Mail::to($changedEmail)->send(new ProfileOtpVerification($user));
        } else {
            $user->notify(new OtpVerification($user, ''));
        }


        return true;
    }

    /**
     * Method checkLogin
     *
     * @param array $data [explicite description]
     *
     * @return User
     */
    public function checkLogin(array $data)
    {
        $user = $this->model->where('email', $data['email'])->first();
        if (empty($user)) {
            throw new CustomException(__('message.error.invalid_admin_login_details'), 422);
        }
        //Check profile active
        if (($user->status === User::STATUS_INACTIVE) || ($user->should_re_login === 1)) {
            throw new CustomException(__('message.error.account_is_inactive'), 422);
        }
        //Check for otp verification
        if (!$user->isEmailVerified() && getVerificationRequired($user->user_type)) {
            // Send otp if email is not verified.
            $this->sendOtp($user, '');
            throw new CustomException(__('message.error.account_not_verified'), 422);
        }

        return $user;
    }


    /**
     * Method checkClientLogin
     *
     * @param array $data [explicite description]
     *
     * @return user
     */
    public function checkClientLogin(array $data)
    {
        $user = $this->model->where('email', $data['email'])->first();
        if (empty($user)) {
            throw new CustomException(__('message.error.invalid_admin_login_details'), 422);
        }
        //Check profile active
        if (($user->status === User::STATUS_INACTIVE) || ($user->should_re_login === 1)) {
            throw new CustomException(__('message.error.account_is_inactive'), 422);
        }
        return $user;
    }

    /**
     * Method checkLoginApi
     *
     * @param array $data [explicite description]
     *
     * @return User
     */
    public function checkLoginApi(array $data)
    {
        try {
            if (isset($data['username_type']) && $data['username_type'] == 'phone_number') {
                $user = $this->getUserByField(
                    [
                        'phone_code' => $data['phone_code'],
                        'phone_number' => $data['phone_number']
                    ]
                );
                $loginErrorMsg = __('message.error.phone_number_does_not_exist');
            } else {
                $user = $this->getUserByField(
                    [
                        'email' => $data['email']
                    ]
                );
                $loginErrorMsg = __('message.error.email_does_not_exist');
            }

            if (empty($user)) {
                throw new CustomException($loginErrorMsg);
            }

            if (!empty($user) && !in_array($user->user_type, [User::TYPE_BASIC, User::TYPE_MEDICAL])) {
                throw new CustomException(__('message.error.exception.user_not_found'), 422);
            }

            //Check user verified after register
            if (($user->status === User::STATUS_INACTIVE) && is_null($user->email_verified_at) && is_null($user->phone_number_verified_at)) {
                if (getVerificationRequired($user->user_type)) {
                    // Send otp if email is not verified.
                    $this->sendOtp($user, '');
                }
                return $user;
            }
            //Check profile active
            if ($user->status === User::STATUS_INACTIVE) {
                throw new CustomException(__('message.error.account_is_inactive'), 422);
            }

            if (getVerificationRequired($user->user_type)) {
                // Send otp if email is not verified.
                $this->sendOtp($user, '');
            }
            return $user;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Method verifyOtp
     *
     * @param array $data [explicite description]
     *
     * @return User
     */
    public function verifyOtp(array $data): User
    {
        $user = $this->firstWhere(['email' => $data['email']]);
        if (empty($user)) {
            throw new CustomException(__('message.error.email.not_found'), 400);
        }
        $currentTime = Carbon::now();
        if (empty($user->otp) || $currentTime > $user->otp_expires_at) {
            throw new CustomException(__('message.error.otp.expired'), 400);
        }

        throw_if($user->otp != $data['otp'], CustomException::class, __('message.error.otp.not_matched'), 400);

        if ($user->otp == $data['otp']) {
            if ($data['type'] == User::VERIFY_TYPE_OTP) {
                return $user;
            }
            $userData = ['otp' => null, 'otp_expires_at' => null];
            if (!empty($data['type']) && $data['type'] == User::VERIFY_TYPE_REGISTER) {
                $userData['email_verified_at'] = Carbon::now();
                $userData['status'] = User::STATUS_ACTIVE;
            }
            if (!empty($data['type']) && $data['type'] == User::VERIFY_TYPE_LOGIN) {
                $userData['email_verified_at'] = Carbon::now();
            }
            if (!empty($data['type']) && $data['type'] == User::VERIFY_TYPE_RESET) {
                $userData['password'] = $data['password'];
            }
            $this->updateUser($userData, $user->id);

            return $this->firstWhere(['email' => $data['email']]);
        }
        throw new CustomException(__('message.error.otp.expired'), 400);
    }

    /**
     * Method verifyOtpApi
     *
     * @param array $data [explicite description]
     *
     * @return User
     */
    public function verifyOtpApi(array $data): User
    {
        if (isset($data['username_type']) && $data['username_type'] == 'phone_number') {
            $user = $this->getUserByField(
                [
                    'phone_code' => $data['phone_code'],
                    'phone_number' => $data['phone_number']
                ]
            );
            $userVerifiedAt = 'phone_number_verified_at';
        } else {
            $user = $this->getUserByField(
                [
                    'email' => $data['email']
                ]
            );
            $userVerifiedAt = 'email_verified_at';
        }
        if (empty($user)) {
            throw new CustomException(__('message.error.exception.user_not_found'), 400);
        }

        if (!empty($data['type']) && $data['type'] == User::VERIFY_TYPE_LOGIN) {
            if ($user->status != User::STATUS_ACTIVE && !is_null($user->$userVerifiedAt)) {
                throw new CustomException(__('message.error.account_is_inactive'), 400);
            }
        }

        $currentTime = Carbon::now();
        if (empty($user->otp) || $currentTime > $user->otp_expires_at) {
            throw new CustomException(__('message.error.otp.expired'), 400);
        }

        throw_if($user->otp != $data['otp'], CustomException::class, __('message.error.otp.not_matched'), 400);

        if ($user->otp == $data['otp']) {
            $userData = ['otp' => null, 'otp_expires_at' => null];
            if (!empty($data['type']) && in_array($data['type'], [User::VERIFY_TYPE_REGISTER, User::VERIFY_TYPE_OTP, User::VERIFY_TYPE_LOGIN])) {
                Auth::loginUsingId($user->id);
                $userData['status'] = User::STATUS_ACTIVE;
                if (isset($data['username_type']) && $data['username_type'] == 'phone_number') {
                    $userData['phone_number_verified_at'] = Carbon::now();
                } else {
                    $userData['email_verified_at'] = Carbon::now();
                }
            }

            if (!empty($data['type']) && $data['type'] == User::VERIFY_TYPE_RESET) {
                $userData['password'] = $data['password'];
            }
            $this->updateUser($userData, $user->id);

            $user = $this->model->find($user->id);
            return $user;
        }
        throw new CustomException(__('message.error.otp.expired'), 400);
    }

    /**
     * Method updatePassword
     *
     * @param $data $data [explicite description]
     *
     * @return void
     */
    public function updatePassword($data)
    {
        $user = $this->getUser(getLoggedInUserDetail()->id);

        throw_if(
            empty($user),
            CustomException::class,
            __('message.error.exception.user_not_found'),
            400
        );

        $user->password = $data['password'];
        $user->change_password_at = Carbon::now();
        if ($user->save()) {
            return true;
        }
        throw new CustomException(__('message.error.something_went_wrong'), 400);
    }

    /**
     * Method getUserList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getUserList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'users.id',
            'name' => 'users.name',
            'created_at' => 'users.created_at',
        ];
        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $name = $data['name'] ?? '';
        $status = $data['status'] ?? '';
        $email = $data['email'] ?? '';
        $type = $data['type'] ?? '';
        $fromDate = $data["fromDate"] ?? '';
        $toDate = $data["toDate"] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];
        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        if (isset($export) && $export == 'export') {
            $paginate = false;
        }
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $user = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where(
                        function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        }
                    );
                }
            )
            ->when(
                $fromDate,
                function ($q) use ($fromDate) {
                    $q->where('created_at', '>=', $fromDate . ' 00:00:00');
                }
            )->when(
                $toDate,
                function ($q) use ($toDate) {
                    $q->where('created_at', '<=', $toDate . ' 23:59:59');
                }
            )
            ->when(
                $name,
                function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%');
                }
            )
            ->when(
                $status,
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )
            ->when(
                $type,
                function ($q) use ($type) {
                    $q->where('user_type', $type);
                }
            )
            ->when(
                $email,
                function ($q) use ($email) {
                    $q->where('email', 'like', '%' . $email . '%');
                }
            )
            ->where(
                function ($q) {
                    $q->where('user_type', User::TYPE_MEDICAL)
                        ->orWhere('user_type', User::TYPE_BASIC);
                }
            )
            ->where(
                function ($q) {
                    $q->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->orderBy($sort, $sortDirection);
        if (!$paginate) {
            $result = $user->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $user->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method changeStatus
     *
     * @param array $data [explicite description]
     * @param int   $id   [explicite description]
     *
     * @return void
     */
    public function changeStatus(array $data, int $id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            return $this->model->where('id', $id)->update(
                [
                    'status' => $data['status'],
                ]
            );
        }
    }

    /**
     * Method getUserDetail
     *
     * @param $id $id [explicite description]
     *
     * @return object
     */
    public function getUserDetail($id)
    {
        return $this->model->find($id);
    }

    /**
     * Method deleteUser
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function deleteUser(User $user)
    {
        return $user->delete();
    }

    /**
     * Method getUserTypeByUserList
     *
     * @param string $userType
     *
     * @return object
     */
    public function getUserTypeByUserList(string $userType)
    {
        $userList = $this->model::where(
            [
                'user_type' => $userType,
                'status' => User::STATUS_ACTIVE
            ]
        )->orderBy('id', 'ASC')->get();

        return $userList;
    }

    /**
     * Method getUserProductDetail
     *
     * @param array $data [explicite description]
     * @param $id   $id [explicite description]
     *
     * @return void
     */
    public function getUserProductDetail(array $data, $id)
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'product_name',
        ];
        $search = $data['search'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'asc';
        $sort = $sortFields['id'];
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $user = ProductScanner::with('product')
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->whereHas(
                        'product',
                        function ($q) use ($search) {
                            $q->where('product_name', 'like', '%' . $search . '%')
                                ->orWhere('udi_number', 'like', '%' . $search . '%');
                        }
                    );
                }
            )
            ->where('user_id', $id)
            ->when(
                (auth()->user()->user_type == User::TYPE_CLIENT),
                function ($q) {
                    $q->whereHas(
                        'product', function ($q) {
                            $q->where('client_id', auth()->user()->id);
                        }
                    );
                }
            )
            ->orderBy($sort, $sortDirection);
        $result = $user->paginate($limit);

        return $result;
    }

    /**
     * Method getUserByTypes
     *
     * @param array $where [explicite description]
     * @param array $types [explicite description]
     *
     * @return User
     */
    public function getUserByTypes(array $where, array $types): ?User
    {
        return $this->model->where($where)->whereIn('user_type', $types)->first();
    }

    /**
     * Method getMyScannedProductUserList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getMyScannedProductUserList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'name',
            'phone_number' => 'phone_number',
        ];
        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];
        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        if (isset($export) && $export == 'export') {
            $paginate = false;
        }
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $user = $this->model
            ->whereHas(
                'productScanners.product', function ($query) {
                        $query->where('client_id', auth()->user()->id);
                }
            )
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where(
                        function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere(DB::raw("CONCAT(phone_code, phone_number)"), 'like', '%'.$search.'%');
                        }
                    );
                }
            )
            ->orderBy($sort, $sortDirection);
        if (!$paginate) {
            $result = $user->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $user->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method profileVerifyOtp
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function profileVerifyOtp(array $data)
    {
        $userId = $data['user_id'];
        $user = $this->model->where('id', $userId)->first();
        if (empty($user)) {
            throw new CustomException(__('message.error.exception.user_not_found'), 400);
        }

        $currentTime = Carbon::now();
        if (empty($user->otp) || $currentTime > $user->otp_expires_at) {
            throw new CustomException(__('message.error.otp.expired'), 400);
        }

        throw_if($user->otp != $data['otp'], CustomException::class, __('message.error.otp.not_matched'), 400);

        if ($user->otp == $data['otp']) {
            DB::transaction(
                function () use ($user, $data) {
                    $tempContact = $user->temp_contact;
                    $tempContactArray = explode('#', $tempContact);
                    $email = '';
                    $phoneCode = '';
                    $phoneNumber = '';
                    $where = [];
                    $message = '';
                    if (count($tempContactArray) == 1) {
                        if (filter_var($tempContactArray[0], FILTER_VALIDATE_EMAIL)) {
                            $email = $tempContactArray[0];
                            $where['email'] = $email;
                            $message = __('message.error.emailAlreadyExists');
                        }
                    } elseif (count($tempContactArray) == 2) {
                        $phoneCode = $tempContactArray[0];
                        $phoneNumber = $tempContactArray[1];
                        $where['phone_code'] = $phoneCode;
                        $where['phone_number'] = $phoneNumber;
                        $message = __('message.error.phoneNumberAlreadyTaken');
                    }
                    if (!empty($where)) {
                        $checkUser = $this->model->where($where)->first();
                        if (!empty($checkUser)) {
                            throw new CustomException($message, 400);
                        }
                    }

                    $userData = ['otp' => null, 'otp_expires_at' => null, 'temp_contact' => null];

                    if (isset($data['username_type']) && $data['username_type'] == 'phone_number') {
                        if ($phoneCode == $data['phone_code'] && $phoneNumber == $data['phone_number']) {
                            $userData['phone_number_verified_at'] = Carbon::now();
                            $userData['phone_code'] = $data['phone_code'];
                            $userData['phone_number'] = $data['phone_number'];
                        }
                    } else {
                        if ($email == $data['email']) {
                            $userData['email_verified_at'] = Carbon::now();
                            $userData['email'] = $data['email'];
                        }
                    }

                    $this->updateUser($userData, $user->id);
                }, 1
            );
            $user = $this->model->find($user->id);
            return $user;
        }
        throw new CustomException(__('message.error.otp.expired'), 400);
    }


    /**
     * Method getTotalActiveUsers
     *
     * @return void
     */
    public function getTotalActiveUsers()
    {
        return $this->model->where('status', User::STATUS_ACTIVE)
            ->where('user_type', User::TYPE_BASIC)
            ->where(
                function ($q) {
                    $q->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->get();
    }

    /**
     * Method getTotalInactiveUsers
     *
     * @return void
     */
    public function getTotalInactiveUsers()
    {
        return $this->model->where('status', User::STATUS_INACTIVE)
            ->where('user_type', User::TYPE_BASIC)
            ->where(
                function ($query) {
                    $query->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->get();
    }

    /**
     * Method getTotalActiveClients
     *
     * @return void
     */
    public function getTotalActiveClients()
    {
        return $this->model->where('status', User::STATUS_ACTIVE)
            ->where('user_type', User::TYPE_CLIENT)
            ->get();
    }

    /**
     * Method getTotalInactiveClients
     *
     * @return void
     */
    public function getTotalInactiveClients()
    {
        return $this->model->where('status', User::STATUS_INACTIVE)
            ->where('user_type', User::TYPE_CLIENT)
            ->where(
                function ($query) {
                    $query->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->get();
    }


    /**
     * Method getRecentUserList
     *
     * @return void
     */
    public function getRecentUserList()
    {
        return $this->model
            ->where(
                function ($q) {
                    $q->where('user_type', User::TYPE_MEDICAL)
                        ->orWhere('user_type', User::TYPE_BASIC);
                }
            )
            ->where(
                function ($q) {
                    $q->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();
    }

    /**
     * Method getActiveUserList
     *
     * @return void
     */
    public function getActiveUserList()
    {
        return $this->model
            ->where(
                function ($q) {
                    $q->where('user_type', User::TYPE_MEDICAL)
                        ->orWhere('user_type', User::TYPE_BASIC);
                }
            )
            ->where(
                function ($q) {
                    $q->whereNotNull('email_verified_at')
                        ->orWhereNotNull('phone_number_verified_at');
                }
            )
            ->where('status', User::STATUS_ACTIVE)
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();
    }
}
