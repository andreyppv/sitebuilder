@extends('frontend.layouts.home')

@section('content')

<div class="indexCont" id="order_content">
    @include('frontend.home.order')
</div>

<div class="indexHowitWork">
    @include('frontend.home.work')
</div>

<div class="indexCont" id="feature_content">
    @include('frontend.home.feature')
</div>

<div class="indexCont" id="signup_content">
    @include('frontend.home.signup')
</div>

@stop

@section('scripts')
<script>
ajaxLoginURL    = '{{ URL::route("ajax.login") }}';
ajaxRegisterURL = '{{ URL::route("ajax.register") }}';
registerSuccessURL = '{{ URL::route("register.success") }}';
</script>
<script src="{{ asset('scripts/pages/home.js') }}"></script>
<script src="{{ asset('scripts/pages/home_login.js') }}"></script>
<script src="{{ asset('scripts/pages/home_register.js') }}"></script>
@stop