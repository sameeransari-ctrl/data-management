@extends('layouts.client.app')
@section('title',__('labels.register'))
@section('content')

    <div class="nk-content ">
        <div class="nk-block mx-auto px-3 wide-md">
            <div class="brand-logo pb-4 text-center">
                <a href="{{ route('client.register') }}" class="logo-link">
                    <x-logo logoClass="logo-dark"></x-logo>
                </a>
            </div>
            <div class="card">
                <div class="card-inner card-inner-lg">
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title">{{ __('labels.registration_and_payment_details') }}</h4>
                        </div>
                    </div>
                    <form action="{{route('client.register.submit')}}" method="post" id="client-register-form" onsubmit="return false;">
                        @csrf
                        <div class="row g-sm-3 g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="name">{{ __('labels.name') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="name" class="form-control shadow-none"
                                            id="name" placeholder="{{ __('labels.enter_your_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="email">{{ __('labels.email') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="email" name="email" class="form-control shadow-none"
                                            id="email" placeholder="{{ __('labels.enter_your_email') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group countryCode">
                                    <label class="form-label">{{ __('labels.mobile') }}</label>
                                    <div class="d-flex">
                                        <select name="phone_code" class="form-select js-select2" data-placeholder="" data-ui="lg" id="countries">
                                            @if (isset($countries) && !empty($countries))
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->phone_code }}" data-flag="{{ $country->flag_image_url }}" title="{{ $country->name }}">{{'+'.$country->phone_code }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="text" name="phone_number" class="form-control shadow-none"
                                            placeholder="{{ __('labels.enter_mobile_number') }}" aria-describedby="phone_number-error">
                                    </div>
                                    <span id="phone_number-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('labels.role') }}</label>
                                    <select name="client_role_id" class="form-select js-select2"
                                        data-placeholder="{{ __('labels.select_role') }}"
                                        data-minimum-results-for-search="Infinity" aria-describedby="client_role_id-error">
                                        <option></option>
                                        @if (isset($clientRoles) && !empty($clientRoles))
                                            @foreach ($clientRoles as $clientRole)
                                                <option value="{{ $clientRole->id }}">{{ $clientRole->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="client_role_id-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="address">{{ __('labels.address') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="address" class="form-control  shadow-none"
                                            id="address" placeholder="{{ __('labels.enter_address') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('labels.country') }}</label>
                                    <select name="country_id" class="form-select js-select2 form-select2" data-placeholder="{{ __('labels.select_country') }}" name="country_id" id="countryId"  aria-describedby="country_id-error">
                                        <option></option>
                                        @if (isset($countries) && !empty($countries))
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="country_id-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('labels.city') }}</label>
                                    <select name="city_id" class="form-select js-select2"
                                        data-placeholder="{{ __('labels.select_city') }}" name="city_id" id="cityId" aria-describedby="city_id-error">
                                    </select>
                                    <span id="city_id-error" class="help-block error-help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group registration">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password">{{ __('labels.password') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a class="form-icon form-icon-right passcode-switch lg"
                                            data-target="password">
                                            <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                        </a>
                                        <input type="password" name="password" class="form-control  shadow-none"
                                            id="password" placeholder="{{ __('labels.enter_password') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group registration">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="confirm_password">{{ __('labels.confirm_password') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a class="form-icon form-icon-right passcode-switch lg"
                                            data-target="confirm_password">
                                            <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                        </a>
                                        <input type="password" name="confirm_password" class="form-control  shadow-none"
                                            id="confirm_password" placeholder="{{ __('labels.enter_confirm_password') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="actor_id">{{ __('labels.actor_id_srn') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="actor_id" class="form-control  shadow-none"
                                            id="actor_id" placeholder="{{ __('labels.enter_actor_id_srn') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-xl-4 mt-3 g-sm-3 g-2">
                            <h5>{{ __('labels.bank_detail') }}</h5>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="card_holder_name">{{ __('labels.account_holder_name') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="card_holder_name" class="form-control  shadow-none"
                                            id="card_holder_name" placeholder="{{ __('labels.enter_account_holder_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="card_number">{{ __('labels.account_number') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="card_number" class="form-control  shadow-none"
                                            id="card_number" placeholder="{{ __('labels.enter_account_number') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="ifsc_code">{{ __('labels.ifsc_bic_swift_code') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="ifsc_code" class="form-control  shadow-none"
                                            id="ifsc_code" placeholder="{{ __('labels.enter_ifsc_bic_swift_code') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="iban_number">{{ __('labels.iban_no') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="iban_number" class="form-control  shadow-none"
                                            id="iban_number" placeholder="{{ __('labels.enter_iban_no') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label"
                                            for="default-13">{{ __('labels.gtin_optional') }}</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="gtin_number" class="form-control  shadow-none"
                                        id="gtin_number" placeholder="{{ __('labels.enter_gtin') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-sm-end justify-content-center mt-4">
                            {{-- <a class="btn btn-lg btn-primary btn-inline-block me-2" id="registerBtn">{{ __('labels.register') }}</a> --}}
                            <button type="button" class="btn btn-primary me-1 btn-lg"
                                id="registerBtn">{{ __('labels.register') }}</button>
                            <a href="{{route('client.login')}}" id="cancelBtn" class="btn btn-lg btn btn-light btn-inline-block">{{ __('labels.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\Client\RegisterRequest', '#client-register-form') !!}
    {!! returnScriptWithNonce(asset_path('assets/js/client/auth/register.js')) !!}
@endpush
