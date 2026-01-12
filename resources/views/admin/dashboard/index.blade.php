@extends('layouts.admin.app')
@section('title',__('labels.dashboard'))
@section('content')

<div class="nk-content dashboard dashboardCardFont">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.dashboard')}}</h3>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="row g-3">
                       <div class="col-xxl-4 col-md-6">
                          <div class="card card-full">
                             <div class="nk-ecwg nk-ecwg6">
                                <div class="card-inner">
                                   <div class="card-title-group">
                                      <div class="card-title">
                                         <h6 class="title">{{__('labels.total_active_and_inactive_staffs')}}</h6>
                                      </div>
                                   </div>
                                   <div class="data mt-2">
                                      <div class="data-group">
                                         <div class="d-flex align-items-center">
                                              <div class="nk-store-statistics d-flex align-items-center justify-content-center me-2">
                                                  <div class="icon bg-purple-dim">
                                                      <img src="{{asset_path('assets/images/users-group.svg')}}" alt="users-group">
                                                  </div>
                                              </div>
                                              <div class="amount">{{$activeStaffs->count()}} <span class="fs-14px text-success">({{__('labels.active')}})</span></div>
                                         </div>
                                         <div class="amount">{{$inactiveStaffs->count()}} <span class="fs-14px text-danger">({{__('labels.inactive')}})</span></div>
                                      </div>
                                   </div>
                                </div>
                                <!-- .card-inner -->
                             </div>
                             <!-- .nk-ecwg -->
                          </div>
                          <!-- .card -->
                       </div>
                       <!-- .col -->
                       <div class="col-xxl-4 col-md-6">
                          <div class="card card-full">
                             <div class="nk-ecwg nk-ecwg6">
                                <div class="card-inner">
                                   <div class="card-title-group">
                                      <div class="card-title">
                                         <h6 class="title">{{__('labels.total_active_and_inactive_roles')}}</h6>
                                      </div>
                                   </div>
                                   <div class="data mt-2">
                                      <div class="data-group">
                                          <div class="d-flex align-items-center">
                                          <div class="nk-store-statistics d-flex align-items-center justify-content-center me-2">
                                            <div class="icon bg-purple-dim">
                                               <img src="{{asset_path('assets/images/scan.svg')}}" alt="scan">
                                            </div>
                                         </div>
                                         <div class="amount">{{$activeRoles->count()}} <span class="fs-14px text-success">({{__('labels.active')}})</span></div>
                                          </div>
                                         <div class="amount">{{$inactiveRoles->count()}} <span class="fs-14px text-danger">({{__('labels.inactive')}})</span></div>
                                      </div>
                                   </div>
                                </div>
                             </div>
                             <!-- .card-inner -->
                          </div>
                          <!-- .nk-ecwg -->
                       </div>
                       <!-- .col -->
                       <div class="col-xxl-4 col-md-6">
                             <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                   <div class="card-inner">
                                   <div class="card-title-group">
                                         <div class="card-title">
                                            <h6 class="title">{{__('labels.total_datas_active_and_inactive')}}</h6>
                                         </div>
                                   </div>
                                   <div class="data mt-2">
                                         <div class="data-group">
                                            <div class="d-flex align-items-center">
                                            <div class="nk-store-statistics d-flex align-items-center justify-content-center me-2">
                                               <div class="icon bg-purple-dim">
                                                     <img src="{{asset_path('assets/images/user-ban.svg')}}" alt="user-ban">
                                               </div>
                                            </div>
                                            <div class="amount">0 <span class="fs-14px text-success">({{__('labels.active')}})</span></div>
                                            </div>
                                            <div class="amount">0<span class="fs-14px text-danger">({{__('labels.inactive')}})</span></div>
                                         </div>
                                   </div>
                                   </div>
                                <!-- .card-inner -->
                                </div>
                                <!-- .nk-ecwg -->
                             </div>
                             <!-- .card -->
                       </div>
                       <!-- .col -->
                       <div class="chartSec col-12">
                          <div class="row g-3">
                             <!-- <div class="col-xxl-8 col-lg-6">
                                <div class="card card-preview h-100">
                                   <div class="card-inner">
                                      <div class="card-head">
                                         <h6 class="title">{{__('labels.basic_and_medical_users_registered')}}</h6>
                                      </div>
                                      <div class="chart_bodyMap">
                                         <div class="chart" id="regions_div"> </div>
                                      </div>
                                   </div>
                                </div>
                             </div> -->
                             <div class="col-xxl-4 col-lg-6">
                                <div class="card card-full overflow-hidden">
                                   <div class="card-inner">
                                      <div class="card-head">
                                         <h6 class="title">{{__('labels.data_uploaded')}}</h6>
                                      </div>
                                      <div class="nk-ecwg7-ck">
                                         <canvas class="ecommerce-doughnut-s1" id="orderStatistics"></canvas>
                                      </div>
                                      <ul class="nk-ecwg7-legends">
                                         <li>
                                            <div class="title">
                                               <span class="dot dot-lg sq" data-bg="#816bff"></span>
                                               <span>{{__('labels.today')}}</span>
                                            </div>
                                         </li>
                                         <li>
                                            <div class="title">
                                               <span class="dot dot-lg sq" data-bg="#13c9f2"></span>
                                               <span>{{__('labels.monthly')}}</span>
                                            </div>
                                         </li>
                                         <li>
                                            <div class="title">
                                               <span class="dot dot-lg sq" data-bg="#ff82b7"></span>
                                               <span>{{__('labels.weekly')}}</span>
                                            </div>
                                         </li>
                                      </ul>
                                   </div>
                              
                                </div>
                            
                             </div>
                        
                          </div>
                       </div>
                    </div>
                </div>
                <!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

@endsection
@push('scripts')
<script nonce="{{ csp_nonce('script') }}" type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script nonce="{{ csp_nonce('script') }}" type="text/javascript" >
   let today = parseInt("{{$scannedStatistics['today']}}");
   let month = parseInt("{{$scannedStatistics['month']}}");
   let weekly = parseInt("{{$scannedStatistics['weekly']}}");
</script>
{!! returnScriptWithNonce(asset_path('assets/js/admin/dashboard-chart.js')) !!}
@endpush
