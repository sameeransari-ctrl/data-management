<form id="addQuestionForm" action="{{ route('client.product.store-question')}}" method="POST">
    {{csrf_field()}}
    <div class="row g-3">
        <input type="hidden" value="" id="answer_type_value">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">{{__('labels.question_title')}}</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.title')}}" value="" name="question_title" id="question_title" aria-describedby="question_title-error">
                    <span id="question_title-error" class="help-block error-help-block"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">{{__('labels.answer_type')}}</label>
                <div class="form-control-wrap">
                    <select class="form-select js-select2 commonSelect2" data-placeholder="{{__('labels.select')}} {{__('labels.answer_type')}}" name="answer_type" id="answer_type" aria-describedby="answer_type-error">>
                    <option></option>
                    <option value="check_box">{{__('labels.check_box')}}</option>
                    <option value="radio_button">{{__('labels.radio_button')}}</option>
                    <option value="input_field">{{__('labels.input_field')}}</option>
                    </select>
                    <span id="answer_type-error" class="help-block error-help-block"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <div class="panel d-none" id="check_box">
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-control-sm custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck110" name="answer_options_check_box_default_answer[]" value="1" disabled>
                                <label class="custom-control-label" for="customCheck110"></label>
                            </div>
                            <input type="text" class="form-control shadow-none checkbox-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_check_box[1]" id="answer_options_check_box_1" aria-describedby="answer_options_check_box[1]-error">
                        </div>
                        <span id="answer_options_check_box[1]-error" class="help-block error-help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-control-sm custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck122" name="answer_options_check_box_default_answer[]" value="2" disabled>
                                <label class="custom-control-label" for="customCheck122"></label>
                            </div>
                            <input type="text" class="form-control shadow-none checkbox-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_check_box[2]" id="answer_options_check_box_2" aria-describedby="answer_options_check_box[2]-error">
                        </div>
                        <span id="answer_options_check_box[2]-error" class="help-block error-help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-control-sm custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck131" name="answer_options_check_box_default_answer[]" value="3" disabled>
                                <label class="custom-control-label" for="customCheck131"></label>
                            </div>
                            <input type="text" class="form-control shadow-none checkbox-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_check_box[3]" id="answer_options_check_box_3" aria-describedby="answer_options_check_box[3]-error">
                        </div>
                        <span id="answer_options_check_box[3]-error" class="help-block error-help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-control-sm custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck141" name="answer_options_check_box_default_answer[]" value="4" disabled>
                                <label class="custom-control-label" for="customCheck141"></label>
                            </div>
                            <input type="text" class="form-control shadow-none checkbox-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_check_box[4]" id="answer_options_check_box_4" aria-describedby="answer_options_check_box[4]-error">
                        </div>
                        <span id="answer_options_check_box[4]-error" class="help-block error-help-block"></span>
                    </div>
                </div>
                <div class="panel d-none" id="radio_button">
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio115" name="answer_options_radio_button_default_answer[]" value="1" class="custom-control-input" disabled>
                                <label class="custom-control-label" for="customRadio115"></label>
                            </div>
                            <input type="text" class="form-control shadow-none radio-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_radio_button[1]" id="answer_options_radio_button_1" aria-describedby="answer_options_radio_button[1]-error">
                        </div>
                        <span id="answer_options_radio_button[1]-error" class="help-block error-help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio116" name="answer_options_radio_button_default_answer[]" value="2" class="custom-control-input" disabled>
                                <label class="custom-control-label" for="customRadio116"></label>
                            </div>
                            <input type="text" class="form-control shadow-none radio-value" placeholder="{{__('labels.enter')}} {{__('labels.label')}}" value="" name="answer_options_radio_button[2]" id="answer_options_radio_button_2" aria-describedby="answer_options_radio_button[2]-error">
                        </div>
                        <span id="answer_options_radio_button[2]-error" class="help-block error-help-block"></span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="question_type" value="{{!empty($type)? $type : '' }}">
        <div class="col-md-12">
            <div class="form-group text-end">
                <button type="button" class="btn btn-primary me-1" id="addQuestionBtn">{{__('labels.add')}}</button>
                <button type="button" data-bs-dismiss="modal" class="btn btn btn-light" id="questionCancelBtn">{{__('labels.cancel')}}</button>
            </div>
        </div>
        </div>
    </form>
    {{-- add question validation --}}
    {!! JsValidator::formRequest('App\Http\Requests\Client\AddQuestionRequest','#addQuestionForm') !!}
