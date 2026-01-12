<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Exceptions\CustomException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\VerifyOtpRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $userRepository;


    /**
     * Method __construct
     *
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Function showLoginForm
     *
     * @return void
     */
    public function index()
    {
        return view('admin.auth.login');
    }

    /**
     * Function login
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $post = $request->all();
        request()->session()->put('time_zone', $post['timezone']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $credentials = $request->only(['email', 'password']);
        throw_if(
            !Auth::validate($credentials),
            CustomException::class,
            __('message.error.invalid_admin_login_details'), 400
        );

        Auth::validate($credentials);
        $user = $this->userRepository->checkLogin($post);

        $this->incrementLoginAttempts($request);

        if (!empty($user)) {
            if (!Hash::check($request->password, $user->password)) {
                return $this->sendFailedLoginResponse($request);
            } elseif (!in_array($user->user_type, [User::TYPE_ADMIN, User::TYPE_STAFF])) {
                throw new CustomException(
                    __('message.error.invalid_admin_login_details'), 400
                );
            } else {
                return $this->checkOtpPageRequired($user, $request);
            }
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Function sendLoginResponse
     *
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(User $user, Request $request)
    {
        $this->guard('web')->login($user, $request->get('remember'));
        $user->should_re_login = false;
        $user->save();
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        if ($request->expectsJson()) {
            $redirectionUrl = route('admin.dashboard');
            if (!$user->can('admin.dashboard.index')) {
                $redirectionUrl = route('admin.profile.index');
            }
            $data = [
                'success' => true,
                'message' => trans('auth.log_in'),
                'redirectionUrl' => $redirectionUrl,
            ];
            return $this->responseSend($data);
        }
    }

    /**
     * Function logout
     *
     * @param Request $request
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $this->guard('web')->logout();
        }
        session()->flash('success', trans('auth.log_out'));

        return redirect()->route('admin.login');
    }

    /**
     * Method checkOtpPageRequired
     *
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function checkOtpPageRequired(User $user, Request $request)
    {
        $revertResponse = [];
        $doVerify = getVerificationRequired($user->user_type);
        if (!$doVerify) {
            $revertResponse = $this->sendLoginResponse($user, $request);
        } else {
            $this->userRepository->sendOtp($user, '');
            $redirectionUrl = route('admin.login.otp.show', encrypt($user->email));
            $data = [
                'success' => true,
                'message' => trans('auth.redirecting_otp_page'),
                'redirectionUrl' => $redirectionUrl,
            ];
            $revertResponse = $this->responseSend($data);
        }

        return $revertResponse;
    }

    /**
     * Function showVerifyOtpForm
     *
     * @param string $email
     *
     * @return \Illuminate\View\View
     */
    public function showVerifyOtpForm($email)
    {

        $resendOtpUrl = route('admin.login.otp.resend', $email);
        $email = decrypt($email);
        $user = $this->userRepository
            ->firstWhere(
                [
                    ['email', '=', $email],
                    ['otp_expires_at', '>', Carbon::now()->subMinute(config("constants.otp.max_time"))]
                ]
            );
        if ($email != null && $user != null) {
            return view('admin.auth.otp-required', compact('email', 'resendOtpUrl'));
        }
        $msg = __('message.error.user_detail_not_found');
        return redirect()->route('admin.login')->with('error', $msg);
    }

    /**
     * Method verifyOtp
     *
     * @param VerifyOtpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $post = $request->all();
        $post['type'] = User::VERIFY_TYPE_OTP;
        $user = $this->userRepository->verifyOtp($post);
        return $this->sendLoginResponse($user, $request);
    }


    /**
     * Method resendOtp
     *
     * @param string  $email
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(string $email, Request $request)
    {
        $email = decrypt($email);
        $user = $this->userRepository
            ->firstWhere(
                [
                    ['email', '=', $email],
                    ['otp_expires_at', '>', Carbon::now()->subMinute(config("constants.otp.max_time"))]
                ]
            );
        if ($request->expectsJson() && !empty($user)) {
            $this->userRepository->sendOtp($user, '');
            $data = [
                'success' => true,
                'message' => trans('auth.resent_otp'),
            ];
            return $this->responseSend($data);
        }
        abort(404);
    }


    /**
     * Method responseSend
     *
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSend(array $data)
    {
        return response()->json($data);
    }
}
