<form id="editQuestionForm" action="{{route('client.product.update-question', $productQuestion[0]['id'])}}" method="POST">
    {{ method_field('put') }}
    <div class="row g-3">
      <input type="hidden" value="{{$productQuestion[0]['answer_type']}}" id="answer_type_value1">
      <input type="hidden" value="" id="answer_type_value">
       <div class="col-md-12">
          <div class="form-group">
             <label class="form-label">{{__('labels.question_title')}}</label>
             <div class="form-control-wrap">
                <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.title')}}" value="{{ $productQuestion[0]['question_title'] ?? '' }}" name="question_title" id="question_title" aria-describedby="question_title-error">
                <span id="question_title-error" class="help-block error-help-block"></span>
             </div>
          </div>
       </div>
       <div class="col-md-12">
          <div class="form-group">
             <label class="form-label">{{__('labels.answer_type')}}</label>
             <div class="form-control-wrap">
                <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.answer_type')}}" name="answer_type" id="answer_type">
                   <option></option>
                   <option value="check_box" {{ $productQuestion[0]['answer_type']==1 ? 'selected' : '' }}>{{__('labels.check_box')}}</option>
                   <option value="radio_button" {{ $productQuestion[0]['answer_type']==2 ? 'selected' : '' }}>{{__('labels.radio_button')}}</option>
                   <option value="input_field" {{ $productQuestion[0]['answer_type']==3 ? 'selected' : '' }}>{{__('labels.input_field')}}</option>
                </select>
                <span id="answer_type-error" class="help-block error-help-block"></span>
             </div>
          </div>
       </div>
       @php
       $answerOptions = (!empty($productQuestion[0]['answer_options'])) ? json_decode($productQuestion[0]['answer_options'], true) : '';
       @endphp
       <div class="col-md-12">
          <div class="form-group">
            <div class="panel {{$productQuestion[0]['answer_type']==1 ? '' : 'd-none' }}" id="check_box">
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-control-sm custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck010" name="answer_options_check_box_default_answer[]" value="1" disabled>
                        <label class="custom-control-label" for="customCheck010"></label>
                     </div>
                     <input type="text" class="form-control shadow-none checkbox-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[1] ?? '' }}" name="answer_options_check_box[1]" id="answer_options_check_box_1" aria-describedby="answer_options_check_box[1]-error">
                  </div>
                  <span id="answer_options_check_box[1]-error" class="help-block error-help-block"></span>
               </div>
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-control-sm custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck020" name="answer_options_check_box_default_answer[]" value="2" disabled>
                        <label class="custom-control-label" for="customCheck020"></label>
                     </div>
                     <input type="text" class="form-control shadow-none checkbox-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[2] ?? '' }}" name="answer_options_check_box[2]" id="answer_options_check_box_2" aria-describedby="answer_options_check_box[2]-error">
                  </div>
                  <span id="answer_options_check_box[2]-error" class="help-block error-help-block"></span>
               </div>
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-control-sm custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck030" name="answer_options_check_box_default_answer[]" value="3" disabled>
                        <label class="custom-control-label" for="customCheck030"></label>
                     </div>
                     <input type="text" class="form-control shadow-none checkbox-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[3] ?? '' }}" name="answer_options_check_box[3]" id="answer_options_check_box_3" aria-describedby="answer_options_check_box[3]-error">
                  </div>
                  <span id="answer_options_check_box[3]-error" class="help-block error-help-block"></span>
               </div>
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-control-sm custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck040" name="answer_options_check_box_default_answer[]" value="4" disabled>
                        <label class="custom-control-label" for="customCheck040"></label>
                     </div>
                     <input type="text" class="form-control shadow-none checkbox-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[4] ?? '' }}" name="answer_options_check_box[4]" id="answer_options_check_box_4" aria-describedby="answer_options_check_box[4]-error">
                  </div>
                  <span id="answer_options_check_box[4]-error" class="help-block error-help-block"></span>
               </div>
            </div>
             <div class="panel {{$productQuestion[0]['answer_type']==2 ? '' : 'd-none' }}" id="radio_button">
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio320" name="answer_options_radio_button_default_answer[]" value="1" class="custom-control-input" disabled>
                        <label class="custom-control-label" for="customRadio320"></label>
                     </div>
                     <input type="text" class="form-control shadow-none radio-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[1] ?? '' }}" name="answer_options_radio_button[1]" id="answer_options_radio_button_1"  aria-describedby="answer_options_radio_button[1]-error">
                  </div>
                  <span id="answer_options_radio_button[1]-error" class="help-block error-help-block"></span>
               </div>

               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio410" name="answer_options_radio_button_default_answer[]" value="2" class="custom-control-input" disabled>
                        <label class="custom-control-label" for="customRadio410"></label>
                     </div>
                     <input type="text" class="form-control shadow-none radio-value-edit" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="{{ $answerOptions[2] ?? '' }}" name="answer_options_radio_button[2]" id="answer_options_radio_button_2" aria-describedby="answer_options_radio_button[2]-error">
                  </div>
                  <span id="answer_options_radio_button[2]-error" class="help-block error-help-block"></span>
               </div>
            </div>
            <span id="answer_options-error" class="help-block error-help-block"></span>
          </div>
       </div>
       <div class="col-md-12">
          <div class="form-group text-end">
             <button type="button" class="btn btn-primary me-1" id="updateQuestionBtn">{{__('labels.update')}}</button>
             <button type="button" data-bs-dismiss="modal" class="btn btn btn-light" id="questionCancelBtn">{{__('labels.cancel')}}</button>
          </div>
       </div>
    </div>
 </form>
 {{-- edit question validation --}}
 {!! JsValidator::formRequest('App\Http\Requests\Client\EditQuestionRequest','#editQuestionForm') !!}
