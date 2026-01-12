@extends('layouts.admin.app')
@section('title',__('labels.login'))
@section('content')

<div class="nk-content ">
    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
        <div class="brand-logo pb-4 text-center">
            <a href="{{route('admin.login')}}" class="logo-link">
                <x-logo logoClass="logo-dark"></x-logo>
            </a>
        </div>
        <div class="card">
            <div class="card-inner card-inner-lg">
                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">{{__('labels.login')}}</h4>
                        <div class="nk-block-des">
                            <p>{{__('labels.access_the_bizprospex_panel_using_your_email_and_password')}}</p>
                        </div>
                    </div>
                </div>
                <form action="{{route('admin.otp.verify')}}" method="post" id="admin-otp-login-form" onsubmit="return false;">
                    @csrf
                    <div class="form-group">
                        <div class="form-label-group">
                            <label class="form-label" for="otp">{{__('labels.otp')}}</label>
                            <a id="resentBtn" class="link link-primary link-sm" data-href="{{$resendOtpUrl}}">{{__('labels.resent_otp')}}</a>
                        </div>
                        <div class="form-control-wrap">
                        <input type="hidden" name="email" value="{{ $email }}" id="email" >
                            <input type="text" class="form-control form-control-lg shadow-none" id="otp" name="otp" placeholder="{{__('labels.enter_your_otp')}}" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="signInBtn" class="btn btn-lg btn-primary btn-block">{{__('labels.login')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Admin\VerifyOtpRequest','#admin-otp-login-form') !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/auth/otp.js')) !!}
@endpush
