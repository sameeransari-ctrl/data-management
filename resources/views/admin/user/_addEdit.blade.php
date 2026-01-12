<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ ($user) ? __('labels.edit_user') : __('labels.add_user') }}</h5>
            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
        </div>
        <div class="modal-body">
            <form id="userAddEditForm" action="{{ ($user) ? route('admin.user.update', $user->id) : route('admin.user.store') }}" method="POST"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                @if ($user)
                    @method('PUT')
                    <input type="hidden" id="user-id" name="id" value="{{$user->id}}" />
                @endif
                <div class="row g-sm-3 g-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="uploadPhoto upload_photo position-relative mx-auto">
                                <div class="img-box rounded-circle overflow-hidden w-100 h-100 imgBox">
                                    <img src="{{ $user->profile_image_url ?? asset('assets/images/default-user.jpg') }}" class="img-fluid" alt="user-img"
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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.name') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="name" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_name') }}" value="@if(!empty($user)){{$user->name}}@else{{ old('name') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.email_address') }}</label>
                            <div class="form-control-wrap">
                                <input type="email" name="email" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_email') }}" value="@if(!empty($user)){{$user->email}}@else{{ old('email') }}@endif">
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
                                    <option @selected($user && $user->phone_code == $country->phone_code) value="{{ $country->phone_code }}" data-flag="{{ $country->flag_image_url }}" title="{{ $country->name }}">{{'+'.$country->phone_code }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <input type="text" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_mobile_number') }}" name="phone_number" value="@if(!empty($user)){{$user->phone_number}}@else{{ old('phone_number') }}@endif" aria-describedby="phone_number-error">
                            </div>
                            <span id="phone_number-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.user_type') }}</label>
                            <select class="form-select js-select2 form-select2"
                                data-placeholder="{{ __('labels.select_type') }}" name="user_type" aria-describedby="user_type-error">
                                <option></option>
                                @foreach ($userTypeList as $key => $label)
                                    <option @selected($user && $user->user_type == $label) value="{{ $label }}">{{ ucfirst($label) }}</option>
                                @endforeach
                            </select>
                            <span id="user_type-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.address') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_address') }}" name="address" value="@if(!empty($user)){{$user->address}}@else{{ old('address') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label" >{{ __('labels.country') }}</label>
                            <select class="form-select js-select2 form-select2" data-placeholder="{{ __('labels.select_country') }}" name="country_id"
                            id="countryId" aria-describedby="country_id-error">
                                    <option></option>
                                @foreach ($countries as $country)
                                    <option @selected($user && $user->country_id == $country->id)  value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <span id="country_id-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.city') }}</label>
                            <select class="form-select js-select2 form-select2"
                                data-placeholder="{{ __('labels.select_city') }}"  name="city_id"
                                id="cityId" aria-describedby="city_id-error">
                                @if(isset($cities) && !empty($cities))
                                @foreach ($cities as $city)
                                    <option @selected($user && $user->city_id == $city->id) value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <span id="city_id-error" class="help-block error-help-block"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.zip_code') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_zip_code') }}" name="zip_code" value="@if(!empty($user)){{$user->zip_code}}@else{{ old('zip_code') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group text-end">
                            <button type="button" class="btn btn-primary me-1"
                                id="userSubmitBtn">{{ __('labels.save') }}</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn btn-light"
                                id="userCancelBtn">{{ __('labels.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\Admin\UserRequest', '#userAddEditForm') !!}
