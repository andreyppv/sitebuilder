@extends('emails.template')
@section('content')
	<h2>Password Reset</h2>

	<div>
		To reset your password, complete this form: 
        {{ URL::route('customer.reset', [$token]) }}.<br /> 
        This link will expire in 60 minutes.
	</div>
@stop
