@extends('layouts.admin.app')
@section('title', __('labels.client_management'))
@section('content')
<!-- main header @e -->
<!-- content @s -->
<div class="nk-content ">
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{__('labels.add_client')}}</h3>
                    <nav>
                        <ul class="breadcrumb breadcrumb-pipe">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.client.index')}}">{{__('labels.client_management')}}</a></li>
                        <li class="breadcrumb-item active">{{__('labels.add_client')}}</li>
                        </ul>
                    </nav>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{route('admin.client.index')}}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('labels.back')}}</span></a>
                    <a href="{{route('admin.client.index')}}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                </div><!-- .nk-block-head-content -->
            </div>
            </div>
            <div class="nk-block wide-md mx-auto">
            <div class="card card-preview">
                <div class="card-inner">
                    <form id="clientAddEditForm" action="{{ ($client) ? route('admin.client.update', $client->id) : route('admin.client.store') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if ($client)
                            @method('PUT')
                            <input type="hidden" id="client-id" name="id" value="{{$client->id}}" />
                        @endif
                        <div class="row g-sm-3 g-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="uploadPhoto position-relative mx-auto">
                                        <div class="img-box rounded-circle overflow-hidden w-100 h-100">
                                            <img src="{{ $client->profile_image_url ?? asset('assets/images/default-user.jpg') }}" class="img-fluid" alt="client-img"
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
                                    <label class="form-label">{{ __('labels.name') }}</label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="name" class="form-control shadow-none"
                                            placeholder="{{ __('labels.enter_name') }}" value="@if(!empty($client)){{$client->name}}@else{{ old('name') }}@endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('labels.email') }}</label>
                                    <div class="form-control-wrap">
                                        <input type="email" name="email" class="form-control shadow-none"
                                            placeholder="{{ __('labels.enter_email') }}" value="@if(!empty($client)){{$client->email}}@else{{ old('email') }}@endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group countryCode">
                                    <label class="form-label">{{ __('labels.mobile') }}</label>
                                    <div class="d-flex">
                                        <select class="form-select js-select2" data-placeholder="" name="phone_code" aria-describedby="phone_number-error" id="countries">
                                            @if(isset($countries) && !empty($countries))
                                            @foreach ($countries as $country)
                                            <option {{ ($client && $client->phone_code == $country->phone_code) ? 'selected' : '' }} value="{{ $country->phone_code }}" data-flag="{{ $country->flag_image_url }}" title="{{ $country->name }}">{{'+'.$country->phone_code }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <input type="text" class="form-control shadow-none"
                                            placeholder="{{ __('labels.enter_mobile_number') }}" name="phone_number" value="@if(!empty($client)){{$client->phone_number}}@else{{ old('phone_number') }}@endif" aria-describedby="phone_number-error">
                                    </div>
                                    <span id="phone_number-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" >{{ __('labels.role') }}</label>
                                    <select class="form-select js-select2 form-select2"
                                        data-placeholder="{{ __('labels.select_type') }}" name="client_role_id" aria-describedby="client_type-error">
                                        <option></option>
                                        @foreach ($roles as $role)
                                            <option {{ ($client && $client->client_role_id == $role->id) ? 'selected' : '' }} value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    <span id="client_type-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="actor_id">{{ __('labels.srn_actor_id') }}</label>
                                </div>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control shadow-none" id="actor_id" value="@if(!empty($client)){{$client->actor_id}}@else{{ old('actor_id') }}@endif" name="actor_id" placeholder="{{ __('labels.enter_srn') }}">
                                </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('labels.address') }}</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control shadow-none"
                                            placeholder="{{ __('labels.enter_address') }}" name="address" value="@if(!empty($client)){{$client->address}}@else{{ old('address') }}@endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" >{{ __('labels.country') }}</label>
                                    <select class="form-select js-select2 form-select2"
                                        data-placeholder="{{ __('labels.select_country') }}" data-search="on" name="country_id"
                                        id="countryId" aria-describedby="country_id-error">
                                        <option></option>
                                        @foreach ($countries as $country)
                                            <option {{ ($client && $client->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="country_id-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" >{{ __('labels.city') }}</label>
                                    <select class="form-select js-select2 form-select2"
                                        data-placeholder="{{ __('labels.select_city') }}" data-search="on" name="city_id"
                                        id="cityId" aria-describedby="city_id-error">
                                        @if(isset($cities) && !empty($cities))
                                        @foreach ($cities as $city)
                                            <option {{ ($client && $client->city_id == $city->id) ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span id="city_id-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password42">{{ __('labels.password') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a class="form-icon form-icon-right passcode-switch" data-target="password">
                                            <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control shadow-none" id="password" name="password" placeholder="{{ __('labels.enter_password') }}" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="confirm_password">{{ __('labels.confirm_password') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a class="form-icon form-icon-right passcode-switch" data-target="confirm_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                        </a>
                                        <input type="password" class="form-control shadow-none" id="confirm_password" aria-describedby="confirm-password-error" name="confirm_password" placeholder="{{ __('labels.enter_confirm_password') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-lg-4 mt-3">
                                <h5>{{ __('labels.bank_detail') }}</h5>
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
                                    <button type="button" class="btn btn-primary me-1"
                                    id="clientSubmitBtn">{{ __('labels.save') }}</button>
                                    <a href="{{route('admin.client.index')}}" class="btn btn btn-light">{{ __('labels.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- content @e -->
<!-- footer @s -->
@include('admin.image-cropper-modal')
@endsection
@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Admin\ClientRequest', '#clientAddEditForm') !!}

{!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/client/index.js')) !!}
@endpush

