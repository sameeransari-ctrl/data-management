@extends('layouts.admin.app')
@section('title', __('labels.fsn'))
@section('content')
<!-- content @s -->
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.field_safety_notice')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.field_safety_notice')}}</li>
                                </ul>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools gx-2">
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
                            <table class="datatable-init nowrap table" id="fsn-list">
                                <caption>{{__('labels.fsn_list')}}</caption>
                                <thead>
                                    <tr>
                                        <th class="nosort id nk-tb-col-tools">{{__('labels.serial_no')}}</th>
                                        <th class="nosort title">{{__('labels.title')}}</th>
                                        <th class="nosort udi_number">{{__('labels.udi_no')}}</th>
                                        <th class="nosort created_at">{{__('labels.date')}}</th>
                                        <th class="nosort product_name">{{__('labels.product_name')}}</th>
                                        <th class="nosort attachment_type">{{__('labels.attach_type')}}</th>
                                        <th class="nosort manufacture">{{__('labels.manufacture')}}</th>
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
<!-- content @e -->
<div class="modal fade" id="fsn-show" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/fsn/index.js')) !!}
@endpush
