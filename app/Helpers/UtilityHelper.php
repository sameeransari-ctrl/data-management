<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Setting;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as facadesRequest;

/**
 * Function used to generate otp
 *
 * @return number
 */
function generateOtp()
{
    $digits = config("constants.otp.otp_length");
    if (config("constants.otp.is_default")) {
        return config("constants.otp.default");
    }
    return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
}

/**
 * Method checkAppVersion
 *
 * @param Request $request [explicite description]
 *
 * @return array
 */
function checkAppVersion(Request $request)
{
    $response = [
        'success' => true
    ];

    $appVersion = $request->header('app-version');
    $deviceType = $request->header('device-type');
    if (!empty($deviceType) && !empty($appVersion)) {
        if ($deviceType == 'ios') {
            $data = DB::table('settings')
                ->where('setting_key', 'ios_app_version')
                ->first();
            if ($data && version_compare($appVersion, $data->setting_value, '<')) {
                $response = [
                    'success' => false,
                    'data' => [],
                    'message' => 'There is a newer version available for download! Please update the app by visiting the App store.',
                    'url' => 'https://itunes.apple.com/app/'
                ];
            }
        } elseif ($deviceType == 'android') {
            $data = DB::table('settings')
                ->where('setting_key', 'android_app_version')
                ->first();
            if ($data && version_compare($appVersion, $data->setting_value, '<')) {
                $response = [
                    'success' => false,
                    'data' => [],
                    'message' => 'There is a newer version available for download! Please update the app by visiting the Play store.'
                ];
            }
        }
    }

    return $response;
}

/**
 * Method sendMail
 *
 * @param string|User|Collection $to
 * @param Mailable               $template
 *
 * @return void
 */
function sendMail($to, $template)
{
    Mail::to($to)->send($template);
}

/**
 * Method generateReferralCode
 *
 * @param int $length [explicite description]
 *
 * @return string
 */
function generateReferralCode(int $length = 8): string
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length);
}

/**
 * Get user email with stars
 *
 * @param String $email
 *
 * @return String
 */
function obfuscateEmail($email)
{
    $em   = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len  = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

/**
 * Method getSetting
 *
 * @param string $key [explicite description]
 *
 * @return mixed
 */
function getSetting(string $key)
{
    $value = 0;
    $setting = Setting::where('setting_key', $key)->first();
    if ($setting) {
        $value = $setting->setting_value;
    }

    return $value;
}

/**
 * Method pageLoader
 *
 * @return void
 */
function pageLoader()
{
    echo '<div class="pageLoader text-center"><div class="spinner-border" role="status"></div></div>';
}

/**
 * Get price filter text
 *
 * @param $start
 *
 * @return Array
 */
function getPriceText($start)
{
    $currency = config('app.currency.default');
    $last = ($start < 100) ? $start + 50 : $start + 100;
    if ($start === 0) {
        $text = trans('labels.less_than') . ' ' . $currency . ' ' . $last;
    } elseif ($start === 500) {
        $text = $currency. ' ' . $start . '+';
    } else {
        $text = $currency . ' ' . $start . ' - ' . $currency . ' ' . $last;
    }
    $value = $start . ',' . $last;
    return [$value, $text, $last];
}

/**
 * Method makeSlug
 *
 * @param string $string
 * @param object $model
 * @param string $key
 * @param string $separator
 * @param bool   $withTrashed
 *
 * @return void
 */
function makeSlug(
    string $string,
    $model = null,
    $key = '',
    $separator = "-",
    $withTrashed = false
) {
    $slug = Str::slug($string, $separator);
    $query = $model::whereSlug($slug);
    if ($withTrashed) {
        $query->withTrashed();
    }
    $checkExists = $query->exists();
    if ($model && $key && $checkExists) {
        $qry = $model::where("slug", 'Like', '%'.$slug.'%');
        if ($withTrashed) {
            $qry->withTrashed();
        }
        $max = $qry->count();
        if ($max) {
            $max = $max+1;
            $slug = "{$slug}-$max";
        }
    }
    return $slug;
}

if (! function_exists('asset_path')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param $path              string
     * @param $manifestDirectory string
     *
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function asset_path($path, $manifestDirectory = '')
    {
        $mixPath = asset(mix($path, $manifestDirectory));
        return $mixPath;
    }
}

/**
 * Method get sidebar route check.
 *
 * @param string $name = ''
 *
 * @return string / emptystring
 */
function sidebarRouteCheck(string $name='')
{
    return ((($name != '') && (facadesRequest::routeIs($name)))
        ? 'active current-page' : facadesRequest::is($name)) ? 'active current-page' : '';
}

/**
 * Get loggedin user ID
 *
 * @return User/bool
 */
function getLoggedInUserDetail()
{
    if (auth()->check()) {
        return  auth()->user();
    }
    return false;
}

/**
 * Method getNotifications
 *
 * @return void
 */
function getNotifications()
{
    return auth()->user()->unreadNotifications()->take(5)->get();
}

/**
 * Method returnScriptWithNonce
 *
 * @param string $path
 *
 * @return string
 */
function returnScriptWithNonce($path)
{
    return '<script nonce="'.csp_nonce('script').'" src="'.$path.'"> </script>';
}

/**
 * Method getAppName
 *
 * @return string
 */
function getAppName()
{
    $websiteName = getSetting('app.name');
    return ($websiteName == 0) ? config('app.name') : $websiteName;
}

/**
 * Method getVerificationRequired
 *
 * @param $type $type
 *
 * @return void
 */
function getVerificationRequired($type = null)
{
    if ($type == User::TYPE_ADMIN) {
        return config('constants.admin_verification_required');
    } elseif ($type == User::TYPE_STAFF) {
        return config('constants.admin_verification_required');
    } else {
        return config('constants.verification_required');
    }
}

/**
 * Method sendOtpByTwillio
 *
 * @param string $receiverNumber
 * @param string $message
 *
 * @return boolean
 */
function sendOtpByTwillio($receiverNumber, $message)
{
    try {
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $result = $client->messages->create(
            $receiverNumber,
            [
                'from' => config('services.twilio.number'),
                'body' => $message
            ]
        );
        return !empty($result) ? $result->sid : false;
    } catch (Exception $exception) {
        info('sendOtpByTwillio', ['exception' => $exception]);
        return false;
    }


}

/**
 * Method forcedLogout
 *
 * @param Request $request [explicite description]
 * @param User    $user    [explicite description]
 *
 * @return void
 */
function forcedLogout(Request $request, User $user)
{
    $message = "Session Expired due to change password. Please login with new credentials provided on your registered Email";
    if (($user->status == User::STATUS_INACTIVE) || ($user->should_re_login == 1)) {
        $message = "Your account has been inactivated. Please contact Administrator.";
    }
    if ($request->is('api/*')) {
        $user->devices()->delete();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    } else {
        Auth::logout();
        session()->flash('error', $message);
    }

    if ($request->ajax() || $request->expectsJson() || $request->is('api/*')) {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
            ],
            401
        );
    }

    if ($user->user_type == User::TYPE_CLIENT) {
        return redirect()->route('client.login');
    } else {
        return redirect()->route('admin.login');
    }
}


/**
 * Method getHomeUrl
 *
 * @return string
 */
function getHomeUrl():string
{
    $homeUrl = url('/');
    if (auth()->check()) {
        /**
         * User
         *
         * @var User $user
         */
        $user = auth()->user();
        $homeUrl = route('admin.dashboard');
        if ($user->user_type == User::TYPE_STAFF && !$user->can('admin.dashboard.index')) {
            $homeUrl = route('admin.profile.index');
        }
    }

    return $homeUrl;
}


/**
 * Method notificationRoute
 *
 * @param $type
 * @param $id
 *
 * @return void
 */
function notificationRoute($type, $id)
{
    if (isset($type)) {
        if (($type == 'registerUser' || $type == 'createUser') && !empty($id)) {
            return route('admin.user.show', $id);
        }
        if ($type == 'createBasicUdi') {
            return route('admin.basicudi.index');
        }
        if (($type == 'createClient' || $type == 'registerClient') && !empty($id)) {
            return route('admin.client.show', $id);
        }
        if ($type == 'createFsn') {
            return route('admin.fsn');
        }
        if ($type == 'createRole') {
            return route('admin.role.index');
        }
        if ($type == 'createStaff') {
            return route('admin.staff.index');
        }
        if (($type == 'createProduct' || $type == 'productRating' || $type == 'productAnswered' || $type == 'scanProduct') && !empty($id)) {
            return auth()->user()->user_type == User::TYPE_CLIENT ? route('client.product.show', $id) : route('admin.product.show', $id);
        }
        return 'javascript:void(0)';
    }
    return 'javascript:void(0)';
}


/**
 * Method removeBracketsFromUdiNumber
 *
 * @param string $udiNumber [explicite description]
 *
 * @return void
 */
function removeBracketsFromUdiNumber($udiNumber)
{
    return str_replace(array( '(', ')' ), '', $udiNumber);
}

/**
 * Method getCountryIdByName
 *
 * @param $countryName $countryName [explicite description]
 *
 * @return int
 */
function getCountryIdByName($countryName) 
{
    $country = Country::where('name', $countryName)->first();
    return (!empty($country)) ? $country->id : null;
}


function normalizeTag(string $value): string
{
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/i', ' ', $value);
    $value = preg_replace('/\s+/', ' ', trim($value));
    return $value;
}