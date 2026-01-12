<!DOCTYPE html>
<html lang="{{config('app.locale')}}" class="js">

<head>
    <title>  @yield('title') | {{getAppName()}} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('layouts.admin.head-link')
    @stack('css')
</head>

<body class="nk-body bg-lighter npc-default {{ Auth::check() ? 'has-sidebar' : 'pg-auth'}} no-touch nk-nio-theme">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @auth
                @include('layouts.admin.sidebar')
            @endauth
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap {{ Auth::check() ? '' : 'nk-wrap-nosidebar'}}">
            @auth
                @include('layouts.admin.header')
            @endauth

                <!-- content @s -->
                @yield('content')
                <!-- content @e -->

                <!-- footer @s -->
                @include('layouts.admin.footer')
                <!-- footer @e -->
            </div>

            <!-- main @e -->
        </div>
        @stack('scripts')
        <!-- app-root @e -->
        <!-- JavaScript -->
        {{-- @include('cookie-consent::index') --}}
</body>

</html>
