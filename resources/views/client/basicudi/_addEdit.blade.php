<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@if(!empty($basicudi)){{ __('labels.update') }}@else{{ __('labels.add') }}@endif</h5>
            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
        </div>
        <div class="modal-body">
            <form id="udiAddEditForm" action="{{ ($basicudi) ? route('client.basicudi.update', $basicudi->id) : route('client.basicudi.store') }}" method="POST"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                @if ($basicudi)
                    @method('PUT')
                    <input type="hidden" id="udi-id" name="id" value="{{$basicudi->id}}" />
                @endif
                <div class="row g-sm-3 g-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('labels.basic_udi_no') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="name" class="form-control shadow-none"
                                    placeholder="{{ __('labels.enter_basic_udi_no') }}" value="@if(!empty($basicudi)){{$basicudi->name}}@else{{ old('name') }}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group text-end">
                            <button type="button" class="btn btn-primary me-1"
                                id="udiSubmitBtn">@if(!empty($basicudi)){{ __('labels.update') }}@else{{ __('labels.save') }}@endif</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn btn-light"
                                id="udiCancelBtn">{{ __('labels.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\Client\BasicUdiRequest', '#udiAddEditForm') !!}
