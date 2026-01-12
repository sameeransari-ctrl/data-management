@extends('layouts.client.app')
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
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{__('labels.total_uploaded_product')}}</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">{{$uploadedProducts}}</div>
                                                <div class="nk-store-statistics">
                                                   <div class="icon bg-purple-dim">
                                                    <img src="{{asset_path('assets/images/upload_products.svg')}}" alt="upload_products">
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{__('labels.total_product_scan')}}</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">{{$scannedProducts}}</div>
                                                <div class="nk-store-statistics">
                                                   <div class="icon bg-teal-dim">
                                                    <img src="{{asset_path('assets/images/scan.svg')}}" alt="scan">
                                                   </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{__('labels.total_review')}}</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">{{$totalReviews}}</div>
                                                <div class="nk-store-statistics">
                                                   <div class="icon bg-pink-dim">
                                                    <img src="{{asset_path('assets/images/total_review.svg')}}" alt="total_review">
                                                   </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{__('labels.total_fsn')}}</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">{{$totalFsn}}</div>
                                                <div class="nk-store-statistics">
                                                   <div class="icon bg-primary-dim">
                                                    <img src="{{asset_path('assets/images/fsn1.svg')}}" alt="fsn">
                                                   </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg6">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{__('labels.total_user_scanned')}}</h6>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="data-group">
                                                <div class="amount">{{$scannedUsers}}</div>
                                                <div class="nk-store-statistics">
                                                   <div class="icon bg-orange-dim">
                                                    <img src="{{asset_path('assets/images/user-viewfinder.svg')}}" alt="user-viewfinder">
                                                   </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                </div><!-- .nk-ecwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="chartSec">
                            <div class="row g-3">
                                <div class="col-xxl-12">
                                    <div class="card card-preview h-100">
                                        <div class="card-inner">
                                            <div class="card-head">
                                                <h6 class="title">{{__('labels.total_products_scanned')}}</h6>
                                            </div>
                                            <div class="chart_bodyMap">
                                                <div class="chart" id="regions_div"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

@endsection
@push('scripts')
<script nonce="{{ csp_nonce('script') }}" type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
{!! returnScriptWithNonce(asset_path('assets/js/client/dashboard-chart.js')) !!}
@endpush
