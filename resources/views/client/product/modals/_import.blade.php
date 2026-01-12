<form id="importProductForm" action="{{ route('client.product.import')}}" method="POST">
    {{csrf_field()}}
    <div class="productError mb-4 d-none">
        <div class="alert alert-success alert-icon">
            <em class="icon ni ni-check-circle-cut"></em> <label><span id="successVal"></span> Successfull rows.</label>
        </div>
        <div class="alert alert-danger alert-icon">
            <em class="icon ni ni-cross-circle"></em> <label><span id="unsuccessVal"></span> Unsuccessfull rows.</label>
        </div>
        <div class="alert alert-warning alert-icon">
            <em class="icon ni ni-alert-circle"></em> <label><span id="errorVal"></span> Errors found.</label>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">{{__('labels.product_file')}}</label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control shadow-none" value="" name="product_file" aria-describedby="product_file-error">
                    <span id="product_file-error" class="help-block error-help-block"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group text-end">
                <a download id="importProduct" class="btn btn-dim btn-outline-primary px-btn d-none"><em class="icon ni ni-download-cloud"></em><span>{{__('labels.download')}}</span></a>
                <button type="button" class="btn btn-primary me-1" id="importProductBtn">{{__('labels.upload')}}</button>
                <button type="button" data-bs-dismiss="modal" class="btn btn btn-light" id="questionCancelBtn">{{__('labels.cancel')}}</button>
            </div>
        </div>
    </div>
</form>
{{-- add question validation --}}
{!! JsValidator::formRequest('App\Http\Requests\Client\ImportProductRequest','#importProductForm') !!}
