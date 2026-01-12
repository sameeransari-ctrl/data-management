@extends('layouts.admin.app')
@section('title', __('labels.role_n_permission'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.role_n_permission')}}s</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.role_n_permission')}}</li>
                                </ul>
                            </nav>
                            <!-- breadcrumb @e -->
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                    <em class="icon ni ni-more-v"></em>
                                </a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                <form id='resetFormFilter'>
                                    <ul class="nk-block-tools gx-2">
                                        <li>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                    <div class="dot dot-primary d-none" id="role_filter_badge"></div>
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
                                                                    <label class="overline-title overline-title-alt" >{{__('labels.status')}}</label>
                                                                    <select class="form-select js-select2" id='searchStatus' data-placeholder="{{__('labels.select_status')}}" data-minimum-results-for-search="Infinity">
                                                                        <option></option>
                                                                        <option value="1">{{__('labels.active')}}</option>
                                                                        <option value="0">{{__('labels.inactive')}}</option>
                                                                    </select>
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
                                        @can('admin.role.create')
                                        <li>
                                            <button type="button" data-toggle="modal" data-target="#addRole"
                                                class="btn btn-primary addEditRole"
                                                id="addRoleBtn"><em class="icon ni ni-plus"></em>
                                                <span>{{__('labels.add')}} {{__('labels.role_n_permission')}}</span>
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

                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner ">
                            <div class="common-table">
                                <table class="datatable-init nowrap table" id="role-list-table" aria-describedby="role table">
                                    <caption>{{__('labels.role_list')}}</caption>
                                    <thead>
                                        <tr>
                                            <th class="id nk-tb-col-check w_100">{{__('labels.serial_no')}}</th>
                                            <th class="name">{{__('labels.role_name')}}</th>
                                            <th class="status text-center w-250px">{{__('labels.status')}}</th>
                                            <th class="actions nosort nk-tb-col-tools text-center w_100">{{__('labels.action')}}</th>
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

<!-- add and edit role modal @s-->
<div class="modal fade zoom" tabindex="-1" id="addEditRoleModal" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<!-- add and edit role modal @e-->
@endsection
@push('scripts')
<script nonce="{{csp_nonce('script')}}">
    var canEdit = "{{auth()->user()->can('admin.role.edit')}}";
</script>
{!! returnScriptWithNonce(asset_path('assets/js/admin/role/index.js')) !!}
@endpush
