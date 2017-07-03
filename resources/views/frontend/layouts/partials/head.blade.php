<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token"/>

<title>@if($pageTitle != ''){{ $pageTitle }} | @endif {{ $settings['site.title'] }}</title>

<!--Main CSS-->
<!--<link href="{{ asset('styles/all_custom.css') }}" rel="stylesheet">-->
<link href="{{ asset('styles/all.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/animsition/animsition.css') }}" rel="stylesheet" >
<link href="{{ asset('vendor/alertify-js/alertify.css') }}" rel="stylesheet" >
<link href="{{ asset('vendor/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet">

<!--Page CSS-->
@yield('styles')

<!--Custom CSS-->
<link href="{{ asset('styles/custom.css') }}" rel="stylesheet">

<link rel="shortcut icon" href="{{ asset('images/favicon.png') }} "/>