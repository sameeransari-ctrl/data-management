@extends('layouts.admin.app')
@section('title',__('labels.settings'))
@section('content')
<!-- content @s -->
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.general_setting')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item">
                                        <a href="{{route('admin.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.setting')}}</li>
                                </ul>
                            </nav>
                            <!-- breadcrumb @e -->
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                    data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li>

                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner">
                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                <div class="nk-block-text">
                                    <h6>{{__('labels.optimize_clear')}}</h6>

                                </div>
                                <div class="nk-block-actions">
                                    <button type="button" class="btn btn-primary me-1 run-command" rel="opcl">{{__('labels.run')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner">
                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                <div class="nk-block-text">
                                    <h6>{{__('labels.config_cache')}}</h6>

                                </div>
                                <div class="nk-block-actions">
                                    <button type="button" class="btn btn-primary me-1 run-command" rel="coca">{{__('labels.run')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner">
                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                <div class="nk-block-text">
                                    <h6>{{__('labels.run_migrate')}}</h6>
                                </div>
                                <div class="nk-block-actions">
                                    <button type="button" class="btn btn-primary me-1 run-command" rel="rm">{{__('labels.run')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner">
                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                <div class="nk-block-text">
                                    <h6>{{__('labels.run_migrate')}}:{{__('labels.fresh')}}</h6>
                                </div>
                                <div class="nk-block-actions">
                                    <button type="button" class="btn btn-primary me-1 run-command" rel="rmf">{{__('labels.run')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-inner">
                            <div class="between-center flex-wrap flex-md-nowrap g-3">
                                <div class="nk-block-text">
                                    <h6>{{__('labels.maintenance_mode')}} ({{__('labels.on')}}/{{__('labels.off')}})</h6>
                                </div>
                                <div class="nk-block-actions">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input run-command" id="customSwitch" rel="{{($maintenanceMode) ? 'up' : 'down'}}" @if($maintenanceMode) checked=checked @endif>
                                        <label class="custom-control-label" for="customSwitch"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content @e -->


@endsection
@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/admin/setting/index.js')) !!}
@endpush
