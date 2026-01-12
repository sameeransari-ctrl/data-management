<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <a class="close custom-close cursor-pointer" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
        <div class="modal-header">
            <h5 class="modal-title">{{!empty($faq) ? __('labels.edit_faq') : __('labels.add_faq')}}</h5>
        </div>
        <div class="modal-body">
            <form id="faqAddEditForm" action="{{ !empty($faq) ? route('admin.faq.update', $faq->id ):  route('admin.faq.store') }}" method="POST">
                {{csrf_field()}}
                <input name="_method" type="hidden" value="{{!empty($faq)?'PUT':'POST'}}">
                <input type="hidden" name="id" value="{{!empty($faq)? $faq->id : '' }}" />
                <div class="form-group">
                    <label class="form-label">{{__('labels.question')}}</label>
                    <div class="form-control-wrap">
                        <input type="text" name="question" class="form-control" placeholder="{{__('labels.enter_question')}}" value="{{!empty($faq)? $faq->question : ''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{__('labels.answer')}}</label>
                    <div class="form-control-wrap">
                        <textarea name="answer" class="faq-description" aria-describedby="answer-error">{{!empty($faq)? $faq->answer : ''}}</textarea>
                        <span id="answer-error" class="help-block error-help-block"></span>
                    </div>
                </div>
                <div class="form-group text-end">
                    <button type="button" class="btn btn-primary me-1" id="faqSubmitBtn">{{!empty($faq) ? __('labels.update') : __('labels.add')}}</button>
                    <button type="button" data-dismiss="modal" class="btn btn-light custom-close" id="faqCancelBtn">{{__('labels.cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Admin\FaqRequest','#faqAddEditForm')->ignore("input:hidden:not(input:hidden.required), [contenteditable='true']") !!}
