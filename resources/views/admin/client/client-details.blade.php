@extends('layouts.admin.app')
@section('title', __('labels.client_management'))
@section('content')
<!-- content @s -->
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.client_detail')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('admin.client.index')}}">{{__('labels.client_management')}}</a></li>
                                    <li class="breadcrumb-item active">{{__('labels.client_detail')}}</li>
                                </ul>
                            </nav>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                    <em class="icon ni ni-more-v"></em>
                                </a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <a href="{{route('admin.client.index')}}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                                                <em class="icon ni ni-arrow-left"></em>
                                                <span>{{__('labels.back')}}</span>
                                            </a>
                                            <a href="{{route('admin.client.index')}}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none">
                                                <em class="icon ni ni-arrow-left"></em>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block aside-menu">
                    <div class="card">
                        <div class="card-aside-wrap">
                            <div class="card-content overflow-hidden">
                                <div class="text-end">
                                <a href="#" class="toggle btn btn-icon btn-trigger d-xxl-none " data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
                                </div>
                                <div class="card-inner">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <h5 class="title">{{__('labels.bank_detail')}}</h5>
                                        </div><!-- .nk-block-head -->
                                        <div class="profile-ud-list">
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label w-140 d-inline-block">{{__('labels.account_holder_name')}}</span>
                                                    <span class="profile-ud-value">{{ucwords($userCard->card_holder_name) ?? '---'}}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label w-140 d-inline-block">{{__('labels.account_number')}}</span>
                                                    <span class="profile-ud-value">{{$userCard->card_number ?? '---'}}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label w-140 d-inline-block">{{__('labels.ifsc_bic_swift_code')}}</span>
                                                    <span class="profile-ud-value"> {{$userCard->ifsc_code ?? '---'}}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">{{__('labels.iban_no')}}</span>
                                                    <span class="profile-ud-value"> {{$userCard->iban_number ?? '---'}}</span>
                                                </div>
                                            </div>
                                            <div class="profile-ud-item">
                                                <div class="profile-ud wider">
                                                    <span class="profile-ud-label">{{__('labels.gtin_optional')}}</span>
                                                    <span class="profile-ud-value"> {{$userCard->gtin_number ?? '---'}}</span>
                                                </div>
                                            </div>
                                        </div><!-- .profile-ud-list -->
                                    </div><!-- .nk-block -->
                                    <div class="nk-divider divider mx-n4"></div>
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <h5 class="title">{{__('labels.fsn_notice_list')}}</h5>
                                        </div><!-- .nk-block-head -->
                                        <table class="datatable-init nowrap table" id="fsn-list-table">
                                            <caption>{{ __('labels.client_fsn_list') }}</caption>
                                            <thead>
                                                <tr>
                                                    <th class="nosort id w_100 nk-tb-col-tools">{{__('labels.serial_no')}}</th>
                                                    <th class="nosort title">{{__('labels.title')}}</th>
                                                    <th class="nosort udi_number">{{__('labels.udi_no')}}</th>
                                                    <th class="nosort created_at">{{__('labels.date')}}</th>
                                                    <th class="nosort product_name">{{__('labels.product_name')}}</th>
                                                    <th class="nosort attachment_type">{{__('labels.attach_type')}}</th>
                                                    <th class="nosort actions nk-tb-col-tools text-center w_100">{{__('labels.action')}}</th>
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
                                               <img src="{{ $client->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" alt="user-img">
                                            </div>
                                            <div class="user-info">
                                                <h5 class="client-id" data-client-id="{{$client->id}}">{{ucwords($client->name)}}</h5>
                                                <span class="sub-text">{{$client->email}}</span>
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="card-inner">
                                        <h6 class="overline-title-alt mb-2">{{__('labels.information')}}</h6>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.actor_id_srn')}}</span>
                                                <span>{{$client->actor_id}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.role')}}</span>
                                                <span>{{$role['name']}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.uploaded_product')}}</span>
                                                <span>{{$productCount}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.fsn_notice')}}</span>
                                                <span>{{$fsnCount}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.mobile')}}</span>
                                                <span>
                                                @if ($client->phone_code !== null && $client->phone_code !== 'undefined')
                                                  {{'+'.$client->phone_code.' '.$client->phone_number}}
                                                @else
                                                  {{$client->phone_number}}
                                                @endif
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.country')}}</span>
                                                <span>{{$client->country->name ?? '---'}}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">{{__('labels.city')}}</span>
                                                <span>{{$client->city->name ?? '---'}}</span>
                                            </div>
                                            <div class="col-12">
                                                <span class="sub-text">{{__('labels.address')}}</span>
                                                <span>{{ $client->address ?? '---' }}</span>
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-aside -->
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
                <!-- second tab starts -->
            </div>
        </div>
    </div>
</div>
    <!-- content @e -->
<div class="modal fade" id="fsn-show" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/client/client-details.js')) !!}
@endpush
