@extends('layouts.client.app')
@section('title', __('labels.product_management'))
@section('content')
    <!-- content @s -->
    <div class="nk-content product-page">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{ __('labels.product_management') }}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('client.dashboard') }}">{{ __('labels.dashboard') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{ __('labels.product_management') }}</li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-md-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <form id='resetFormFilter'>
                                        <ul class="nk-block-tools g-2 flex-wrap">
                                            <li>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                        <div class="dot dot-primary d-none" id="product_filter_badge"></div>
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
                                                                        <label class="overline-title overline-title-alt">{{__('labels.class')}}</label>
                                                                        <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.class')}}" id="class_id">
                                                                            <option></option>
                                                                        @if(!empty($productClassList))
                                                                            @foreach($productClassList as $value)
                                                                                <option value="{{ $value->id }}">{{ ucfirst($value->name) }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt">{{__('labels.verification')}}</label>
                                                                        <select class="form-select js-select2" id="verification_by" data-placeholder="{{__('labels.select')}} {{__('labels.verification')}}">
                                                                            <option></option>
                                                                            @if(!empty($verificationList))
                                                                                @foreach($verificationList as $key => $value)
                                                                                    <option value="{{ $key }}">{{ ucfirst($value) }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-foot between">
                                                            <a class="clickable cursor-pointer" id="resetFilter">{{__('labels.reset_filter')}}</a>
                                                            <a class="btn btn-primary" id="searchFilter">{{__('labels.filter')}}</a>
                                                        </div>
                                                    </div><!-- .filter-wg -->
                                                </div>
                                            </li>
                                            <li><button type="button" id="import-product" class="btn btn-dim btn-outline-primary px-btn"><em class="icon ni ni-upload-cloud"></em><span>{{__('labels.import')}}</span></button></li>
                                            <li><a download href="{{getImageUrl('product-sample.xlsx', '', false)}}" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.sample_file_download')}}</span></a></li>
                                            <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                            <li><a href="{{route('client.product.create')}}" class="btn btn-primary" ><em class="icon ni ni-plus"></em><span>{{__('labels.add_product')}}</span></a></li>
                                        </ul>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table" id="product_table" aria-describedby="product table">
                                    <caption>{{__('labels.product_list')}}</caption>
                                    <thead>
                                        <tr>
                                            <th class="w_70 id">{{__('labels.serial_no')}}</th>
                                            <th class="product_image">{{__('labels.product_image')}}</th>
                                            <th class="udi_number">{{__('labels.udi_no')}}</th>
                                            <th class="product_name">{{__('labels.product_name')}}</th>
                                            <th class="class_name">{{__('labels.class')}}</th>
                                            <th class="verification_by">{{__('labels.verification')}}</th>
                                            <th class="total_scan_count">{{__('labels.total_scan_count')}}</th>
                                            <th class="nosort nk-tb-col-tools actions">{{__('labels.action')}}</th>
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

<div class="modal fade zoom" tabindex="-1" id="import-product-div" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <a class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
        </a>
        <div class="modal-header">
            <h5 class="modal-title">{{__('labels.import_product')}}</h5>
        </div>
        <div class="modal-body">
            <!-- content here -->
        </div>
    </div>
    </div>
</div>
    <!-- content @e -->
@endsection
@push('scripts')
    {!! returnScriptWithNonce(asset_path('assets/js/client/product/index.js')) !!}
@endpush
