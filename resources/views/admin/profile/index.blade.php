@extends('layouts.admin.app')
@section('title',__('labels.my_profile'))
@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card card-bordered">
                    <div class="card-aside-wrap">
                        <div class="card-inner card-inner-lg">
                            <div class="nk-block-head nk-block-head-lg">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">{{__('labels.profile')}}</h4>
                                        <div class="nk-block-des">
                                            <p>{{__('labels.basic_info_like_your_name_and_email_that_you_use_on_bizprospex_platform')}}</p>
                                        </div>
                                    </div>
                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1"
                                            data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                    </div>
                                    <div class="nk-block-head-content align-self-center align-self-lg-start ms-0 ms-sm-2">
                                        <a class="btn-primary btn" data-bs-toggle="modal" data-bs-target="#profile-edit"><em class="icon ni ni-edit"></em><span>{{__('labels.update_profile')}}</span></a>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head -->
                            <div class="nk-block">
                                <div class="nk-data data-list">
                                    <div class="data-head">
                                        <h6 class="overline-title">{{__('labels.basics')}}</h6>
                                    </div>
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.name')}}</span>
                                            <span
                                                class="data-value">{{ucwords($userData->name) ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                        <div class="data-col data-col-end"><span class="data-more disable"><em
                                                    class="icon ni ni-user-alt"></em></span></div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.email')}}</span>
                                            <span
                                                class="data-value">{{$userData->email ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                        <div class="data-col data-col-end"><span class="data-more disable"><em
                                                    class="icon ni ni-lock-alt"></em></span></div>
                                    </div><!-- data-item -->
                                    <div class="data-item">
                                        <div class="data-col">
                                            <span class="data-label">{{__('labels.phone_number')}}</span>
                                            <span
                                                class="data-value">{{$userData->phone_number ?? __('labels.not_add_yet')}}</span>
                                        </div>
                                        <div class="data-col data-col-end"><span class="data-more disable"><em
                                                    class="icon ni ni-call"></em></span></div>
                                    </div><!-- data-item -->
                                </div><!-- data-list -->
                            </div><!-- .nk-block -->
                        </div>
                        <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg"
                            data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                            <div class="card-inner-group" data-simplebar>
                                <div class="card-inner">
                                    <div class="user-card">
                                        <div class="user-avatar bg-primary imgBox">

                                            <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}"
                                                id="currentProfileImage" class="img-fluid" alt="Not Found">
                                        </div>
                                        <div class="user-info">
                                            <span class="lead-text">{{ucwords($userData->name) ?? ''}}</span>
                                            <span class="sub-text">{{$userData->email ?? ''}}</span>
                                        </div>
                                    </div><!-- .user-card -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <ul class="link-list-menu">
                                        <li>
                                            <a href="{{route('admin.profile.index')}}" class="@if(basename(request()->path()) == 'profile') active @endif "><em class="icon ni ni-user-fill-c"></em><span>{{__('labels.profile')}}</span></a>
                                            <a href="{{route('admin.change-user-password')}}" class="@if(basename(request()->path()) == 'change-user-password') active @endif "><em class="icon ni ni-lock-alt"></em><span>{{__('labels.change_password')}}</span></a>
                                        </li>
                                    </ul>
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- card-aside -->
                    </div><!-- .card-aside-wrap -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>


<!-- Update Profile -->
<div class="modal fade zoom" tabindex="-1" id="profile-edit" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <a class="close custom-close1" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">{{__('labels.update_profile')}}</h5>
            </div>
            <div class="modal-body">
                <form id="updateProfileForm" method="POST" action="{{route('admin.update-profile')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-sm-3 g-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="uploadPhoto upload_photo position-relative mx-auto">
                                    <div class="img-box rounded-circle overflow-hidden w-100 h-100 imgBox">
                                        <img src="{{ getLoggedInUserDetail()->profile_image_url ?? asset_path('assets/images/default-user.jpg') }}" class="img-fluid" alt="user-img"
                                            class="img-fluid rounded-circle border" id="imagePreview">
                                    </div>
                                    <label
                                        class="mb-0 d-flex align-items-center justify-content-center position-absolute rounded-circle overflow-hidden imgBox"
                                        for="uploadImage">
                                        <input type="file" id="uploadImage" class="imgBox customCropperImage"
                                            data-width-height="{{ config('constants.image.profile.dimension') }}"
                                            data-max-size="{{ config('constants.image.profile.maxSize') }}"
                                            data-accept-file="{{ config('constants.image.profile.acceptType') }}"
                                            data-preview-id="imagePreview" data-base64-id="uploadImageBase64"
                                            accept="{{ config('constants.image.profile.acceptType') }}"
                                            data-path={{ config('constants.image.profile.path') }}
                                            data-zoomable="{{ config('constants.image.profile.zoomAble') }}"
                                            data-aspect-ratio="{{ config('constants.image.profile.aspectRatio') }}"
                                            data-crop-box-resizable="{{ config('constants.image.profile.cropBoxResizable') }}"
                                            data-zoomOnWheel="{{ config('constants.image.profile.zoomOnWheel') }}">
                                        <input type="hidden" name="profile_image" id="uploadImageBase64" value="">
                                        <!-- <input type="file" id="uploadImage" class="imgBox"> -->
                                        <em class="icon ni ni-camera-fill"></em>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="full-name">{{__('labels.name')}}</label>
                                <input type="text" class="form-control" name="name" id="full-name" placeholder="{{__('labels.enter_full_name')}}"
                                    value="{{$userData->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="user-email">{{__('labels.email_address')}}</label>
                                <input type="text" class="form-control" name="email" id="user-email" placeholder="{{__('labels.enters_email')}}"
                                    value="{{$userData->email}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group countryCode">
                                <label class="form-label" for="phone-number">{{__('labels.mobile')}}</label>
                                <div class="d-flex">
                                    <select class="form-select js-select2" data-placeholder="" name="phone_code" disabled>

                                        @if(isset($countries) && !empty($countries))
                                        @foreach ($countries as $country)
                                        <option {{ !empty($userData->phone_code) && ($userData->phone_code == $country->phone_code) ? 'selected' : '' }} value="{{ $country->phone_code }}">{{'+'.$country->phone_code }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="text" class="form-control" name="phone_number" id="phone-number"
                                        placeholder="{{__('labels.enter_phone_number')}}" value="{{$userData->phone_number}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="button" id="updateProfileBtn" class="btn btn-primary width-120 ripple-effect">{{__('labels.update')}}</button>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-light width-120 ripple-effect mr-2 resetForm">{{__('labels.cancel')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('admin.image-cropper-modal')
@endsection
@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Admin\UpdateProfileRequest','#updateProfileForm') !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/cropper/image-cropper.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/profile/index.js')) !!}
@endpush
