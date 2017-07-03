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
<style>
            .borderCss {
                border:1px solid #639DBC;
                box-shadow: 1px 1px 4px 5px rgba(99,157,188,1);
                cursor: pointer;
            }
            .highlight{
                background-color:#dadada;
                color:red;
            }

        </style>
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
        </header><section class="banner checkout_banner" id="banner_wrapper"><div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7">
                        <div class="col-lg-12 no-gutter">
                            <div class="review_order" id="banner_review_order">
                                <div class="subtotal_order">
                                    <p>SUBTOTAL :</p>
                                    <div class="order_num">45</div>
                                </div>

                                <div class="shipping_order">
                                    <p>SHIPPING :</p>
                                    <div class="order_num">45</div>
                                </div>
                                <div class="total_order">
                                    <p class="no_border">TOTAL :</p>
                                    <div class="order_num">45</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 no-gutter">
                            <div class="product col-lg-4 col-md-4 no-gutter ">
                                <div id="descrpition_picture_order" class="descrpition_picture_order">
                    @if($params["descrpition_picture_order"]->content != "")
                    <img src="{{url($params["descrpition_picture_order"]->content)}}" width="100%" height="100%"/>
                    @else<div id="form_picture" class="upload-icon text-center "><span aria-hidden="true" class="icon-upload "/></div>@endif
                </div>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <p class="legal_text " id="banner_legal_text">{!! isset($params["banner_legal_text"]) ? $params["banner_legal_text"]->content : "" !!}</p>
                            </div>
                        </div>

                    </div>
<!--END COL 7-->

                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <div class="form_home form_home_check">
                            <h3 id="form_heading" class="form_heading checkout_form text-center">{!! isset($params["form_heading"]) ? $params["form_heading"]->content : "" !!}</h3>
                            <div class="upload-icon text-center ">
                                <span aria-hidden="true" class=" icon-upload "></span>
                            </div>

                            <form class="datainfo">
                                <input type="text" placeholder="Name on card" class="card textfield "><div class="col-lg-6 no-gutter-card">
                                    <select class="month form-control expire_month "><option>Expire month</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option></select>
</div>
                                <div class="col-lg-6 no-gutter">
                                    <select class="year form-control expire_month "><option>Expire year </option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option></select>
</div>
                                <input type="text" placeholder="Security Code" class="code textfield "><input type="text" placeholder="Phone" class="phone textfield "><div class="terms checkbox checkbox-inline checkmar ">
                                    <input id="checkbox1" class="styled" type="checkbox"><label for="checkbox1">
                                        Review and accept <a href="">terms and conditions</a>
                                    </label>
                                </div>
                                <button class="but ordernow">SUBMIT</button>
                            </form>
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
