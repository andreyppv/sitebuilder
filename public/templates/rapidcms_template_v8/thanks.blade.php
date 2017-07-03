<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<base href="{{ $base_url }}">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>{{ isset($page->title) ? $page->title : "" }}</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/eleganticons.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/build.css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,900,700,300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
</head>
<body>
	<header><div class="container" id="header_wrapper">
        	<div class="row">
            	<div class="col-lg-12">
                	<div class="header_top"></div>
                </div>
            	<div class="col-lg-6 col-md-6">
                    <h1 class="main_heading" id="main_heading">{!! isset($params["main_heading"]) ? $params["main_heading"]->content : "" !!}</h1>
                    <h2 class="main_sublte" id="main_sublte">{!! isset($params["main_sublte"]) ? $params["main_sublte"]->content : "" !!}</h2>
                </div>
                <div class="col-lg-6 col-md-6">
                    <h3 class="main_heading_sublte" id="main_heading_sublte">{!! isset($params["main_heading_sublte"]) ? $params["main_heading_sublte"]->content : "" !!}</h3>
                </div>
            </div>
        </div>
        <div class="header_bottom"></div>
    </header><section class="banner thanks_banner" id="banner_wrapper"><div class="container">
        	<div class="row">
            	<div class="col-lg-12 col-md-12">
                	<img src="{{ (isset($params['thanks_picture']) && ($params['thanks_picture']->modified == 1)) ? url($params['thanks_picture']->content) : 'img/thanks.png' }}" class="img-responsive center-block thanks_img" id="thanks_picture"><h3 class="thanks_heading" id="thanks_heading">{!! isset($params["thanks_heading"]) ? $params["thanks_heading"]->content : "" !!}</h3>
                    <h3 class="thanks_sublte" id="thanks_sublte">{!! isset($params["thanks_sublte"]) ? $params["thanks_sublte"]->content : "" !!}</h3>
                </div>
<!--END COL 12-->
                <div class="clearfix"></div>
                <div class="col-lg-12 ordermargintop">
                	<h1 class="order_heading" id="order_heading">{!! isset($params["order_heading"]) ? $params["order_heading"]->content : "" !!}</h1>
                	<div class="order_summary" id="order_summary_wrapper">
       					 <div id="no-more-tables">
            <table class="col-md-6 col-sm-6  table-condensed cf"><tbody>
<tr id="order_id">
<td>Order ID :</td>
        				<td>83</td>
					</tr>
<tr id="order_date">
<td>Order date :</td>
        				<td>03-11-2015</td>        				
        			</tr>
<tr id="order_payment">
<td>Payment type :</td>
        				<td>Credit card</td>        				
        			</tr>
<tr id="order_product">
<td>Product name :</td>
        				<td>Diet pro test</td>        				
        			</tr>
</tbody></table>
<table class="col-md-6 col-sm-6  table-condensed cf"><tbody>
<tr id="order_id_1">
<td>Order ID :</td>
        				<td>83</td>
        			</tr>
<tr id="order_date_1">
<td>Order date :</td>
        				<td>03-11-2015</td>
        			</tr>
<tr id="order_payment_1">
<td>Payment type :</td>
        				<td>Credit card</td>
        			</tr>
<tr id="order_product_1">
<td>Product name :</td>
        				<td>Diet pro test</td>
        			</tr>
</tbody></table>
</div>
        </div>
        </div>
        </div>
        </div>
    </section><div class="sep40"></div>
    <!-- blank space section-->
	<section class="blank_space " id="blank_wrapper"><div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div id="blankspace" class="blankspace ">@include('frontend.site.element.dropbox')</div>
				</div>
			</div>
		</div>
	</section><!-- blank space section end--><footer><div class="container" id="footer_wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center terms">
                        <a href="" id="footer_link_1">{!! isset($params["footer_link_1"]) ? $params["footer_link_1"]->content : "" !!}</a>    |    <a href="" id="footer_link_2">{!! isset($params["footer_link_2"]) ? $params["footer_link_2"]->content : "" !!}</a>     |     <a href="" id="footer_link_3">{!! isset($params["footer_link_3"]) ? $params["footer_link_3"]->content : "" !!}</a>  
                    </p>
                    <p class="text-center address" id="address_text">{!! isset($params["address_text"]) ? $params["address_text"]->content : "" !!}</p>
                    <p class="text-center legal" id="legal_text">{!! isset($params["legal_text"]) ? $params["legal_text"]->content : "" !!}</p>
                </div>
            </div>
        </div>
    </footer><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script src="js/main.js" type="text/javascript"></script><script src="js/bootstrap.min.js" type="text/javascript"></script><script src="js/jquery.easing.1.3.js"></script>
</body>
</html>
