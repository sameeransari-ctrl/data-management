<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="js">

<head>
    <title> @yield('title') | {{ getAppName() }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('layouts.admin.head-link')
    @stack('css')
</head>
<!-- Body @s -->
<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle wide-xs mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <h1 class="nk-error-head">{{ $errorCode }}</h1>
                            <p class="nk-error-text">{{ $message }}</p>
                            @if (strpos(Request::segment(1), 'admin') !== false)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="btn btn-lg btn-primary mt-2">{{ __('message.error.page.back_to_home') }} </a>
                            @else
                                <a href="{{ route('client.dashboard') }}"
                                    class="btn btn-lg btn-primary mt-2">{{ __('message.error.page.back_to_home') }} </a>
                            @endif
                        </div>
                    </div><!-- .nk-block -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
</body>
<!-- Body @e -->
</html>
