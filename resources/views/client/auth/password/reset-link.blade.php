@extends('layouts.client.app')
@section('title',__('labels.forgot_password'))
@section('content')

    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="brand-logo pb-4 text-center">
                            <a href="{{route('client.forgot-password')}}" class="logo-link">
                                <x-logo logoClass="logo-dark"></x-logo>
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title ">{{__('labels.forgot_password')}}</h5>
                                        <div class="nk-block-des ">
                                            <p>{{__('labels.if_you_forgot_your_password_well_then_we_will_email_you_instructions_to_forgot_your_password')}}</p>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('client.forgotPassword') }}" id="submitFrom" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01">{{__('labels.email')}}</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input type="text" name="email" class="form-control form-control-lg shadow-none" id="default-01" placeholder="{{__('labels.enter_your_email_address')}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('labels.submit') }}{{-- Send Reset Link --}}</button>
                                    </div>
                                    {!! JsValidator::formRequest('App\Http\Requests\Client\VerifyEmailRequest','#submitFrom') !!}
                                </form>
                                <div class="form-note-s2 text-center {{-- pt-4 --}}">
                                    <a href="{{route('client.login')}}" class=""><strong>{{ __('labels.return_to_login') }}</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>

@endsection
<!-- app-root @e -->
<!-- JavaScript -->
