@extends('layouts.admin.app')
@section('title',__('labels.faqs'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.faq')}}s</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.faq')}}</li>
                                </ul>
                            </nav>
                            <!-- breadcrumb @e -->
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <button type="button" data-toggle="modal" data-target="#addFAQ"
                                                class="btn btn-primary addEditFaq"
                                                id="addFaqBtn"><em class="icon ni ni-plus"></em>{{__('labels.add_faq')}}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner ">
                            <div class="common-table">
                                <table class="datatable-init nowrap table" id="faq_table" aria-describedby="faq table">
                                    <caption>{{__('labels.faq_list')}}</caption>
                                    <thead>
                                        <tr>
                                            <th class="w_70 id">{{__('labels.serial_no')}}</th>
                                            <th class="question">{{__('labels.question')}}</th>
                                            <th class="answer">{{__('labels.answer')}}</th>
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

<!-- add and edit faq modal @s-->
<div class="modal fade zoom" tabindex="-1" id="addEditFAQModal" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<!-- add and edit faq modal @e-->

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
                <p id="faqReadMoreData" class="text-break"></p>
            </div>
        </div>
    </div>
</div>
<!-- readMore modal @e-->
@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/faq/index.js')) !!}
@endpush
