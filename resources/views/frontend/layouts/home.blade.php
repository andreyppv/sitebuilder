<!DOCTYPE html>
<html lang="en">
<head>

@include('frontend.layouts.partials.head')

</head>

<body class="cbp-spmenu-push indexBg">

<div id="loading"></div>

<div id="common_wrapper" class="clearfix">
    @yield('content')    
</div>

@include('frontend.layouts.partials.foot')

@include('frontend.layouts.partials.notifications')
</body>
</html>