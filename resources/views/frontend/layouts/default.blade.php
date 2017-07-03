<!DOCTYPE html>
<html lang="en">
<head>

@include('frontend.layouts.partials.head')

</head>

<body class="cbp-spmenu-push indexBg">

<div id="loading"></div>

@include('frontend.layouts.partials.header')  

<div class="row-fluid">
    <div id="container" class="container">
        <div class="padtop20 pad-l-r-10">
            @yield('content')
        </div>
    </div>
</div>

<!--Loading-->
@include('frontend.layouts.partials.mask')
<!--End Loading-->

@yield('additional')

@include('frontend.layouts.partials.foot')

@include('frontend.layouts.partials.notifications')
</body>
</html>