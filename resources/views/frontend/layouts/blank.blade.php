<!DOCTYPE html>
<html lang="en">
<head>

@include('frontend.layouts.partials.head')

</head>

<body class="cbp-spmenu-push indexBg">

<div id="loading"></div>

<div id="common_wrapper" class="bggray clearfix">
    <div class="container center bg-white">
        <a class="inlineblock logo" href="{{ url('/') }}">
            <img src="{{ asset('images/logo1.png') }}" alt="logo" title="{{ $settings['site.title'] }}" />
        </a>
    </div>

    <div class="row-fluid">
        <div id="container" class="container">
            @yield('content')
        </div>
    </div>

    <!--Loading-->
    <div class="ui-loader">
        <div class="deleteLoadInn">
            <div class="loadImg"><img title="" alt="" src="{{ asset('images/loading_circle.gif') }}"></div>
            <div class="loadTxt">Loading...</div>
        </div>
    </div>
    <div id="maska"></div>
    <div style="display:none;" id="loaderMask"></div>
    <!--End Loading-->

    @yield('additional')
</div>

@include('frontend.layouts.partials.foot')

@include('frontend.layouts.partials.notifications')
</body>
</html>