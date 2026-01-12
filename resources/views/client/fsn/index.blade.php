@extends('layouts.client.app')
@section('title', __('labels.field_safety_notice'))
@section('content')
    <!-- content @s
        -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{ __('labels.field_safety_notice') }}</h3>
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('client.dashboard') }}">{{ __('labels.dashboard') }}</a>
                                            </li>
                                            <li class="breadcrumb-item active">{{ __('labels.field_safety_notice') }}</li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                            data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools gx-2">
                                                <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                    <button type="button" data-toggle="modal" data-target="#addFsn"
                                                        class="btn btn-primary addEditPreviewFsn" id="addFsnBtn">
                                                        <em class="icon ni ni-plus"></em><span>
                                                            {{ __('labels.add_fsn') }}</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block nk-block-lg">
                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="datatable-init nowrap table" id="fsn-list-table">
                                        <caption>{{__('labels.fsn_list')}}</caption>
                                        <thead>
                                            <tr>
                                                <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                                <th class="title">{{__('labels.title')}}</th>
                                                <th class="udi_number">{{__('labels.udi_no')}}</th>
                                                <th class="nosort created_at">{{__('labels.date')}}</th>
                                                <th class="product_name">{{__('labels.product_name')}}</th>
                                                <th class="attachment_type">{{__('labels.attach_type')}}</th>
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
    <div class="modal fade" id="addFsn" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"></div>
    <div class="modal fade" id="viewFsn" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    @endsection
    @push('scripts')
    {!! returnScriptWithNonce(asset_path('assets/js/client/fsn/index.js')) !!}
    @endpush
