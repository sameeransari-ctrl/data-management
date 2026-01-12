<form id="importBasicUdiForm" action="{{ route('client.basicudi.import')}}" method="POST">
    {{csrf_field()}}
    <div class="basicUdiError mb-4 d-none">
        <div class="alert alert-success alert-icon">
            <em class="icon ni ni-check-circle-cut"></em> <label><span id="successVal"></span> {{__('labels.successfull_rows')}}</label>
        </div>
        <div class="alert alert-danger alert-icon">
            <em class="icon ni ni-cross-circle"></em> <label><span id="unsuccessVal"></span> {{__('labels.unsuccessfull_rows')}}</label>
        </div>
        <div class="alert alert-warning alert-icon">
            <em class="icon ni ni-alert-circle"></em> <label><span id="errorVal"></span> {{__('labels.errors_found')}}</label>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">{{__('labels.basic_udi_file')}}</label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control shadow-none" value="" name="basic_udi" aria-describedby="basicudi_file-error">
                    <span id="basicudi_file-error" class="help-block error-help-block"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group text-end">
                <a download id="importBasicUdi" class="btn btn-dim btn-outline-primary px-btn d-none"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.download')}}</span></a>
                <button type="button" class="btn btn-primary me-1" id="importBasicUdiBtn">{{__('labels.upload')}}</button>
                <button type="button" data-bs-dismiss="modal" class="btn btn btn-light" id="questionCancelBtn">{{__('labels.cancel')}}</button>
            </div>
        </div>
    </div>
</form>
{{-- add question validation --}}
{!! JsValidator::formRequest('App\Http\Requests\Client\ImportBasicUdiRequest','#importBasicUdiForm') !!}
