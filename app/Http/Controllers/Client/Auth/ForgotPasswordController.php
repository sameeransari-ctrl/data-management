<?php

namespace App\Http\Controllers\Client\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\ResetPasswordRepository;
use App\Http\Requests\Client\VerifyEmailRequest;
use App\Http\Requests\Client\OtpResetPasswordRequest;

class ForgotPasswordController extends Controller
{
    protected $userRepository;
    protected $resetPasswordRepository;

    /**
     * Method __construct
     *
     * @param UserRepository          $userRepository          [explicite description]
     * @param ResetPasswordRepository $resetPasswordRepository [explicite description]
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
     * Method showLinkRequestForm
     *
     * @return void
     */
    public function showLinkRequestForm()
    {
        return view('client.auth.password.reset-link');
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
        $client = $this->userRepository->getUserByTypes(
            [
                'email' => $request->email
            ],
            [
                User::TYPE_CLIENT
            ]
        );

        if (empty($client)) {
            return back()->with('error', __('message.error.email.not_found'));
        }

        $redirectUrl = route('client.password.success');
        if (config('constants.reset_password.client') == 'otp') {
            $this->userRepository->sendOtp($client, '');
            $token = encrypt($client->email);
            $redirectUrl = route('client.otp.password.get', ['email'=>$token]);
        } else {
            $data = [
                    'email' => $request->email,
                    'name' => $client->name,
                    'token' => Str::random(64),
                    'created_at' => date("Y-m-d H:i:s"),
                    'user_type' => User::TYPE_CLIENT,
                    'url' => 'client.password.get',
                ];

            $this->resetPasswordRepository->createRecord($data);
        }
        return redirect($redirectUrl)->with('success', __('message.otp_send_success')); //passwords.sent
    }

    /**
     * Method showOtpResetPasswordForm
     *
     * @param $email $email [explicite description]
     *
     * @return void
     */
    public function showOtpResetPasswordForm($email)
    {
        $email = decrypt($email);
        $user = $this->userRepository
            ->firstWhere(
                [
                    ['email', '=', $email],
                    ['otp_expires_at', '>', Carbon::now()->subMinute(config("constants.otp.max_time"))]
                ]
            );
        if ($email != null && $user != null) {
            return view('client.auth.password.otp-reset-password-form', [ 'email' => $email]);
        }
        $msg = __('message.error.link_expire');
        return redirect()->route('client.forgot-password')->with('error', $msg);
    }

    /**
     * Method submitOtpResetPasswordForm
     *
     * @param OtpResetPasswordRequest $request [explicite description]
     *
     * @return void
     */
    public function submitOtpResetPasswordForm(OtpResetPasswordRequest $request)
    {

        $post = $request->all();
        $post['type'] = User::VERIFY_TYPE_RESET;
        $this->userRepository->verifyOtp($post);

        if ($request->ajax()) {
            $data = ['redirectionUrl' => route('client.login')];
            return $this->successResponse(__('message.password_set_success'), $data);
        }

        return redirect()->route('client.login')->with('message', __('passwords.reset'));
    }
}
