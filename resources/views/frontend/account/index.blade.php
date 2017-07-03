@extends('frontend.layouts.default')

@section('content')

<div class="HeadingUser">Account Settings</div>
<div class="clr"></div>
<div class="MyAccPageOuter">
    <div class="MyAccPageInner">
        <div class="row-fluid">
            @include('frontend.account.general')
        </div>
        
        <div class="row-fluid marTop20">
            @include('frontend.account.additional')
        </div>
    </div>
</div>

@stop

@section('additional')
@include('frontend.account.modals')
@stop

@section('scripts')
<script>
accountURL = '{{ URL::route("profile") }}';
ajaxUpdatePasswordURL   = '{{ URL::route("ajax.update.password") }}';
ajaxUpdateEmailURL      = '{{ URL::route("ajax.update.email") }}';
ajaxUpdateNameURL       = '{{ URL::route("ajax.update.name") }}';
</script>
<script src="{{ asset('scripts/pages/account.js') }}"></script>
@stop