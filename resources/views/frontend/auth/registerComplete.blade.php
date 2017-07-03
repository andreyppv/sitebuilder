@extends('frontend.layouts.home')

@section('content')

<div class="container">
    <div class="forgotHead">Your Password</div>
    <div class="resetContainer">
        <form name="resetform" id="resetform" class="no-mar" method="post" action="{{ URL::route('register.complete.post') }}">
            <div class="dc">
                <div class="resetpasswordImg"></div>
            </div>
            <div class="clr"></div>
            <div class="cont">Please enter your password below.</div>
            <div class="resetInner form">
                <label>Password</label>
                @if($errors->has('password'))
                    <div class="errormsg">{{ $errors->first('password', ':message') }}</div>
                @endif
                {!! Form::password('password', array('id' => 'password', 'required')) !!}
                <div class="clr"></div>
                
                <label>Email address</label>
                @if($errors->has('password_confirm'))
                    <div class="errormsg">{{ $errors->first('password_confirm', ':message') }}</div>
                @endif
                {!! Form::password('password_confirm', array('id' => 'password_confirm', 'required')) !!}
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