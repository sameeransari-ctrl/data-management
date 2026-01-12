@extends('layouts.client.app')
@section('title', __('labels.notification'))
@section('content')
<!-- content @s -->
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.notification')}}</h3>
                                <nav>
                                    <ul class="breadcrumb breadcrumb-arrow">
                                        <li class="breadcrumb-item">
                                            <a href="{{route('client.dashboard')}}">{{__('labels.dashboard')}}</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{__('labels.notification')}}</li>
                                    </ul>
                                </nav>
                        </div>
                    </div>
                </div>
                <div class="nk-block ">
                    <div class="card card-preview">
                        <div class="card-inner">
                            <table class="datatable-init nowrap table" id="notification-table">
                                <caption>{{__('labels.notification_list')}}</caption>
                                <thead>
                                    <tr>
                                        <th class="w_70 nk-tb-col-tools nosort id">{{__('labels.serial_no')}}</th>
                                        <th class="message">{{__('labels.notification')}}</th>
                                        <th class="datetime">{{__('labels.date_time')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9">
                                            <div id="loaderTb"></div>
                                        </td>
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

<!-- readMore modal @s-->
<div class="modal fade" tabindex="-1" id="readMoreModal" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a class="close custom-close cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title" id="heading"></h5>
            </div>
            <div class="modal-body">
                <p id="notificationReadMoreData" class="text-break"></p>
            </div>
        </div>
    </div>
</div>
<!-- readMore modal @e-->
<!-- content @e -->
@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/client/notification/notification.js')) !!}
@endpush
