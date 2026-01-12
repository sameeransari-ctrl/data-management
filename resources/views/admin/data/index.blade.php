@extends('layouts.admin.app')
@section('title', __('labels.data_management'))
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
                                <h3 class="nk-block-title page-title">{{__('labels.data_management')}}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{__('labels.data_management')}}</li>
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
                                                        <div class="dot dot-primary d-none" id="data_filter_badge"></div>
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
                                                                    <label class="overline-title overline-title-alt">{{ __('labels.title') }}</label>
                                                                    <select class="form-select js-select2 select2-hidden-accessible designation-title" data-placeholder="{{ __('labels.select') }} {{ __('labels.title') }}" name="designation[]" multiple="multiple" id="designation-title">
                                                                        <!-- No options needed here; will load via AJAX -->
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="overline-title overline-title-alt">{{ __('labels.company') }}</label>
                                                                    <select class="form-select js-select2 select2-hidden-accessible companies-domain" data-placeholder="{{ __('labels.select') }} {{ __('labels.companies') }}" name="companies_domain[]" multiple="multiple" id="companies-domain">
                                                                    </select>
                                                                </div>
                                                            </div> -->


                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="overline-title overline-title-alt">{{ __('labels.company_websites') }} {{ __('labels.comma_seperated') }}</label>
                                                                    <textarea 
                                                                        id="companies-bulk-input" 
                                                                        class="form-control" 
                                                                        placeholder="Paste comma-separated company websites, e.g. keytravel.com, gavrascenter.com, yps.com.tr"
                                                                        rows="2"
                                                                    ></textarea>
                                                                </div>
                                                            </div>

                                                            

                                                                <!-- <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt">{{__('labels.title')}}</label>
                                                                        <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.title')}}" id="designation_id">
                                                                            <option></option>
                                                                          
                                                                        </select>
                                                                    </div>
                                                                </div> -->
                                                                <div class="col-sm-12">
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
                                                                <!-- <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="overline-title overline-title-alt" >{{__('labels.user_type')}}</label>
                                                                        <select class="form-select js-select2" id='searchType' data-placeholder="{{__('labels.select_type')}}" data-minimum-results-for-search="Infinity">
                                                                            <option></option>
                                                                        </select>
                                                                    </div>
                                                                </div> -->
                                                                <!-- <div class="col-sm-12">
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
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-foot between">
                                                            <a class="clickable cursor-pointer" id="resetFilter">{{__('labels.reset_filter')}}</a>
                                                            <a class="btn btn-primary btn-sm" id="searchFilter">{{__('labels.filter')}}</a>
                                                        </div>
                                                    </div><!-- .filter-wg -->
                                                </div>
                                            </li>
                                            <li><button type="button" id="import-data" class="btn btn-dim btn-outline-primary px-btn"><em class="icon ni ni-upload-cloud"></em><span>{{__('labels.import')}}</span></button></li>
                                            <li><a download href="{{getImageUrl('companies-template.xlsx', '', false)}}" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.template_file_download')}}</span></a></li>
                                            <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                            @can('admin.data.create')
                                            <li>
                                                <button type="button" data-toggle="modal" data-target="#addUser" class="btn btn-primary addEditUser" id="addUserBtn">
                                                    <em class="icon ni ni-plus"></em><span>
                                                    {{__('labels.add_data')}}</span>
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
                                    <table class="datatable-init nowrap table" id="data-list-table">
                                        <caption>{{__('labels.data_list')}}</caption>
                                        <thead>
                                            <tr>
                                                <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                                <th class="company_name">{{__('labels.company_name')}}</th>
                                                <th class="nosort title">{{__('labels.title')}}</th>
                                                <th class="company_website">{{__('labels.company_website')}}</th>
                                                <th class="company_industries">{{__('labels.company_industries')}}</th>
                                                <th class="num_of_employees">{{__('labels.no_of_employees')}}</th>
                                                <th class="company_size">{{__('labels.company_size')}}</th>
                                                <th class="nosort company_address">{{__('labels.company_address')}}</th>
                                                <th class="nosort company_revenue_range">{{__('labels.company_revenue_range')}}</th>
                                                <th class="nosort company_linkedin_url">{{__('labels.company_linkedin_url')}}</th>
                                                <th class="nosort company_phone_number">{{__('labels.company_phone_number')}}</th>
                                                <th class="nosort first_name">{{__('labels.first_name')}}</th>
                                                <th class="nosort last_name">{{__('labels.last_name')}}</th>
                                                <th class="nosort email">{{__('labels.email')}}</th>
                                                <th class="nosort person_linkedin_url">{{__('labels.person_linkedin_url')}}</th>
                                                <th class="nosort source_url">{{__('labels.source_url')}}</th>
                                                <th class="nosort person_location">{{__('labels.person_location')}}</th>
                                                <th class="nosort updated_at">{{__('labels.updated_date')}}</th>
                                                <th class="nosort status">{{__('labels.status')}}</th>
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
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
                                                <td class="d-none"></td>
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
    </div>

    <div class="modal fade zoom" tabindex="-1" id="import-data-div" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
              <a class="close" data-bs-dismiss="modal" aria-label="Close">
              <em class="icon ni ni-cross"></em>
              </a>
              <div class="modal-header">
                 <h5 class="modal-title">{{__('labels.import_data')}}</h5>
              </div>
              <div class="modal-body">
                 <!-- content here -->
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
        var canEdit = "{{auth()->user()->can('admin.data.edit')}}";
        var canView = "{{auth()->user()->can('admin.data.show')}}";
    </script>
    {!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
    {!! returnScriptWithNonce(asset_path('assets/js/admin/data/index.js')) !!}
    @endpush
