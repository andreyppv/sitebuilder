@extends('frontend.layouts.home')

@section('content')

<div class="row-fluid">
    <div class="marTop75">
        <div class="container">
            <div class="usrHomeInner">
                <span style="color:red;margin-left:230px;"></span>
                <div class="thanksMsg">
                    <div class="thanksMsgTxt">Thank you for registering with us. Lets get started to built Site/Blog.,</div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')

<script>
$(document).ready(function(){
    timeout = '5000'; 
    window.onload = setInterval(refresh_box, timeout); 
    function refresh_box() {
        window.location.href = base_url + '/onboarding.php';
    }
});        
</script>

@stop