<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@if(!empty($basicudi)){{ __('labels.update') }}@else{{ __('labels.add') }}@endif</h5>
            <a class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
        </div>
        <div class="modal-body">
            <form id="udiAddEditForm" action="{{ ($basicudi) ? route('admin.basicudi.update', $basicudi->id) : route('admin.basicudi.store') }}" method="POST"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                @if ($basicudi)
                    @method('PUT')
                    <input type="hidden" id="udi-id" name="id" value="{{$basicudi->id}}" />
                @endif
                <div class="row g-sm-3 g-2">

                    <div class="col-md-12">
                        <div class="form-group">
                           <label class="form-label">{{__('labels.client_name')}}</label>
                           <select class="form-select js-select2" data-placeholder="{{__('labels.select')}} {{__('labels.client_name')}}" aria-describedby="client_id-error" name="added_by" id="client_id">
                              <option></option>
                              @if(!empty($clientList))
                                 @foreach($clientList as $value)
                                    <option @selected($basicudi && $basicudi->added_by == $value->id) value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                 @endforeach
                              @endif
                           </select>
                           <span id="client_id-error" class="help-block error-help-block"></span>
                        </div>
                     </div>

                    <div class="col-lg-12">
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
{!! JsValidator::formRequest('App\Http\Requests\Admin\BasicUdiRequest', '#udiAddEditForm') !!}
