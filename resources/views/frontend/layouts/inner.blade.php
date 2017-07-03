<!DOCTYPE html>
<html lang="en">
<head>

@include('frontend.layouts.partials.head')

</head>

<body class="cbp-spmenu-push indexBg body-hidden js">

<div id="loading"></div>

<div id="common_wrapper" class="clearfix">
    <div class="clearfix"></div>
    <div class="row-fluid relatediv">
        @include('frontend.layouts.innerpart.header')
        
        <div id="inner-content" class="row-fluid clearfix">
            @yield('content')                                                
        </div>
    </div>
</div>

<!--Loading-->
@include('frontend.layouts.partials.mask')
<!--End Loading-->

@yield('additional')
@include('frontend.site.additional')

@include('frontend.layouts.partials.foot')
                
@include('frontend.layouts.partials.notifications')
</body>
</html>