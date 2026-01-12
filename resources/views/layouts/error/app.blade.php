@extends('layouts.admin.app')

@section('title', '')
@section('content')
<main class="mainContent">
    <div class="errorPage bg-green">
        <div class="container">
            <div class="errorPage__inner text-center"><br><br>
                <h1 class="pt-5">@yield('code')</h1>
                <h3>{{__('message.error.page.oops')}}</h3>
                <p>{{__('message.error.page.not_access')}}<br class="d-none d-sm-block">@yield('message')</p>
                <a class="btn btn-primary ripple-effect btn-lg mt-2" href="{{getHomeUrl()}}">{{__('message.error.page.back_to_home')}}</a>
            </div>
        </div>
    </div>
</main>
@endsection
