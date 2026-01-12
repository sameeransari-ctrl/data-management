@extends('layouts.email.app')
@section('content')
<strong style="font-family: 'Urbanist', sans-serif; font-weight:700; color:#000;font-size:18px;">{{ __('labels.hello') }}!</strong>
<p style="font-family: 'Urbanist', sans-serif; color:#707070; font-size:15px;font-weight:400; margin-bottom: 12px; margin-top: 12px;">{{__('labels.use_this_code_to_verify_your_account', ['otp' => $otp])}}</p>
<p></p>
<p></p>
<p style="font-family: 'Urbanist', sans-serif;color:#707070; font-size:15px;font-weight:400;">{{__('labels.thank_you_for_using_our_application') }} </p>
<p style="font-family: 'Urbanist', sans-serif;color:#707070; font-size:15px;font-weight:400;">{{__('labels.regards')}},<br>{{config('app.name')}}</p>
@endsection