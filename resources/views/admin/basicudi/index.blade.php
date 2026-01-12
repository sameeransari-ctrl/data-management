@extends('layouts.admin.app')
@section('title', __('labels.basic_udi'))
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
                                <h3 class="nk-block-title page-title">{{__('labels.basic_udi')}}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{__('labels.basic_udi')}}</li>
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
                                            <li><button type="button" id="import-basic-udi" class="btn btn-dim btn-outline-primary px-btn"><em class="icon ni ni-upload-cloud"></em><span>{{__('labels.import')}}</span></button></li>
                                            <li><a download href="{{getImageUrl('basicudi-sample.xlsx', '', false)}}" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.sample_file_download')}}</span></a></li>
                                            <li><a class="btn btn-white btn-outline-light exportCsv"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.export')}}</span></a></li>
                                            <li>
                                                <button type="button" data-toggle="modal" data-target="#addUdi" class="btn btn-primary addEditUdi" id="addUdiBtn">
                                                    <em class="icon ni ni-plus"></em><span>
                                                    {{__('labels.add')}}</span>
                                                </button>
                                            </li>
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
                                    <table class="datatable-init nowrap table" id="udi-list-table">
                                        <caption>{{ __('labels.basic_udid_list') }}</caption>
                                        <thead>
                                            <tr>
                                                <th class="nosort nk-tb-col-tools id">{{__('labels.serial_no')}}</th>
                                                <th class="name">{{__('labels.basic_udi_no')}}</th>
                                                <th class="nosort clientName">{{__('labels.client_name')}}</th>
                                                <th class="nosort actor_id">{{__('labels.srn_actor_id')}}</th>
                                                <th class="nosort nk-tb-col-tools text-center actions w_100">{{__('labels.action')}}</th>
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

    <div class="modal fade zoom" tabindex="-1" id="import-basic-udi-div" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
              <a class="close" data-bs-dismiss="modal" aria-label="Close">
              <em class="icon ni ni-cross"></em>
              </a>
              <div class="modal-header">
                 <h5 class="modal-title">{{__('labels.import_basic_udi')}}</h5>
              </div>
              <div class="modal-body">
                 <!-- content here -->
              </div>
           </div>
        </div>
     </div>

    <!-- content @e -->
    <div class="modal fade" id="addUdi" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    </div>
    @endsection
    @push('scripts')
    {!! returnScriptWithNonce(asset_path('assets/js/admin/basicudi/index.js')) !!}
    @endpush
