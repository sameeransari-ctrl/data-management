<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{asset_path('assets/images/favicon/apple-touch-icon.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset_path('assets/images/favicon/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset_path('assets/images/favicon/favicon-16x16.png')}}">
<link rel="manifest" href="{{asset_path('assets/images/favicon/site.webmanifest')}}">
<link rel="mask-icon" href="{{asset_path('assets/images/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<!-- StyleSheets  -->
<link rel="stylesheet"  href="{{ asset_path('assets/css/admin/admin.css') }}" type="text/css">
{{-- <link rel="stylesheet"  href="{{ asset_path('assets/css/admin/summernote.css') }}" type="text/css"> --}}
<link rel="stylesheet"  href="{{ asset_path('assets/css/admin/cropper.min.css') }}" type="text/css">

{!! returnScriptWithNonce(asset_path('assets/js/admin/app.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/admin/admin-app.js')) !!}

<script nonce="{{ csp_nonce('script') }}">
    var internetConnectionError = "{{trans('message.error.internet_connection_error')}}";
</script>
