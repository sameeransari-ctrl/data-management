@extends('layouts.client.app')
@section('title',__('labels.my_profile'))
@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card card-bordered">
                    <div class="card-aside-wrap">
                        <div class="card-inner card-inner-lg">
                            <div class="nk-block-head nk-block-head-lg">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">{{__('labels.profile')}}</h4>
                                        <div class="nk-block-des">
                                            <p>{{__('labels.basic_info_like_your_name_and_email_that_you_use_on_bizprospex_platform')}}</p>
                                        </div>
                                    </div>
                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                        <a class="toggle btn btn-icon btn-trigger mt-n1"
                                            data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                    </div>
                                    <div class="nk-block-head-content align-self-center align-self-lg-start ms-0 ms-sm-2">
                                        <a class="btn-primary btn" data-bs-toggle="modal" data-bs-target="#profile-edit"><em class="icon ni ni-edit"></em><span>{{__('labels.update_profile')}}</span></a>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head -->
                            <div class="nk-block">
                                <div class="nk-data data-list">
                                    <div class="data-head">
                                        <h6 class="overline-title">{{__('labels.basics')}}</h6>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.name')}}</span>
                                            <span class="data-value">{{ucwords($clientData->name) ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.mobile')}}</span>
                                            <span class="data-value">{{$clientData->phone_number ? '+'.$clientData->phone_code.' '.$clientData->phone_number: __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.email_address')}}</span>
                                            <span class="data-value">{{$clientData->email ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.address')}}</span>
                                            <span class="data-value">{{$clientData->address ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.country')}}</span>
                                            <span class="data-value">{{$clientData->country->name ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.city')}}</span>
                                            <span class="data-value">{{$clientData->city->name ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.actor_id_srn')}}</span>
                                            <span class="data-value">{{$clientData->actor_id ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div>
                                </div><!-- data-list -->
                            </div><!-- .nk-block -->
                            <div class="nk-block">
                                <div class="nk-data data-list">
                                    <div class="data-head">
                                        <h6 class="overline-title">{{__('labels.bank_detail')}}</h6>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.account_holder_name')}}</span>
                                            <span class="data-value">{{ucwords($clientData->userCard->card_holder_name) ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.account_number')}}</span>
                                            <span class="data-value">{{$clientData->userCard->card_number ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.ifsc_bic_swift_code')}}</span>
                                            <span class="data-value">{{$clientData->userCard->ifsc_code ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.iban_no')}}</span>
                                            <span class="data-value">{{$clientData->userCard->iban_number ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.gtin_optional')}}</span>
                                            <span class="data-value">{{$clientData->userCard->gtin_number ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                    </div>
                                </div><!-- data-list -->
                            </div><!-- .nk-block -->
                        </div>
                        <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg"
                            data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                            <div class="card-inner-group" data-simplebar>
                                <div class="card-inner">
                                    <div class="user-card">
                                        <div class="user-avatar bg-primary imgBox">

                                            <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}"
                                                id="currentProfileImage" class="img-fluid" alt="Not Found">
                                        </div>
                                        <div class="user-info">
                                            <span class="lead-text">{{ucwords($clientData->name) ?? ''}}</span>
                                            <span class="sub-text">{{$clientData->email ?? ''}}</span>
                                        </div>
                                    </div><!-- .user-card -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <ul class="link-list-menu">
                                        <li>
                                            <a href="{{route('client.profile.index')}}" class="@if(basename(request()->path()) == 'profile') active @endif "><em class="icon ni ni-user-fill-c"></em><span>{{__('labels.profile')}}</span></a>
                                            <a href="{{route('client.change-user-password')}}" class="@if(basename(request()->path()) == 'change-user-password') active @endif "><em class="icon ni ni-lock-alt"></em><span>{{__('labels.change_password')}}</span></a>
                                        </li>
                                    </ul>
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- card-aside -->
                    </div><!-- .card-aside-wrap -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>


<!-- Update Profile -->
<div class="modal fade zoom" tabindex="-1" id="profile-edit" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">{{__('labels.update_profile')}}</h5>
            </div>
            <div class="modal-body">
                <form id="updateProfileForm" method="POST" action="{{route('client.update-profile')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-sm-3 g-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="uploadPhoto upload_photo position-relative mx-auto">
                                    <div class="img-box rounded-circle overflow-hidden w-100 h-100 imgBox">
                                        <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" class="img-fluid" alt="user-img"
                                            class="img-fluid rounded-circle border" id="imagePreview">
                                    </div>
                                    <label
                                        class="mb-0 d-flex align-items-center justify-content-center position-absolute rounded-circle overflow-hidden imgBox"
                                        for="uploadImage">
                                        <input type="file" id="uploadImage" class="imgBox customCropperImage"
                                            data-width-height="{{ config('constants.image.profile.dimension') }}"
                                            data-max-size="{{ config('constants.image.profile.maxSize') }}"
                                            data-accept-file="{{ config('constants.image.profile.acceptType') }}"
                                            data-preview-id="imagePreview" data-base64-id="uploadImageBase64"
                                            accept="{{ config('constants.image.profile.acceptType') }}"
                                            data-path={{ config('constants.image.profile.path') }}
                                            data-zoomable="{{ config('constants.image.profile.zoomAble') }}"
                                            data-aspect-ratio="{{ config('constants.image.profile.aspectRatio') }}"
                                            data-crop-box-resizable="{{ config('constants.image.profile.cropBoxResizable') }}"
                                            data-zoomOnWheel="{{ config('constants.image.profile.zoomOnWheel') }}">
                                        <input type="hidden" name="profile_image" id="uploadImageBase64" value="">
                                        <em class="icon ni ni-camera-fill"></em>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('labels.name')}}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="name" class="form-control shadow-none" placeholder="{{__('labels.enter_name')}}" value="{{$clientData->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('labels.email_address')}}</label>
                                <div class="form-control-wrap">
                                    <input type="email" name="email" class="form-control shadow-none" placeholder="{{__('labels.enter_email')}}" value="{{$clientData->email}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group countryCode">
                                <label class="form-label">{{__('labels.mobile')}}</label>
                                <div class="d-flex">
                                    <select name="phone_code" class="form-select js-select2" data-placeholder="" id="countries">
                                        <option></option>
                                        @foreach ($countries as $country)
                                        <option @selected($clientData && $clientData->phone_code == $country->phone_code) value="{{$country->phone_code}}" data-flag="{{ $country->flag_image_url }}" title="{{ $country->name }}">{{'+'.$country->phone_code}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="phone_number" class="form-control shadow-none" placeholder="{{__('labels.enter_mobile_number')}}" value="{{$clientData->phone_number}}" aria-describedby="phone_number-error">
                                </div>
                                <span id="phone_number-error" class="help-block error-help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <div class="form-label-group">
                                   <label class="form-label" for="default-05">{{__('labels.address')}}</label>
                                </div>
                                <div class="form-control-wrap">
                                   <input type="text" name="address" class="form-control shadow-none" id="default-05" placeholder="{{__('labels.enter_address')}}" value="{{$clientData->address}}">
                                </div>
                             </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" >{{__('labels.country')}}</label>
                                <select name="country_id" id="countryId" class="form-select js-select2" data-placeholder="{{__('labels.select_country')}}">
                                    <option></option>
                                    @foreach ($countries as $country)
                                    <option @selected($clientData && $clientData->country_id == $country->id) value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" >{{__('labels.city')}}</label>
                                <select name="city_id" id="cityId" class="form-select js-select2" data-placeholder="{{__('labels.select_city')}}" aria-describedby="city-error">
                                    <option></option>
                                    @foreach ($cities as $city)
                                    <option @selected($clientData && $clientData->city_id == $city->id) value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                                <span id="city-error" class="help-block error-help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="default-12">{{__('labels.actor_id_srn')}}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" name="actor_id" class="form-control shadow-none" id="default-12" placeholder="{{__('labels.enter_actor_id_srn')}}" value="{{$clientData->actor_id}}">
                            </div>
                            </div>
                        </div>
                        <div class="col-12 mt-lg-4 mt-3">
                            <h5>{{__('labels.bank_detail')}}</h5>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="card_holder_name">{{ __('labels.account_holder_name') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none" id="card_holder_name" value="@if(!empty($userCard)){{$userCard->card_holder_name}}@else{{ old('card_holder_name') }}@endif" name="card_holder_name" placeholder="{{ __('labels.enter_account_holder_name') }}">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="card_number">{{ __('labels.account_number') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none" id="card_number" value="@if(!empty($userCard)){{$userCard->card_number}}@else{{ old('card_number') }}@endif" name="card_number" placeholder="{{ __('labels.enter_account_number') }}">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="ifsc_code">{{ __('labels.ifsc_bic_swift_code') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none" id="ifsc_code" value="@if(!empty($userCard)){{$userCard->ifsc_code}}@else{{ old('ifsc_code') }}@endif" name="ifsc_code" placeholder="{{ __('labels.enter_ifsc_bic_swift_code') }}">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="iban_number">{{ __('labels.iban_no') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none" id="iban_number" value="@if(!empty($userCard)){{$userCard->iban_number}}@else{{ old('iban_number') }}@endif" name="iban_number" placeholder="{{ __('labels.enter_iban_no') }}">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="gtin_number">{{ __('labels.gtin_optional') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none" id="gtin_number" value="@if(!empty($userCard)){{$userCard->gtin_number}}@else{{ old('gtin_number') }}@endif" name="gtin_number" placeholder="{{ __('labels.enter_gtin') }}">
                            </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit" id="updateProfileBtn" class="btn btn-primary me-1">{{__('labels.update')}}</button>
                                <button type="button" id="cancelBtn" data-bs-dismiss="modal" class="btn btn btn-light">{{__('labels.cancel')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@include('client.image-cropper-modal')
@endsection
@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Client\UpdateProfileRequest','#updateProfileForm') !!}
{!! returnScriptWithNonce(asset_path('assets/js/client/cropper/image-cropper.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/client/profile/index.js')) !!}
@endpush
