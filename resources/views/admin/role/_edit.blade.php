<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{__('labels.edit')}}</h5>
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
        </div>
        <div class="modal-body">
            <form id="roleEditForm" action="{{ route('admin.role.update', $role->id) }}" method="PUT" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label class="form-label">{{__('labels.role')}}</label>
                        <div class="form-control-wrap">
                            <input type="text" name="name" class="form-control shadow-none" placeholder="{{__('labels.enter')}} {{__('labels.role')}}" value="@if(!empty($role)){{$role->name}}@else{{ old('name') }}@endif">
                        </div>
                        </div>
                    </div>
                </div>

                <div class="moduleSec mt-lg-4 mt-2">
                    <h6 class="moduleSec_head">{{__('labels.permissions')}}</h6>
                    <div class="moduleSec_accordion">
                        <div id="accordion-2" class="accordion accordion-s3">
                            @php $i = 0; @endphp
                            @foreach($permissions->groupBy('module_name') as $menuName => $items)
                                @php
                                    $menuId = str_replace(" ", "_", $menuName);
                                @endphp
                                <div class="accordion-item">
                                    <div class="moduleSec_title d-flex custom-checkText py-2">
                                        <button type="button" class="accordion-head {{$i != 0 ? 'collapsed' : ''}} custom-checkText" data-bs-toggle="collapse"
                                            data-bs-target="#accordion_item_{{$i}}">
                                            <span class="accordion-icon"></span>
                                        </button>
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input type="checkbox" class="custom-control-input permission-menu" data-id="{{$i}}" id="customCheckAll_{{$i}}">
                                            <label class="custom-control-label fw-bold side-menu" for="customCheckAll_{{$i}}">{{ $menuName }}</label>
                                        </div>
                                    </div>
                                    <div class="accordion-body collapse {{$i == 0 ? 'show' : ''}}" id="accordion_item_{{$i}}" data-bs-parent="#accordion-2" >
                                        <div class="accordion-inner">
                                            <div class="row g-2 custom-checkText ml-30" id="allCheck_{{$i}}">
                                                @foreach($items as $key => $item)
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="custom-control custom-control-sm custom-checkbox">
                                                            <input type="checkbox" {{in_array($item->id, $rolePermissions) ? 'checked' : ''}} name="permissions[]" value="{{$item->id}}" class="custom-control-input selectInput" id="customCheck_{{$menuId}}_{{$key}}"
                                                            aria-describedby="permissions-error" data-menuId="{{$i}}">
                                                            <label class="custom-control-label {{ $menuName }}-menu-permission" for="customCheck_{{$menuId}}_{{$key}}">{{ $item->title }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        </div>
                        <span id="permissions-error" class="help-block error-help-block"></span>
                    </div>
                </div>
                <div class="form-group text-end mt-lg-4 mt-2">
                    <button type="button" class="btn btn-primary me-1" id="roleUpdateBtn">{{__('labels.update')}}</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn btn-light" id="roleCancelBtn">{{__('labels.cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\Admin\RoleRequest', '#roleEditForm') !!}
