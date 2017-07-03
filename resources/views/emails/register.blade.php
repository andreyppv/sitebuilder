@extends('emails.template')
@section('content')

<table>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size:14px; font-weight: bold; padding: 10px 0 0 10px; text-align:left;">Hi {{ $customer->name }}!</td>
    </tr>
    <tr>
        <td style="color: #619925; font-family: Trebuchet MS; font-size:14px; font-weight: bold; padding: 10px 0 0; text-align:center;"> 
            <span style="display: block; margin: 0px 10px;padding: 10px 0px;"> 
                <img moz-do-not-send="true" src="{{ asset('images/inviitemail.png') }}"/>You have been successfully registered in our site, 
            </span> 
        </td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size:14px; font-weight: bold; padding: 10px 0 0 10px; text-align:left;">Your Details Below</td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size:14px; padding: 10px 0 0 40px; text-align: left;">
            <b style="width:100px;display: inline-block;">Username</b> : {{ $customer->name }}
        </td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size:14px; padding: 10px 0 0 40px; text-align: left;">
            <b style="width:100px;display: inline-block;">Email</b> : {{ $customer->email }}
        </td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size:14px; padding: 10px 0 0 40px; text-align: left;">
            <b style="width:100px;display: inline-block;">Password</b> : Your chosen password
        </td>
    </tr>
    <tr>
        <td colspan="3" style="color: #333333; font-family: Trebuchet MS; font-size: 14px; font-weight: bold; padding: 30px 0 0 10px;" align="left" valign="top">Thanks</td>
    </tr>
    <tr>
        <td colspan="3" style="color: #393860; font-family: Trebuchet MS; font-size: 14px; font-weight: bold; padding: 10px 0px 20px 10px;" align="left" valign="top">{{ $settings['email.sender_name'] }}</td>
    </tr>
</table>

@stop
