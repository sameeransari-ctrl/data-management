@extends('layouts.client.app')
@section('title',__('labels.change_password'))
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
                                        <h4 class="nk-block-title">{{__('labels.change_password')}}</h4>
                                    </div>
                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                        <a href="javascript:void(0);"
                                            class="toggle btn btn-icon btn-trigger mt-n1"
                                            data-target="userAside"><em
                                                class="icon ni ni-menu-alt-r"></em></a>
                                    </div>
                                </div>
                            </div>
                            <!-- .nk-block-head -->
                            <div class="nk-block">
                                <div class="between-center flex-wrap g-3">
                                    <div class="nk-block-text">
                                        <h6>{{__('labels.change_password')}}</h6>
                                        <p>{{__('labels.set_a_unique_password_to_protect_your_account')}}</p>
                                    </div>
                                    <div class="nk-block-actions flex-shrink-sm-0">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                            <li class="order-md-last">
                                                <a class="cursor-pointer btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePassword">{{__('labels.change_password')}}</a>
                                            </li>
                                            <li>
                                                <em class="text-soft text-date fs-12px">
                                                    {{__('labels.last_changed')}} : <span>{{$userData->change_password_at != null ? convertDateToTz($userData->change_password_at, 'd M Y, h:i A') : '--'}} </span>
                                                </em>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- .nk-block -->
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
                                            <span class="lead-text">{{ucwords($userData->name) ?? ''}}</span>
                                            <span class="sub-text">{{$userData->email ?? ''}}</span>
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
@endsection
