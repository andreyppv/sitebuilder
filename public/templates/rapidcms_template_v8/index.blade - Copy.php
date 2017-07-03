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
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/build.css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,900,700,300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
<style>
        .borderCss {
            border: 1px solid #639DBC;
            box-shadow: 1px 1px 4px 5px rgba(99, 157, 188, 1);
            cursor: pointer;
        }
        
        .highlight {
            background-color: #dadada;
            color: red;
        }
    </style>
</head>
<body>
    <!-- headre section -->
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
    </header><!-- headre section end--><!--banner section--><section class="banner" id="banner_wrapper"><div class="container">
            <div class="row">
                <div class="girl"><img src="img/girl.png"></div>
                <div class="col-lg-7 col-md-7 no-gutter">
                    <div class="col-lg-12">
                        <div class="banner_heading_wrap ">
                            <h1 class="banner_heading content" id="banner_title">{!! isset($params["banner_title"]) ? $params["banner_title"]->content : "" !!}</h1>
                            <h2 class="banner_subtle content" id="banner_subtitle">{!! isset($params["banner_subtitle"]) ? $params["banner_subtitle"]->content : "" !!}</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="product_item ">
                            <img src="img/product_item.png" class="center-block" id="image--product_img">
</div>
                    </div>
                    <!--END COL 7-->
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="list">
                            <ul>
<li class="fea_1 ">
                                    <div class="dot"><span></span></div>
                                    <div id="feature_1">{!! isset($params["feature_1"]) ? $params["feature_1"]->content : "" !!}</div>
                                </li>
                                <li class="fea_2 ">
                                    <div class="dot"><span></span></div>
                                    <div id="feature_2">{!! isset($params["feature_2"]) ? $params["feature_2"]->content : "" !!}</div>
                                </li>
                                <li class="fea_3 ">
                                    <div class="dot"><span></span></div>
                                    <div id="feature_3">{!! isset($params["feature_3"]) ? $params["feature_3"]->content : "" !!}</div>
                                </li>
                                <li class="fea_4 ">
                                    <div class="dot"><span></span></div>
                                    <div id="feature_4">{!! isset($params["feature_4"]) ? $params["feature_4"]->content : "" !!}</div>
                                </li>
                            </ul>
</div>
                    </div>
                    <!--END COL 5-->

                </div>
                <!--END COL 7-->

                <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="form_home">
                        <h3 class="form_heading text-center content ">ORDER YOUR<br> FREE PACK</h3>
                        <form class="datainfo">
                            <input type="text" placeholder="Name" class="name textfield "><input type="text" placeholder="Last Name" class="lname textfield "><input type="text" placeholder="Street Address" class="address textfield "><input type="text" placeholder="City" class="city textfield "><input type="text" placeholder="Zip Code/Postal Code" class="code textfield "><select class="country form-control "><option>Select County</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option></select><select class="State form-control "><option>Select State</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option></select><input type="email" placeholder="Email" class="email textfield "><div class="term checkbox checkbox-inline checkmar ">
                                <input id="checkbox1" class="styled " type="checkbox"><label for="checkbox1">
                                    Review and accept <a href="#">terms and conditions</a>
                                </label>
                            </div>

                            <div class="submit1 but ordernow " style="text-align: center;padding-top: 15px;">ORDER NOW !</div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section><!--banner section end--><!-- description section--><section class="description" id="description_wrapper"><div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <h1 class="description_heading content">
                            <div class="dot"><span></span></div>
                            <div id="description_heading">{!! isset($params["description_heading"]) ? $params["description_heading"]->content : "" !!}</div>
                        </h1>
                    <p class="description_parragraph" id="description_paragraph">{!! isset($params["description_paragraph"]) ? $params["description_paragraph"]->content : "" !!}</p>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="descrpition_picture " id="descrpition_picture">
                    @if($params["descrpition_picture"]->content != "")
                    <img src="{{url($params["descrpition_picture"]->content)}}" width="100%" height="100%"/>
                    @else<img src="img/upload.png" class="img-responsive center-block"/>@endif
                </div>
                </div>

            </div>
        </div>

    </section><!-- description section end--><!-- blank space section--><section class="blank_space " id="blank_wrapper"><div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="blankspace" class="blankspace ">@include('frontend.site.element.items')</div>
                </div>
            </div>
		</div>
    </section><!-- blank space section end--><!-- footer section--><footer><div class="footerContainer container " id="footer_wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center terms ">
                        <a class="link1 " id="footer_link_1">{!! isset($params["footer_link_1"]) ? $params["footer_link_1"]->content : "" !!}</a> | <a class="link2 " id="footer_link_2">{!! isset($params["footer_link_2"]) ? $params["footer_link_2"]->content : "" !!}</a> | <a class="link3 " id="footer_link_3">{!! isset($params["footer_link_3"]) ? $params["footer_link_3"]->content : "" !!}</a>
                    </p>
                    <p class="footerAddress text-center address" id="address_text">{!! isset($params["address_text"]) ? $params["address_text"]->content : "" !!}</p>
                    <p class="ltext text-center legal " id="legal_text">{!! isset($params["legal_text"]) ? $params["legal_text"]->content : "" !!}</p>
                </div>
            </div>
        </div>
    </footer><!-- footer section end--><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script src="js/main.js" type="text/javascript"></script><script src="js/bootstrap.min.js" type="text/javascript"></script><script src="js/jquery.easing.1.3.js"></script>
</body>
</html>
