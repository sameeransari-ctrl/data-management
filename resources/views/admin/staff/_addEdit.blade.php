<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <a class="close custom-close" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
        <div class="modal-header">
            <h5 class="modal-title">{{!empty($staff) ? __('labels.edit_staff') : __('labels.add_staff')}}</h5>
        </div>
        <div class="modal-body">
            <form id="staffAddEditForm" action="{{ !empty($staff) ? route('admin.staff.update', $staff->id ):  route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <input name="_method" type="hidden" value="{{!empty($staff)?'PUT':'POST'}}">
                <input type="hidden" name="id" id="staff-id"  value="{{!empty($staff)? $staff->id : '' }}" />
                <input type="hidden" name="formType"  value="{{!empty($staff)? 'update' : 'add' }}" />
                <div class="row g-sm-3 g-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="uploadPhoto upload_photo position-relative mx-auto">
                                <div class="img-box rounded-circle overflow-hidden w-100 h-100 imgBox">
                                    <img src="{{ $staff->profile_image_url ?? asset('assets/images/default-user.jpg') }}" class="img-fluid" alt="staff-img"
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
                                    <em class="icon ni ni-camera-fill"></em>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{__('labels.name')}}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('labels.enter_name') }}" value="{{!empty($staff)? $staff->name : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{__('labels.email_address')}}</label>
                            <div class="form-control-wrap">
                                <input type="email" name="email" class="form-control shadow-none" placeholder="{{ __('labels.enter_email') }}" value="{{!empty($staff)? $staff->email : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group countryCode">
                            <label class="form-label">{{ __('labels.mobile') }}</label>
                            <div class="d-flex">
                                <select class="form-select js-select2" data-placeholder="" name="phone_code" id="countries">

                                    @if(isset($countries) && !empty($countries))
                                    @foreach ($countries as $country)
                                    <option {{ !empty($staff->phone_code) && ($staff->phone_code == $country->phone_code) ? 'selected' : '' }} value="{{ $country->phone_code }}" data-flag="{{ $country->flag_image_url }}" title="{{ $country->name }}">{{'+'.$country->phone_code }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <input type="text" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_mobile_number') }}" name="phone_number" value="{{!empty($staff)? $staff->phone_number : ''}}" aria-describedby="phone_number-error">
                            </div>
                            <span id="phone_number-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.role') }}</label>
                            <select class="form-select js-select2 form-select2"
                                data-placeholder="{{ __('labels.select_role') }}" name="user_type" aria-describedby="user_type-error">
                                <option></option>
                                @foreach ($roles as $role)
                                    <option {{ !empty($staff) && ($roleId == $role->id) ? 'selected' : '' }} value="{{  $role->id }}">{{ ucwords($role->name) }}</option>
                                @endforeach
                            </select>
                            <span id="user_type-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">{{ __('labels.password') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <a class="form-icon form-icon-right passcode-switch" data-target="password">
                                    <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" class="form-control shadow-none" id="password" name="password" placeholder="{{ __('labels.enter_password') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="confirm_password">{{ __('labels.confirm_password') }}</label>
                            </div>
                            <div class="form-control-wrap">
                                <a class="form-icon form-icon-right passcode-switch" data-target="confirm_password">
                                    <em class="passcode-icon icon-show icon ni ni-eye-off"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye"></em>
                                </a>
                                <input type="password" class="form-control shadow-none" id="confirm_password" aria-describedby="confirm-password-error" name="confirm_password" placeholder="{{ __('labels.enter_confirm_password') }}">
                            </div>
                        </div>
                        <span id="confirm-new-password-error" class="help-block error-help-block"></span>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-end">
                            <button type="button" class="btn btn-primary me-1" id="staffSubmitBtn">{{!empty($staff) ? __('labels.update') : __('labels.save')}}</button>
                            <button type="button" data-dismiss="modal" class="btn btn-light custom-close" id="staffCancelBtn">{{__('labels.cancel')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\Admin\StaffRequest', '#staffAddEditForm') !!}
