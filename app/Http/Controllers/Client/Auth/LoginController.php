<?php

namespace App\Http\Controllers\Client\Auth;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Client\LoginRequest;
use App\Http\Requests\Client\VerifyOtpRequest;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $userRepository;

    /**
     * Method __construct
     *
     * @param UserRepository $userRepository [explicite description]
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        return view('client.auth.login');
    }

    /**
     * Method login
     *
     * @param LoginRequest $request [explicite description]
     *
     * @return void
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
        $user = $this->userRepository->checkClientLogin($post);

        $this->incrementLoginAttempts($request);

        if (!empty($user)) {
            if (!Hash::check($request->password, $user->password)) {
                return $this->sendFailedLoginResponse($request);
            } elseif (!in_array($user->user_type, [User::TYPE_CLIENT])) {
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
     * Method checkOtpPageRequired
     *
     * @param \App\Models\User $user    — [explicite description]
     * @param Request          $request [explicite description]
     *
     * @return void
     */
    protected function checkOtpPageRequired(User $user, Request $request)
    {
        $revertResponse = [];
        $isEmailVerified = $user->isEmailVerified();

        if ($isEmailVerified) {
            $revertResponse = $this->sendLoginResponse($user, $request);
        } else {
            $this->userRepository->sendOtp($user, '');
            $redirectionUrl = route('client.login.otp.show', encrypt($user->email));
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
     * Method sendLoginResponse
     *
     * @param User    $user    [explicite description]
     * @param Request $request [explicite description]
     *
     * @return void
     */
    protected function sendLoginResponse(User $user, Request $request)
    {
        $this->guard('web')->login($user, $request->get('remember'));
        $user->should_re_login = false;
        $user->save();
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        if ($request->expectsJson()) {
            $redirectionUrl = route('client.dashboard');
            $data = [
                'success' => true,
                'message' => trans('auth.log_in'),
                'redirectionUrl' => $redirectionUrl,
            ];
            return $this->responseSend($data);
        }
    }

    /**
     * Method logout
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $this->guard('web')->logout();
        }
        session()->flash('success', trans('auth.log_out'));

        return redirect()->route('client.login');
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

    /**
     * Method showVerifyOtpForm
     *
     * @param $email $email [explicite description]
     *
     * @return void
     */
    public function showVerifyOtpForm($email)
    {
        $resendOtpUrl = route('client.login.otp.resend', $email);
        $email = decrypt($email);
        $user = $this->userRepository
            ->firstWhere(
                [
                    ['email', '=', $email],
                    ['otp_expires_at', '>', Carbon::now()->subMinute(config("constants.otp.max_time"))]
                ]
            );
        if ($email != null && $user != null) {
            return view('client.auth.otp-required', compact('email', 'resendOtpUrl'));
        }
        $msg = __('message.error.user_detail_not_found');
        return redirect()->route('client.login')->with('error', $msg);
    }

    /**
     * Method verifyOtp
     *
     * @param VerifyOtpRequest $request [explicite description]
     *
     * @return void
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $post = $request->all();
        $post['type'] = User::VERIFY_TYPE_LOGIN;
        $user = $this->userRepository->verifyOtp($post);
        return $this->sendLoginResponse($user, $request);
    }

    /**
     * Method resendOtp
     *
     * @param string  $email   [explicite description]
     * @param Request $request [explicite description]
     *
     * @return void
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

}
