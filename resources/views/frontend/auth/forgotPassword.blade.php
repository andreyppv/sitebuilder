@extends('frontend.layouts.home')

@section('content')

<div class="container">
    <div class="forgotHead">Forgot Password</div>
    <div class="resetContainer">
        <form name="resetform" id="resetform" class="no-mar" method="post" action="{{ URL::route('forgot.post') }}">
            <div class="dc">
                <div class="resetpasswordImg"></div>
            </div>
            <div class="clr"></div>
            <div class="cont">Please enter your email address below.</div>
            <div class="resetInner form">
                <label>Email address</label>
                @if($errors->has('email'))
                    <div class="errormsg">{{ $errors->first('email', ':message') }}</div>
                @endif
                {!! Form::email('email', null, array('id' => 'email', 'required')) !!}
                <div class="clr"></div>
                <input type="submit" class="btn btn-large btn-info" name="btn-send" id="btn-send" value="Send">
                <a class="btn btn-large btn-danger" href="{{ url('/') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>

@stop

@section('scripts')

<script>

</script>

@stop