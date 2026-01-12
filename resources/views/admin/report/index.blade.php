@extends('layouts.admin.app')
@section('title', __('labels.reports_&_analytics'))
@section('content')
<!-- content @s -->
<div class="nk-content reportsAnalytics">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{ __('labels.reports_&_analytics') }}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-pipe">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('labels.dashboard') }}</a></li>
                                    <li class="breadcrumb-item active">{{ __('labels.reports_&_analytics') }}</li>
                                </ul>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle"> <a href="#"
                                    class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                                        class="icon ni ni-more-v"></em></a> </div>
                        </div>
                    </div>
                </div>
                <div class="nk-block nk-block-lg">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg8 h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('labels.new_user_registration') }}</h6>
                                            </div>
                                            <div class="card-tools"> <a href="{{ route('admin.user.index') }}"
                                                    class="btn btn-primary">{{ __('labels.view_all') }}</a> </div>
                                        </div>
                                        <table class="datatable-init nowrap table">
                                            <thead>
                                                <tr>
                                                    <th class="nk-tb-col-check w_25">{{ __('labels.serial_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.user_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.registration_date') }}</th>
                                                    <th data-orderable="false">{{ __('labels.user_type') }}</th>
                                                    <th data-orderable="false" class="text-center">
                                                        {{ __('labels.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @foreach ($recentUserList as $resentUser)
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>
                                                            <div class="user-card">
                                                                <div class="user-avatar bg-transparent"> <img
                                                                        src="{{ $resentUser->profile_image_url ? $resentUser->profile_image_url : asset_path('assets/images/default-user.jpg') }}"
                                                                        alt="a-sm"> </div>
                                                                <div class="user-info"> <span
                                                                        class="lead-text">{{ ucwords($resentUser->name) }}
                                                                        <span
                                                                            class="dot dot-warning d-md-none ms-1"></span></span>
                                                                    <span>{{ $resentUser->email }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td> {{convertDateToTz($resentUser->created_at, 'd M Y, h:i A')}}</td>
                                                        <td> {{ ucfirst($resentUser->user_type) }} </td>
                                                        <td align="center"><span
                                                                class="badge badge-sm badge-dot has-bg {{ $resentUser->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($resentUser->status) }}</span>
                                                        </td>
                                                    </tr>
                                                @php $count++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg8 h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('labels.top_10_most_active_user_list') }}</h6>
                                            </div>
                                            <div class="card-tools"> <a href="{{ route('admin.user.index') }}"
                                                class="btn btn-primary">{{ __('labels.view_all') }}</a> </div>
                                        </div>
                                        <table class="datatable-init nowrap table">
                                            <thead>
                                                <tr>
                                                    <th class="nk-tb-col-check w_25">{{ __('labels.serial_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.user_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.registration_date') }}
                                                    </th>
                                                    <th data-orderable="false">{{ __('labels.user_type') }}</th>
                                                    <th data-orderable="false" class="text-center">
                                                        {{ __('labels.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @foreach ($recentActiveUserList as $activeUser)
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>
                                                            <div class="user-card">
                                                                <div class="user-avatar bg-transparent"> <img
                                                                        src="{{ $activeUser->profile_image_url ? $activeUser->profile_image_url : asset_path('assets/images/default-user.jpg') }}"
                                                                        alt="a-sm"> </div>
                                                                <div class="user-info"> <span
                                                                        class="lead-text">{{ ucwords($activeUser->name) }}
                                                                        <span
                                                                            class="dot dot-warning d-md-none ms-1"></span></span>
                                                                    <span>{{ $activeUser->email }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td> {{convertDateToTz($activeUser->created_at, 'd M Y, h:i A')}}</td>
                                                        <td> {{ ucfirst($activeUser->user_type) }} </td>
                                                        <td align="center"><span
                                                                class="badge badge-sm badge-dot has-bg {{ $activeUser->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($resentUser->status) }}</span>
                                                        </td>
                                                    </tr>
                                                @php $count++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg8 h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('labels.new_device_upload') }}</h6>
                                            </div>
                                            <div class="card-tools"> <a href="{{ route('admin.product.index') }}"
                                                    class="btn btn-primary">{{ __('labels.view_all') }}</a> </div>
                                        </div>
                                        <table class="datatable-init nowrap table">
                                            <thead>
                                                <tr>
                                                    <th class="nk-tb-col-check w_25">{{ __('labels.serial_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.product_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.udi_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.product_image') }}</th>
                                                    <th data-orderable="false">{{ __('labels.client_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.class') }}</th>
                                                    <th data-orderable="false">{{ __('labels.verification') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @foreach ($recentProductList as $productList)
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>{{ ucwords($productList->product_name) }}</td>
                                                        <td><a href="{{ route('admin.product.show', $productList->id) }}">{{ $productList->udi_number }}</a></td>
                                                        <td>
                                                            <div class="user-card">
                                                                <div class="user-avatar sq md bg-transparent"> <img
                                                                        src="{{ $productList->image_url }}"
                                                                        class="img-fluid" alt="product-img"> </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ ucwords($productList->client->name) }}</td>
                                                        <td>{{ $productList->productClass->name }}</td>
                                                        <td>{{ \App\Models\Product::$productVerificationTypes[$productList->verification_by] }}
                                                        </td>
                                                    </tr>
                                                @php $count++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-full">
                                <div class="nk-ecwg nk-ecwg8 h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('labels.most_viewed_device') }}</h6>
                                            </div>
                                            <div class="card-tools"> <a href="{{ route('admin.product.index') }}"
                                                    class="btn btn-primary">{{ __('labels.view_all') }}</a> </div>
                                        </div>
                                        <table class="datatable-init nowrap table">
                                            <thead>
                                                <tr>
                                                    <th class="nk-tb-col-check w_25">{{ __('labels.serial_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.product_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.udi_no') }}</th>
                                                    <th data-orderable="false">{{ __('labels.product_image') }}</th>
                                                    <th data-orderable="false">{{ __('labels.client_name') }}</th>
                                                    <th data-orderable="false">{{ __('labels.class') }}</th>
                                                    <th data-orderable="false">{{ __('labels.verification') }}</th>
                                                    <th data-orderable="false" class="text-center">
                                                        {{ __('labels.product_count') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @foreach ($productScannedList as $scannedList)
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>{{ ucwords($scannedList->product->product_name) }}</td>
                                                        <td><a href="{{ route('admin.product.show', $scannedList->product->id) }}">{{ $scannedList->product->udi_number }}</a></td>
                                                        <td>
                                                            <div class="user-card">
                                                                <div class="user-avatar sq md bg-transparent"> <img
                                                                        src="{{$scannedList->product->image_url}}"
                                                                        class="img-fluid" alt="product-img"> </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ ucwords(\App\Models\User::where('id', $scannedList->product->client_id)->first()->name); }}
                                                        </td>
                                                        <td>{{ \App\Models\ProductClass::where('id', $scannedList->product->class_id)->first()->name }}
                                                        </td>
                                                        <td>{{ \App\Models\Product::$productVerificationTypes[$scannedList->product->verification_by] }}
                                                        </td>
                                                        <td class="text-center">{{ $scannedList->total_scanned }}</td>
                                                    </tr>
                                                @php $count++ @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
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
