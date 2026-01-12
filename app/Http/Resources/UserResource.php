<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Method toArray
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public function toArray($request)
    {
        /**
         * User
         *
         * @var User $user
         */
        $user = Auth::user();

        return [
            'id' => $this->id,
            'name' => ucwords($this->name),
            'address' => $this->address,
            'email' => $this->email,
            'user_role_type' => $this->user_type,
            'user_type' => count($this->roles) > 0 ? ucwords($this->roles->pluck('name')[0]) : '-',
            'phone_code' => $this->phone_code,
            'phone_number' => $this->phone_number,
            'zip_code' => $this->zip_code,
            'profile_image' => $this->profile_image_url,
            'is_profile_completed' => $this->is_profile_completed,
            'notification_alert' => $this->notification_alert,
            'status' => $this->status,
            'created_at' => getConvertedDate($this->created_at, ''),
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'country' => !empty($this->country) ? $this->country->name : '-',
            'city' => !empty($this->city) ? $this->city->name : '-',
            'role' => !empty($this->clientRole) ? $this->clientRole->name : '-',
            'product_count' => count($this->product),
            $this->mergeWhen(
                $user && $user->id === $this->id,
                [
                    'is_email_verified' => $this->isEmailVerified(),
                    'is_phone_number_verified' => $this->isPhoneNumberVerified(),
                    'token' => $this->currentAccessToken(),
                ]
            ),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'about_us_url' => config('constants.cms.about_us'),
                'privacy_url' => config('constants.cms.privacy_policy'),
                'terms_url' => config('constants.cms.terms_condition'),
            ],
        ];
    }
}
