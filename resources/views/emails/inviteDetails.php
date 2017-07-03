@extends('emails.template')
@section('content')

<table>
    <tr>
        <td style="color: #000000; font-family: Trebuchet MS; font-size: 14px; font-weight: bold; padding: 10px 0 0; text-align: left;"><span style="display: block; margin: 0px 10px; padding: 10px 0px;"><!--<img src="{INVITE_URL}">--> Hi,We're excited to let you know that <b style="color:#8A2BE2;">{{ $customer->name }}</b> has invited you to {{ $settings['site.title'] }}!</span></td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size: 14px; padding: 25px 0 20px 10px; text-align: left;"> <b style="color:#8A2BE2;">{{ $customer->name }}</b> has been using {{ $settings['site.title'] }} to easily create a website & blog and thought you might want to as well.</td>
    </tr>
    <tr>
        <td style="color: #333333; font-family: Trebuchet MS; font-size: 14px; font-weight: bold; padding: 30px 0 0 10px;" align="left" valign="top" colspan="3"><a style="color:#ef3f77; text-decoration:none;" href="{{ url('/') }}">Get started here</a></td>
    </tr>
    <tr>
        <td style="color: #393860; font-family: Trebuchet MS; font-size: 14px; font-weight: bold; padding: 10px 0px 20px 10px;" align="left" valign="top" colspan="3">The {{ $settings['site.title'] }} Team</td>
    </tr>    
</table>    
    
@stop