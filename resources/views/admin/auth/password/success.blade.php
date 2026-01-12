@extends('layouts.admin.app')
@section('title',__('labels.reset_password_email_sent'))
@section('content')

    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="brand-logo pb-4 text-center">
                            <a href="{{ route('admin.login') }}" class="logo-link">
                                <x-logo logoClass="logo-dark"></x-logo>
                            </a>
                        </div>
                        <div class="card border-0 shadow-none">
                            <div class="card-inner card-inner-lg">

                                <div class="nk-block-head pb-0">
                                    <div class="nk-block-head-content text-center">
                                        <h4 class="nk-block-title">{{__('labels.thank_you_for_submitting_form')}}</h4>
                                        <div class="nk-block-des">
                                            <p class="mb-0">{{__('labels.password_reset_instructions_will_be_sent_to_the_registered_email')}}</p>
                                            <p>{{__('labels.please_check_your_mail')}}</p>
                                        </div>
                                    </div>
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
