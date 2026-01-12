<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Facades\Log;
use Str;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\ResetPasswordRepository;
use App\Http\Requests\Admin\VerifyEmailRequest;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Http\Requests\Admin\OtpResetPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    protected $userRepository;
    protected $resetPasswordRepository;

    use SendsPasswordResetEmails;
    /**
     * Function __construct
     *
     * @param $userRepository          UserRepository
     * @param $resetPasswordRepository ResetPasswordRepository
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        ResetPasswordRepository $resetPasswordRepository
    ) {
        $this->userRepository = $userRepository;
        $this->resetPasswordRepository = $resetPasswordRepository;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.password.reset-link');
    }

    /**
     * Method sendResetLinkEmail
     *
     * @param VerifyEmailRequest $request [explicite description]
     *
     * @return void
     */
    public function sendResetLinkEmail(VerifyEmailRequest $request)
    {
        $user = $this->userRepository->getUserByTypes(
            [
                'email' => $request->email
            ],
            [
                User::TYPE_STAFF,
                User::TYPE_ADMIN
            ]
        );

        if (empty($user)) {
            return back()->with('error', __('message.error.email.not_found'));
        }

        $redirectUrl = route('admin.password.success');
        if (config('constants.reset_password.admin') == 'otp') {
            $this->userRepository->sendOtp($user, '');
            $token = encrypt($user->email);
            $redirectUrl = route('admin.otp.password.get', ['email'=>$token]);
        } else {
            $data = [
                    'email' => $request->email,
                    'name' => $user->name,
                    'token' => Str::random(64),
                    'created_at' => date("Y-m-d H:i:s"),
                    'user_type' => User::TYPE_ADMIN,
                    'url' => 'admin.password.get'
                ];

            $this->resetPasswordRepository->createRecord($data);
        }
        return redirect($redirectUrl)->with('success', __('message.otp_send_success')); //passwords.sent
    }

    /**
     * Show reset password page
     *
     * @param string $token
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        try {
            $tokenValid = DB::table('password_resets')
                ->where('token', '=', $token)
                ->where('created_at', '>', Carbon::now()->subMinute(10))
                ->first();
            if ($tokenValid != null) {
                $where = ['token' => $token];
                $email = $this->resetPasswordRepository->firstWhere($where)->email;
                return view('admin.auth.password.reset-password-form', ['token' => $token, 'email' => $email]);
            }
            $msg = __('message.error.link_expire');

            return redirect()->route('admin.forgot-password')->with('error', $msg);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Show success page after reset password link has been sent
     *
     * @return View
     */
    public function showSuccessPage()
    {
        return view('admin.auth.password.success');
    }


    /**
     * Method submitResetPasswordForm
     *
     * @param ResetPasswordRequest $request
     *
     * @return void
     */
    public function submitResetPasswordForm(ResetPasswordRequest $request)
    {

        $data = [
            'email' => $request->email,
            'token' => $request->token,
            'password' => $request->password
        ];
        $this->resetPasswordRepository->resetPassword($data);

        if ($request->ajax()) {
            $data = ['redirect_url' => '/admin/login'];
            return $this->successResponse(__('passwords.reset'), $data);
        }

        return redirect()->route('admin.login')
            ->with('message', __('passwords.reset'));
    }


    /**
     * Show otp reset password page
     *
     * @param string $email
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showOtpResetPasswordForm($email)
    {
        try {
            $email = decrypt($email);
            $user = $this->userRepository
                ->firstWhere(
                    [
                        ['email', '=', $email],
                        ['otp_expires_at', '>', Carbon::now()->subMinute(config("constants.otp.max_time"))]
                    ]
                );
            if ($email != null && $user != null) {
                return view('admin.auth.password.otp-reset-password-form', [ 'email' => $email]);
            }
            $msg = __('message.error.link_expire');
            return redirect()->route('admin.forgot-password')->with('error', $msg);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        $msg = __('message.error.link_expire');
        return redirect()->route('admin.forgot-password')->with('error', $msg);

    }


    /**
     * Method submitOtpResetPasswordForm
     *
     * @param OtpResetPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function submitOtpResetPasswordForm(OtpResetPasswordRequest $request)
    {
        try {
            $post = $request->all();
            $post['type'] = User::VERIFY_TYPE_RESET;
            $this->userRepository->verifyOtp($post);

            if ($request->ajax()) {
                $data = ['redirectionUrl' => route('admin.login')];
                return $this->successResponse(__('message.password_set_success'), $data);
            }

            return redirect()->route('admin.login')->with('message', __('passwords.reset'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
