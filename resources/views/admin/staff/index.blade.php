@extends('layouts.admin.app')
@section('title', __('labels.staff_management'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.staff_management')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.staff_management')}}</li>
                                </ul>
                            </nav>
                            <!-- breadcrumb @e -->
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                <form id='resetFormFilter'>
                                    <ul class="nk-block-tools gx-3 gy-2 flex-wrap">
                                        <li>
                                            <div class="dropdown">
                                                <a class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                    <div class="dot dot-primary d-none" id="staff_filter_badge"></div>
                                                    <em class="icon ni ni-filter-alt"></em>
                                                </a>
                                                <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-end">
                                                    <div class="dropdown-head">
                                                        <span class="sub-title dropdown-title">{{__('labels.filter')}}</span>
                                                    </div>
                                                    <div class="dropdown-body dropdown-body-rg">
                                                        <div class="row g-sm-3 g-2">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="overline-title overline-title-alt" >{{__('labels.role')}}</label>
                                                                    <select class="form-select js-select2" id='searchType' data-placeholder="{{__('labels.select_role')}}">
                                                                        <option></option>
                                                                        @foreach ($roles as $role)
                                                                            <option value="{{  $role->id }}">{{ ucfirst($role->name) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label class="overline-title overline-title-alt">{{__('labels.status')}}</label>
                                                                    <select class="form-select js-select2" id='searchStatus' data-placeholder="{{__('labels.select_status')}}" data-minimum-results-for-search="Infinity">
                                                                        <option value="">{{__('labels.select_status')}}
                                                                        </option>
                                                                        <option value="{{__('labels.active')}}">{{__('labels.active')}}
                                                                        </option>
                                                                        <option value="{{__('labels.inactive')}}">{{__('labels.inactive')}}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-foot between">
                                                        <a class="clickable cursor-pointer" id="resetFilter">
                                                            {{__('labels.reset_filter')}}
                                                        </a>
                                                        <a id='searchFilter' class="btn btn-sm btn-primary">
                                                            {{__('labels.filter')}}
                                                        </a>
                                                    </div>

                                                </div><!-- .filter-wg -->
                                            </div>
                                        </li>
                                        <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                        @can('admin.staff.create')
                                        <li>
                                            <button type="button" data-toggle="modal" data-target="#addStaffBtn" class="btn btn-primary addEditStaff" id="addStaffBtn">
                                                <em class="icon ni ni-plus"></em><span>{{__('labels.add_staff')}}</span>
                                            </button>
                                        </li>
                                        @endcan
                                    </ul>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner ">
                            <div class="common-table">
                                <table class="datatable-init nowrap table" id="staff_table" aria-describedby="staff table">
                                    <caption>{{__('labels.staff_list')}}</caption>
                                    <thead>
                                        <tr>
                                            <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                            <th class="name">{{__('labels.name')}}</th>
                                            <th class="phone_number">{{__('labels.mobile')}}</th>
                                            <th class="nosort user_type">{{__('labels.role')}}</th>
                                            <th class="nosort status">{{__('labels.status')}}</th>
                                            <th class="nosort nk-tb-col-tools actions text-center w_100">{{__('labels.action')}}</th>
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

<!-- add and edit staff modal @s-->
<div class="modal fade zoom" tabindex="-1" id="addEditStaffModal" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<!-- add and edit staff modal @e-->
@include('admin.image-cropper-modal')
@endsection
@push('scripts')
<script nonce="{{csp_nonce('script')}}">
    var canEdit = "{{auth()->user()->can('admin.staff.edit')}}";
</script>
{!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/staff/index.js')) !!}
@endpush
