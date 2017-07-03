<div class="header">
    <div class="container">
        <div class="newtopmenunav TopMenuNav clearfix">
            <ul class="row unstyled TopMenuNavList">
                <li class="sitelogo">
                    <a class="inlineblock" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo1.png') }}" alt="logo" title="{{ $settings['site.title'] }}" />
                    </a>
                </li>
                <li class="indexBannerDiv frt">
                    <div class="indexBannerHead">
                        <ul class="Indexnav">
                            <li class="menu">
                                <a id="menuclick" class="menuclick">Menu <img src="{{ asset('images/menu.png') }}" alt="menu" title="menu" /></a>
                                <ul id="menuList" class="menuList" style="display:none;">
                                    <span class="arrow"></span>
                                    <li class="menuListIcon borTopNone">
                                        <a href="{{ url('/') }}" class="menuHome">
                                            <img class="frontindex" src="{{ asset('images/home.png') }}" alt="home" title="home" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indexhome.png') }}" alt="indexhome" title="indexhome" />
                                                <p>Home</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="menuListIcon menuListIconListMar">
                                        <a id="feature_content" class="menuFeature">
                                            <img class="frontindex" src="{{ asset('images/features.png') }}" alt="features" title="features" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indexfeatures.png') }}" alt="indexfeatures" title="indexfeatures" />
                                                <p>Features</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="menuListIcon menuListIconListMar">
                                        <a id="createTheme" class="menuTheme">
                                            <img class="frontindex" src="{{ asset('images/themes2.png') }}" alt="themes2" title="themes2" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indextheme.png') }}" alt="indextheme" title="indextheme" />
                                                <p>Themes</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="menuListIcon">
                                        <a id="createBlog" class="menuBlog">
                                            <img class="frontindex" src="{{ asset('images/blogging2.png') }}" alt="blogging2" title="blogging2" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indexblogging.png') }}" alt="indexblogging" title="indexblogging" />
                                                <p>Blog</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="menuListIcon menuListIconListMar">
                                        <a href="{{ URL::route('page.show.about') }}">
                                            <img class="frontindex" src="{{ asset('images/about.png') }}" alt="about" title="about" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indexabout.png') }}" alt="indexabout" title="indexabout" />
                                                <p>About</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="menuListIcon menuListIconListMar">
                                        <a href="{{ URL::route('site.contact') }}">
                                            <img class="frontindex" src="{{ asset('images/contact.png') }}" alt="contact" title="contact" />
                                            <div class="indexFearListTxt">
                                                <img class="frontindex" src="{{ asset('images/indexcontact.png') }}" alt="indexabout" title="indexabout" />
                                                <p>Contact</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="indexsocialConnect">
                                        <h1>Social Connect</h1>
                                        <ul>
                                            <li class="indexsocialConnectFb">
                                                <a href="www.facebook.com" target="_blank"><img src="{{ asset('images/fb2.png') }}" alt="facebook" title="facebook" /></a>
                                            </li>
                                            <li class="indexsocialConnectTw">
                                                <a href="www.twitter.com" target="_blank"><img src="{{ asset('images/twee.png') }}" alt="twiiter" title="twiiter" /></a>
                                            </li>
                                            <li class="indexsocialConnectGoog">
                                                <a href="www.google.com" target="_blank"><img src="{{ asset('images/g+.png') }}" alt="google" title="google" /></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="frt borNone loginPop dropdown">
                    <a id="loginPop" class="dropdown-toggle " data-toggle="dropdown">
                        Login <b class="caret"></b>
                    </a>

                    <div class="dropdown-menu offset0">
                        <form name="loginform" id="loginform" class="no-mar" method="post" action="">
                            <div class="headerDropDownList">
                                <div id="error_msglogin"><span class="icon-close"></span></div>
                                <div class="row-fluid">
                                    <div id="uemail">
                                        <input type="text" class="textbox bordertop_radius span12" name="user_email" id="user_email" value="" placeholder="Email/Username" value="" />
                                    </div>
                                    <input type="password" class="textbox span12 borderbottom_radius offset0" name="user_password" id="user_password" value="" placeholder="Password" />
                                    <div class="clr"></div>
                                    <div class="remember">
                                        <input type="checkbox" name="remember_me" id="remember_me" class="no-mar" value="Yes" /> Remember Me
                                    </div>
                                    <a href="{{ URL::route('forgot') }}" class="forgot">Forgot Password?</a>
                                    <input type="submit" class="loginPopButton" name="login" id="login" value="Login" />
                                </div>
                            </div>
                        </form>

                        <div class="marLftRhtLogPop">
                            <input type="hidden" name="facebook_api_value" id="facebook_api_value" value="469991483162991" />
                            <a class="btn-facebook btn-block">
                                <span class="text">Login with Facebook</span>
                            </a>
                        </div>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</div>

<div id="indexBanner" class="indexBanner">
    <div class="container">
        <div class="indexBannerCont">
            <div class="indexBannerContTop">A better web starts with your website</div>
            <ul class="indexBannerContBott">
                <li><img src="{{ asset('images/indexDott.png') }}" alt="indexDott" title="indexDott" /> Websites</li>
                <li><img src="{{ asset('images/indexDott.png') }}" alt="indexDott" title="indexDott" /> Domains</li>
                <li><img src="{{ asset('images/indexDott.png') }}" alt="indexDott" title="indexDott" /> Commerce</li>
                <li><img src="{{ asset('images/indexDott.png') }}" alt="indexDott" title="indexDott" /> Mobile</li>
                <li><img src="{{ asset('images/indexDott.png') }}" alt="indexDott" title="indexDott" /> 24/7 Support</li>
            </ul>
            <a id="btn-signup-now" class="indexBannerContButton">Sign Up Now !</a>
        </div>
    </div>
</div>