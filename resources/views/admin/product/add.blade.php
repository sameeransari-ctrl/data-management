@extends('layouts.admin.app')
@section('title', __('labels.add_product'))
@section('content')
<!-- content @s -->
<div class="nk-content text-break">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.add_product')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.product.index')}}">{{__('labels.product')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.add_product')}}</li>
                                </ul>
                            </nav><!-- breadcrumb @e -->
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                           <a href="{{route('admin.product.index')}}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('labels.back')}}</span></a>
                           <a href="{{route('admin.product.index')}}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                       </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                <div class="nk-block wide-sm mx-auto">
                    <div class="card card-preview">
                       <div class="card-inner">

                           <!-- wizard start -->
                           <div class="nk-split-content nk-split-stretch bg-white p-sm-2 d-flex justify-center align-center flex-column">
                              <div class="wide-lg-fix">
                                 <div class="stepForm mx-auto">
                                    <fieldset>
                                       <legend>
                                          <div class="d-flex justify-content-between">
                                             <div class="stepForm_nums">
                                                01<sub>/ 03</sub>
                                             </div>
                                          </div>
                                       </legend>
                                       <form id="addProductForm" action="{{ route('admin.product.store')}}" method="POST">
                                          {{csrf_field()}}
                                          <div class="form-group">
                                             <div class="row g-sm-3 g-2">
                                                <div class="col-md-12">
                                                   <div class="form-group ">
                                                      <label class="form-label" for="copyUrlText">{{__('labels.add_image_url')}}</label>
                                                      <div class="form-control-wrap">
                                                         <div class="input-group copy-link">
                                                            <input type="text" class="form-control shadow-none copy-link-input" placeholder="{{__('labels.enter')}} {{__('labels.url_link')}}" value="" name="image_url" id="image_url">
                                                            <div class="input-group-append">
                                                               <span class="input-group-text cursor-pointer copy-link-button" id="copyUrlText" ><em class="icon ni ni-link fs-14px"></em></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                      <label class="form-label" for="product_name">{{__('labels.product_name')}}</label>
                                                      <div class="form-control-wrap">
                                                         <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.product_name')}}" name="product_name" id="product_name">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                      <label class="form-label">{{__('labels.client_name')}}</label>
                                                      <select class="form-select client-js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.client_name')}}" aria-describedby="client_id-error" name="client_id" id="client_id">
                                                         <option></option>
                                                         @if(!empty($clientList))
                                                            @foreach($clientList as $value)
                                                               <option value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                                            @endforeach
                                                         @endif
                                                         {{-- <option value="other">{{__('labels.other')}}</option> --}}
                                                      </select>
                                                      <span id="client_id-error" class="help-block error-help-block"></span>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                      <label class="form-label">{{__('labels.basicUdidNo')}}</label>
                                                      <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.basicUdidNo')}}" aria-describedby="basic_udid_id-error" name="basic_udid_id" id="basic_udid_id">
                                                         <option value=""></option>
                                                         {{-- @if(!empty($basicUdids))
                                                            @foreach($basicUdids as $basicUdid)
                                                               <option value="{{ $basicUdid->id }}">{{ $basicUdid->name }}</option>
                                                            @endforeach
                                                         @endif --}}
                                                      </select>
                                                      <span id="basic_udid_id-error" class="help-block error-help-block"></span>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                      <label class="form-label">{{__('labels.udi_no')}}</label>
                                                      <div class="form-control-wrap">
                                                         <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.udi_no')}}" name="udi_number" id="udi_number">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group">
                                                      <label class="form-label">{{__('labels.class')}}</label>
                                                      <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.class')}}" aria-describedby="class_id-error" name="class_id" id="class_id">
                                                         <option></option>
                                                         @if(!empty($productClassList))
                                                            @foreach($productClassList as $value)
                                                               <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                            @endforeach
                                                         @endif
                                                      </select>
                                                      <span id="class_id-error" class="help-block error-help-block"></span>
                                                   </div>
                                                </div>
                                                {{-- <div class="col-md-6 d-none" id="otherClientNameSection">
                                                   <div class="form-group">
                                                      <label class="form-label">{{__('labels.other')}} {{__('labels.client_name')}}</label>
                                                      <div class="form-control-wrap">
                                                         <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.other')}} {{__('labels.client_name')}}" name="client_name" id="client_name">
                                                      </div>
                                                   </div>
                                                </div> --}}
                                                <div class="col-md-12">
                                                   <div class="form-group">
                                                      <label class="form-label" for="product_description">{{__('labels.product_description')}}</label>
                                                      <div class="form-control-wrap">
                                                         <textarea class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.product_description')}}" name="product_description" id="product_description"></textarea>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-12">
                                                   <div class="form-group">
                                                      <label class="form-label" for="copyUrlText2">{{__('labels.add_file_url')}}</label>
                                                      <div class="form-control-wrap field_wrapper">
                                                         <div class="input-group copy-link">
                                                            <input type="text" class="form-control shadow-none copy-link-input" placeholder="{{__('labels.add_file_url')}}" value="" name="file_url[]" aria-describedby="file_url-0-error" >
                                                            <div class="input-group-append">
                                                               <span class="input-group-text cursor-pointer copy-link-button" id="copyUrlText2"><em class="icon ni ni-link fs-14px"></em></span>
                                                            </div>
                                                            <a class="add_button addMoreBtn" data-count="1" title="{{__('labels.add_field')}}"><em class="icon ni ni-plus-sm"></em></a>
                                                         </div>
                                                         <span id="file_url-0-error" class="help-block error-help-block"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="stepForm_btns text-end">
                                             <button type="button" class="btn btn-primary ripple-effect next d-none" id="productSubmitBtn_hidden">{{__('labels.save_n_continue')}}</button>
                                             <button type="button" class="btn btn-primary ripple-effect" id="productSubmitBtn">{{__('labels.save_n_continue')}}</button>
                                          </div>
                                       </form>
                                    </fieldset>
                                    <fieldset>
                                       <legend>
                                          <div class="d-flex justify-content-between">
                                             <div class="stepForm_nums">
                                                02<sub>/ 03</sub>
                                             </div>
                                          </div>
                                       </legend>
                                       <div class="form-group">
                                          <div class="questionnaire custom-checkbox col-md-12" id="questionForReviewSection">
                                             <div id="questionForReviewListing" class="row g-sm-3 g-2">
                                                <div class="d-sm-flex align-items-center justify-content-between">
                                                   <h5>{{__('labels.questionnaire_for_review')}}</h5>
                                                   <button type="button" class="btn btn-outline-primary addQuestionModal" data-question_type="1">{{__('labels.add_question')}}</button>
                                                </div>
                                                <!-- questionForReviewListing html here -->
                                             </div>
                                          </div>
                                       </div>
                                       <div class="stepForm_btns d-flex justify-content-between align-items-center">
                                          <a class="btn btn-outline-primary previous ripple-effect" id="questionForReviewBackBtn">{{__('labels.back')}}</a>
                                          <div class="d-flex">
                                             <a class="btn btn-light ripple-effect skip me-2" id="questionForReviewSkipBtn">{{__('labels.skip')}}</a>
                                             <a class="btn btn-primary ripple-effect next" id="questionForReviewSaveNContinueBtn">{{__('labels.save_n_continue')}}</a>
                                          </div>
                                       </div>
                                    </fieldset>
                                    <fieldset>
                                       <legend>
                                          <div class="d-flex justify-content-between">
                                             <div class="stepForm_nums">
                                                03<sub>/ 03</sub>
                                             </div>
                                          </div>
                                       </legend>
                                       <div class="form-group">
                                          <div class="questionnaire custom-checkbox col-md-12" id="questionForProductSection">
                                             <div id="questionForProductListing" class="row g-sm-3 g-2">
                                                <div class="d-sm-flex align-items-center justify-content-between">
                                                   <h5>{{__('labels.product_questionnaire')}}</h5>
                                                   <button type="button" class="btn btn-outline-primary addQuestionModal" data-question_type="2">{{__('labels.add_question')}}</button>
                                                </div>
                                                <!-- questionForProductListing html here -->
                                             </div>
                                          </div>
                                       </div>
                                       <div class="stepForm_btns d-flex justify-content-between align-items-center">
                                          <a class="btn btn-outline-primary previous ripple-effect">{{__('labels.back')}}</a>
                                          <div class="d-flex">
                                          <a href="{{route('admin.product.index')}}" class="btn btn-light ripple-effect me-2" id="productCancelBtn">{{__('labels.skip')}}</a>
                                          <button type="button" data-product_id="" data-status="1" id="productFinalSubmitBtn" class="btn btn-primary ripple-effect">{{__('labels.save')}}</button>
                                          </div>
                                       </div>
                                    </fieldset>
                                 </div>
                              </div>
                           </div>
                           <!-- wizard end -->

                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
{{-- step 1 validation --}}
{!! JsValidator::formRequest('App\Http\Requests\Admin\AddProductRequest','#addProductForm') !!}


<!-- add question Modal -->
<div class="modal fade zoom" tabindex="-1" id="addQuestion" data-bs-backdrop="static" data-bs-keyboard="false">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <a class="close" data-bs-dismiss="modal" aria-label="Close">
         <em class="icon ni ni-cross"></em>
         </a>
         <div class="modal-header">
            <h5 class="modal-title">{{__('labels.add_question')}}</h5>
         </div>
         <div class="modal-body" id="addQuestionModalContent">
            <!-- content here -->
         </div>
      </div>
   </div>
</div>

<!-- edit question modal -->
<div class="modal fade zoom" tabindex="-1" id="editQuestion" data-bs-backdrop="static" data-bs-keyboard="false">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <a class="close" data-bs-dismiss="modal" aria-label="Close">
         <em class="icon ni ni-cross"></em>
         </a>
         <div class="modal-header">
            <h5 class="modal-title">{{__('labels.edit_question')}}</h5>
         </div>
         <div class="modal-body" id="editQuestionModalContent">
            <!-- content here -->
         </div>
      </div>
   </div>
</div>

@endsection

@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/product/add.js')) !!}
@endpush
