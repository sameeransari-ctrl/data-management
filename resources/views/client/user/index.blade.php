@extends('layouts.client.app')
@section('title', __('labels.user_management'))
@section('content')
    <!-- content @s -->
    <div class="nk-content user-management">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{ __('labels.user_management') }}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('client.dashboard') }}">{{ __('labels.dashboard') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{ __('labels.user_list') }}</li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools gx-3 gy-2 flex-wrap">
                                            <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table" id="user-list-table">
                                    <caption>{{__('labels.user_list')}}</caption>
                                    <thead>
                                        <tr>
                                            <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                            <th class="name">{{__('labels.name')}}</th>
                                            <th class="nosort phone_number">{{__('labels.mobile')}}</th>
                                            <th class="nosort address w-min-200px">{{__('labels.address')}}</th>
                                            <th class="nosort country">{{__('labels.country')}}</th>
                                            <th class="nosort city">{{__('labels.city')}}</th>
                                            <th class="nosort nk-tb-col-tools text-center W_100 actions">{{__('labels.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="9">
                                                <div id="loaderTb"></div>
                                            </td>
                                            <td class="d-none"></td>
                                            <td class="d-none"></td>
                                            <td class="d-none w-min-300px white_spaceWrap"></td>
                                            <td class="d-none"></td>
                                            <td class="d-none"></td>
                                            <td class="d-none text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content @e -->
@endsection
@push('scripts')
    {!! returnScriptWithNonce(asset_path('assets/js/client/user/index.js')) !!}
@endpush
