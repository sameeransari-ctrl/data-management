@extends('layouts.email.app')
@section('title', 'Forgot password')
@section('subject', 'Forgot password')
@section('content')
<strong style="font-family: 'Urbanist', sans-serif; font-weight:700; color:#000;font-size:18px;">{{ __('labels.hi') }}, {{!empty($data['name']) ? $data['name'] : '' }}!</strong>
<p style="font-family: 'Urbanist', sans-serif; color:#707070; font-size:15px;font-weight:400; margin-bottom: 12px; margin-top: 12px;">{{ __('labels.use_below_link_to_reset_your_password') }}</p>
<a href="{{$data['reset_password_link']}}" style="font-family: 'Urbanist', sans-serif;  font-size:18px;font-weight:700; margin-bottom: 12px; margin-top: 12px;">{{ __('labels.password_reset_link') }}</a>
<p style="font-family: 'Urbanist', sans-serif;color:#707070; font-size:15px;font-weight:400;">{{ __('labels.above_link_will_expire_in_10_mins') }}</p>
<p style="font-family: 'Urbanist', sans-serif;color:#707070; font-size:15px;font-weight:400;">{{ __('labels.If_you_did_not_send_the_request_please_review_your_account') }}</p>
@endsection