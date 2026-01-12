@extends('layouts.admin.app')
@section('title', __('labels.user_management'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('labels.user_management')}}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{__('labels.user_management')}}</li>
                                    </ul>
                                </nav>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                        <em class="icon ni ni-more-v"></em>
                                    </a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                    <form id='resetFormFilter'>
                                        <ul class="nk-block-tools gx-3 gy-2 flex-wrap">
                                            <li>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                        <div class="dot dot-primary d-none" id="user_filter_badge"></div>
                                                        <em class="icon ni ni-filter-alt"></em>
                                                    </a>
                                                    <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-end">
                                                        <div class="dropdown-head">
                                                            <span class="sub-title dropdown-title">{{__('labels.filter')}}</span>
                                                        </div>
                                                        <div class="dropdown-body dropdown-body-rg">
                                                            <div class="row g-sm-3 g-2">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt" >{{__('labels.status')}}</label>
                                                                        <select class="form-select js-select2" id='searchStatus' data-placeholder="{{__('labels.select_status')}}" data-minimum-results-for-search="Infinity">
                                                                            <option></option>
                                                                            @foreach ($statusList as $key => $label)
                                                                                <option value="{{ $label }}">{{ ucfirst($label) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt" >{{__('labels.user_type')}}</label>
                                                                        <select class="form-select js-select2" id='searchType' data-placeholder="{{__('labels.select_type')}}" data-minimum-results-for-search="Infinity">
                                                                            <option></option>
                                                                            @foreach ($userTypeList as $key => $label)
                                                                                <option value="{{ $label }}">{{ ucfirst($label) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt">{{__('labels.registration_date_duration')}}</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="input-group">
                                                                                <input type="text" id="fromDate" class="form-control shadow-none date-picker" placeholder="{{__('labels.enter_start_date')}}" data-date-format="yyyy-mm-dd">
                                                                                <div class="input-group-addon">{{__('labels.to')}}</div>
                                                                                <input type="text" id="toDate" class="form-control shadow-none date-picker" placeholder="{{__('labels.enter_end_date')}}" data-date-format="yyyy-mm-dd">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-foot between">
                                                            <a class="clickable cursor-pointer" id="resetFilter">{{__('labels.reset_filter')}}</a>
                                                            <a class="btn btn-primary btn-sm" id="searchFilter">{{__('labels.filter')}}</a>
                                                        </div>
                                                    </div><!-- .filter-wg -->
                                                </div>
                                            </li>
                                            <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                            @can('admin.user.create')
                                            <li>
                                                <button type="button" data-toggle="modal" data-target="#addUser" class="btn btn-primary addEditUser" id="addUserBtn">
                                                    <em class="icon ni ni-plus"></em><span>
                                                    {{__('labels.add_user')}}</span>
                                                </button>
                                            </li>
                                            @endcan
                                        </ul>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner ">
                                <div class="common-table">
                                    <table class="datatable-init nowrap table" id="user-list-table">
                                        <caption>{{__('labels.user_list')}}</caption>
                                        <thead>
                                            <tr>
                                                <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                                <th class="name">{{__('labels.name')}}</th>
                                                <th class="created_at">{{__('labels.registration_date')}}</th>
                                                <th class="nosort address w-min-200px">{{__('labels.address')}}</th>
                                                <th class="nosort country">{{__('labels.country')}}</th>
                                                <th class="nosort city">{{__('labels.city')}}</th>
                                                <th class="nosort user_type">{{__('labels.user_type')}}</th>
                                                <th class="nosort text-center status">{{__('labels.status')}}</th>
                                                <th class="nosort nk-tb-col-tools text-center actions">{{__('labels.action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9">
                                                    <div id="loaderTb"></div>
                                                </td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none w-300px white_spaceWrap"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
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
    </div>

    <!-- content @e -->
    <div class="modal fade" id="addUser" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    </div>
    @include('admin.image-cropper-modal')
    @endsection
    @push('scripts')
    <script nonce="{{csp_nonce('script')}}">
        var canEdit = "{{auth()->user()->can('admin.user.edit')}}";
        var canView = "{{auth()->user()->can('admin.user.show')}}";
    </script>
    {!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
    {!! returnScriptWithNonce(asset_path('assets/js/admin/user/index.js')) !!}
    @endpush
