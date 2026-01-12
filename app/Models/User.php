<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; //, SoftDeletes;

    public const TYPE_ADMIN = 'admin';

    public const TYPE_USER = 'user';

    public const TYPE_BASIC = 'basic';

    public const TYPE_MEDICAL = 'medical';

    public const TYPE_STAFF = 'staff';

    public const TYPE_CLIENT = 'client';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const VERIFY_TYPE_REGISTER = 'registration';

    public const VERIFY_TYPE_RESET = 'reset-password';

    public const VERIFY_TYPE_OTP = 'otp-verification';

    public const VERIFY_TYPE_LOGIN = 'login';

    public const VERIFY_TYPE_PROFILE = 'profile';

    public static $otpTypes = [
        self::VERIFY_TYPE_REGISTER,
        self::VERIFY_TYPE_RESET,
        self::VERIFY_TYPE_OTP,
        self::VERIFY_TYPE_LOGIN,
        self::VERIFY_TYPE_PROFILE
    ];

    public static $userTypes = [
        self::TYPE_BASIC,
        // self::TYPE_MEDICAL,
    ];

    public static $statusList = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_type',
        'email',
        'email_verified_at',
        'phone_number_verified_at',
        'password',
        'phone_code',
        'phone_number',
        'profile_image',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'latitude',
        'longitude',
        'timezone',
        'otp',
        'otp_expires_at',
        'is_profile_completed',
        'notification_alert',
        'status',
        'last_login_date',
        'change_password_at',
        'client_role_id',
        'actor_id',
        'temp_contact',
        'uniquekey',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    protected $appends = [
        'profile_image_url',
    ];

    protected $attributes = [
        'status' => self::STATUS_INACTIVE,
    ];

    /**
     * Method setPasswordAttribute
     *
     * @param $value $value [explicite description]
     *
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Method getProfileImageUrlAttribute
     *
     * @return string
     */
    public function getProfileImageUrlAttribute()
    {
        if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
            $url = $this->profile_image;
        } else {
            $url = getImageUrl($this->profile_image, 'user');
        }

        return $url;
    }

    /**
     * Method scopeByStatus
     *
     * @param $query  $query  [explicite description]
     * @param $status $status [explicite description]
     *
     * @return void
     */
    public function scopeByStatus($query, $status = null)
    {
        if ($status) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Method scopeNotAdmin
     *
     * @param $query $query [explicite description]
     *
     * @return void
     */
    public function scopeNotAdmin($query)
    {
        return $query->where('user_type', '<>', User::TYPE_ADMIN);
    }

    /**
     * Method scopeOnlyAdmin
     *
     * @param $query $query [explicite description]
     *
     * @return void
     */
    public function scopeOnlyAdmin($query)
    {
        return $query->where('user_type', User::TYPE_ADMIN);
    }

    /**
     * Method isEmailVerified
     *
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Method isPhoneNumberVerified
     *
     * @return bool
     */
    public function isPhoneNumberVerified(): bool
    {
        return !is_null($this->phone_number_verified_at);
    }

    /**
     * Method userCountry
     *
     * @return void
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    /**
     * Method userCity
     *
     * @return void
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Method productScanners
     *
     * @return void
     */
    public function productScanners()
    {
        return $this->hasMany(ProductScanner::class, 'user_id');
    }


    /**
     * Method userCard
     *
     * @return void
     */
    public function userCard()
    {
        return $this->hasOne(UserCard::class, 'user_id');
    }

    /**
     * Method role
     *
     * @return void
     */
    public function clientRole()
    {
        return $this->belongsTo(ClientRole::class, 'client_role_id');
    }

    /**
     * Method product
     *
     * @return void
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'client_id');
    }
    
    /**
     * Method productReview
     *
     * @return void
     */
    public function productReview()
    {
        return $this->hasMany(RatingReview::class, 'review_by');
    }

    /**
     * Method userDevice
     *
     * @return void
     */
    public function userDevice()
    {
        return $this->hasOne(UserDevice::class, 'user_id');
    }
    
    /**
     * Method productAnswer
     *
     * @return void
     */
    public function productAnswer()
    {
        return $this->hasMany(ProductAnswer::class, 'user_id');
    }
    
    /**
     * Method activity
     *
     * @return void
     */
    public function activity()
    {
        return $this->hasOne(UserActivity::class, 'user_id');
    }
}
