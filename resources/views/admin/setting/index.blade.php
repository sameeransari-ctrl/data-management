@extends('layouts.admin.app')
@section('title',__('labels.settings'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.application_setting')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.setting')}}</li>
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
                            <form id="addForm" action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}

                            <div class="row gy-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('labels.app_name')}}</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="app_name" class="form-control" placeholder="{{__('labels.enter_app_name')}}" value="{{!empty($settings['app.name'])? $settings['app.name'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('labels.website_logo')}}</label>
                                        <div class="form-control-wrap">
                                            <div class="form-file">
                                                <input type="file" id="uploadImage" class="imgBox customCropperImage"
                                                data-width-height="{{ config('constants.image.logo.dimension') }}"
                                                data-max-size="{{ config('constants.image.logo.maxSize') }}"
                                                data-accept-file="{{ config('constants.image.logo.acceptType') }}"
                                                data-preview-id="imagePreview" data-base64-id="uploadImageBase64"
                                                accept="{{ config('constants.image.logo.acceptType') }}"
                                                data-path="{{ config('constants.image.logo.path') }}"
                                                data-zoomable="{{ config('constants.image.logo.zoomAble') }}"
                                                data-crop-box-resizable="{{ config('constants.image.logo.cropBoxResizable') }}"
                                                data-zoomOnWheel="{{ config('constants.image.logo.zoomOnWheel') }}">
                                                <input type="hidden" name="app_logo" class="form-control" id="uploadImageBase64" placeholder="Enter Value" value="{{!empty($settings['app.logo'])? $settings['app.logo'] : ''}}">
                                                <label class="form-file-label" for="uploadImage">{{__('labels.choose_file')}}</label>
                                            </div>
                                            <div class="img-box imgBox mt-3">
                                                <img src="{{ getImageUrl($settings['app.logo']) }}" class="img-fluid img-thumbnail " alt="user-img" id="imagePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gy-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('labels.google_analytics')}}</label>
                                        <div class="form-control-wrap">
                                            <input type="text" name="google_analytics" class="form-control" placeholder="{{__('labels.enter_google_analytics_code')}}" value="{{!empty($settings['app.google_analytics'])? $settings['app.google_analytics'] : ''}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-end">
                                <button type="button" class="btn btn-primary me-1" id="settingSubmitBtn">{{__('labels.save')}}</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
@include('admin.image-cropper-modal')

@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/setting/index.js')) !!}
@endpush
