@extends('layouts.client.app')
@section('title', __('labels.user_management'))
@section('content')
<!-- content @s -->
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.view_details')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item"><a href="{{route('client.dashboard')}}">{{__('labels.dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('client.user.index')}}">{{__('labels.user_management')}}</a></li>
                                    <li class="breadcrumb-item active">{{__('labels.view_details')}}</li>
                                </ul>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{route('client.user.index')}}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('labels.back')}}</span></a>
                            <a href="{{route('client.user.index')}}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card h-100">
                        <div class="card-aside-wrap">
                            <div class="card-content overflow-hidden">
                                <div class="text-end">
                                <a href="#" class="toggle btn btn-icon btn-trigger d-xxl-none " data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
                                </div>
                                <div class="card-inner">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <h5 class="title">{{__('labels.device_history')}}</h5>
                                        </div><!-- .nk-block-head -->
                                        <table class="datatable-init nowrap table" id="scannedProduct-list-table">
                                            <caption>{{__('labels.scanned_product_list')}}</caption>
                                            <thead>
                                                <tr>
                                                    <th class="nosort id w_100 nk-tb-col-check">{{__('labels.serial_no')}}</th>
                                                    <th class="nosort image">{{__('labels.image')}}</th>
                                                    <th class="nosort udi_number">{{__('labels.udi_no')}}</th>
                                                    <th class="nosort product_name">{{__('labels.product_name')}}</th>
                                                    <th class="nosort date_time">{{__('labels.date_time')}}</th>
                                                    <th class="nosort country">{{__('labels.country')}}</th>
                                                    <th class="nosort city nk-tb-col-tools w_100">{{__('labels.city')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="9">
                                                        <div id="loaderTb"></div>
                                                    </td>
                                                    <td class="d-none"></td>
                                                    <td class="d-none"></td>
                                                    <td class="d-none"></td>
                                                    <td class="d-none"></td>
                                                    <td class="d-none"></td>
                                                    <td class="d-none"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-content -->
                            <div class="card-aside card-aside-right user-aside toggle-slide toggle-slide-right toggle-break-xxl" data-content="userAside" data-toggle-screen="xxl" data-toggle-overlay="true" data-toggle-body="true">
                                <div class="card-inner-group" data-simplebar>
                                    <div class="card-inner">
                                        <div class="user-card user-card-s2">
                                            <div class="user-avatar lg bg-primary">
                                               <img src="{{ $user->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" alt="user-img">
                                            </div>
                                            <div class="user-info">
                                                <h5 class="user-id" data-user-id="{{$user->id}}">{{ucwords($user->name)}}</h5>
                                                <span class="sub-text">{{$user->email}}</span>
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="card-inner">
                                        <h6 class="overline-title-alt mb-2">{{__('labels.personal_information')}}</h6>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.user_type')}}</span>
                                                <span>{{ucfirst($user->user_type.' User')}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.mobile')}}</span>
                                                <span>
                                                @if ($user->phone_code !== null && $user->phone_code !== 'undefined')
                                                  {{'+'.$user->phone_code.' '.$user->phone_number}}
                                                @else
                                                  {{$user->phone_number}}
                                                @endif
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.country')}}</span>
                                                <span>{{$user->country->name ?? '---'}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.city')}}</span>
                                                <span>{{$user->city->name ?? '---'}}</span>
                                            </div>
                                            <div class="col-12">
                                                <span class="sub-text">{{__('labels.address')}}</span>
                                                <span>{{ $user->address ?? '---' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.zip_code')}}</span>
                                                <span>{{ $user->zip_code ?? '---' }}</span>
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-aside -->
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

    <!-- content @e -->
    @endsection
    @push('scripts')
    {!! returnScriptWithNonce(asset_path('assets/js/client/user/user-details.js')) !!}
    @endpush
