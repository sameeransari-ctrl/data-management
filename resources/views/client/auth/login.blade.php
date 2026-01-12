@extends('layouts.client.app')
@section('title',__('labels.login'))
@section('content')

<div class="nk-content ">
    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
        <div class="brand-logo pb-4 text-center">
            <a href="{{route('client.login')}}" class="logo-link">
                <x-logo logoClass="logo-dark"></x-logo>
            </a>
        </div>
        <div class="card">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">{{ __('labels.login') }}</h4>
                        <div class="nk-block-des">
                            <p>{{ __('labels.access_the_bizprospex_panel_using_your_email_and_password') }}</p>
                        </div>
                    </div>
                </div>
                <form action="{{route('client.login.submit')}}" method="post" id="client-login-form" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="timezone" id="timezone">
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="email">{{ __('labels.email') }}</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg shadow-none" id="email" name="email" placeholder="{{ __('labels.enter_your_email_address') }}" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="password">{{ __('labels.password') }}</label>
                        </div>
                        <div class="form-control-wrap">
                            <a href="#"
                                class="form-icon form-icon-right passcode-switch lg"
                                data-target="password">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" class="form-control form-control-lg shadow-none" id="password" name="password" placeholder="{{ __('labels.enter_your_password') }}" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <div class="custom-control custom-control-sm custom-checkbox">
                                <input type="checkbox" class="custom-control-input shadow-none" name="remember" id="remember" value="1">
                                <label class="custom-control-label" for="remember">{{ __('labels.stay_logged_in') }}</label>
                            </div>
                            <a class="link link-primary link-sm" href="{{route('client.forgot-password')}}">{{ __('labels.forgot_password') }}?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="signInBtn" class="btn btn-lg btn-primary btn-block">{{ __('labels.login') }}</button>
                    </div>
                    <div class="form-note-s2 text-center"><a href="{{route('client.register')}}">{{ __('labels.create_an_account') }}</a></div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Client\LoginRequest','#client-login-form') !!}
{!! returnScriptWithNonce(asset_path('assets/js/client/auth/login.js')) !!}
@endpush
