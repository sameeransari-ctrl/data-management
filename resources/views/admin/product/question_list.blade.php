@forelse($productQuestionList as $question)
    @if ($question['answer_type']==1)
        <div class="col-md-12" id="questionId_{{ $question['id'] }}">
            <div class="box">
                <div class="form-group mb-0">
                    <label class="form-label">{{$question['question_title']}}</label>
                    <div class="row g-2">
                        @php
                            $answerOptions = json_decode($question['answer_options'], true);
                        @endphp
                        @forelse ($answerOptions as $key => $answer)
                            <div class="col-lg-3 col-6 custom-checkText">
                                <div class="custom-control custom-control-sm custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mcqCheck11_{{$key}}" disabled>
                                    <label class="custom-control-label" for="mcqCheck11_{{$key}}">{{$answer}}</label>
                                </div>
                            </div>
                        @empty
                            <p>{{__('labels.no_answers')}}</p>
                        @endforelse
                    </div>
                </div>
                <div class="action">
                    <a class="editQuestionModal cursor-pointer" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></a>
                    {{-- <button type="button" class="btn btn-outline-primary editQuestionModal" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></button> --}}
                    <a class="removeData cursor-pointer" data-question_id="{{ $question['id'] }}"><em class="ni ni-cross"></em></a>
                </div>
            </div>
        </div>
    @endif
    @if ($question['answer_type']==2)
        <div class="col-md-12" id="questionId_{{ $question['id'] }}">
            <div class="box">
                <div class="form-group">
                    <label class="form-label">{{$question['question_title']}}</label>
                    @php
                        $answerOptions = (!empty($question['answer_options'])) ? json_decode($question['answer_options'], true) : '';
                    @endphp
                    <div class="form-control-wrap">
                        @forelse ($answerOptions as $key => $answer)
                            <div class="custom-control custom-radio {{($loop->index==0) ? 'me-2' : ''}}">
                                <input type="radio" id="customRadio113_{{$key}}" name="customRadioMeet" class="custom-control-input" disabled>
                                <label class="custom-control-label" for="customRadio113_{{$key}}">{{$answer}}</label>
                            </div>
                        @empty
                            <p>{{__('labels.no_answers')}}</p>
                        @endforelse
                    </div>
                </div>
                <div class="action">
                    <a class="editQuestionModal cursor-pointer" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></a>
                    {{-- <button type="button" class="btn btn-outline-primary editQuestionModal" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></button> --}}
                    <a class="removeData cursor-pointer" data-question_id="{{ $question['id'] }}"><em class="ni ni-cross"></em></a>
                </div>
            </div>
        </div>
    @endif
    @if ($question['answer_type']==3)
        <div class="col-md-12" id="questionId_{{ $question['id'] }}">
            <div class="box">
                <div class="form-group">
                    <label class="form-label">{{$question['question_title']}}</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.text')}}" disabled>
                    </div>
                </div>
                <div class="action">
                    <a class="editQuestionModal cursor-pointer" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></a>
                    {{-- <button type="button" class="btn btn-outline-primary editQuestionModal" data-question_type="{{$question['question_type']}}" data-question_id="{{ $question['id'] }}"><em class="ni ni-edit-alt"></em></button> --}}
                    <a class="removeData cursor-pointer" data-question_id="{{ $question['id'] }}"><em class="ni ni-cross"></em></a>
                </div>
            </div>
        </div>
    @endif
@empty
    <div class="col-md-12">
        <div class="box">
            <p>{{__('labels.no_questions')}}</p>
        </div>
    </div>
@endforelse
