<div class="indexFooter">
    <div class="container">
        <div class="indexFooterInn">
            <input type="hidden" name="facebook_api_value" id="facebook_api_value" value="469991483162991" />
            <div class="indexFooterHead">Build and maintain your own website for FREE!</div>
            <div class="indexFooterPara">We help you every step of the way</div>
            <a class="indexFooterSocial" href="{{ URL::route('register.facebook') }}"><img src="{{ asset('images/fb-signup.png') }}" alt="fb" title="fb" /></a>

            <div class="row-fluid">
                <div class="span6 indexFooterLeft">
                    <div id="show_case">
                        <div class="indexFooterLeftSlide">
                            <div class="indexFooterLeftUser"></div>
                            <div class="indexFooterSpace"></div>
                            <div class="indexFooterDesc">
                                <div class="indexFooterDescQuote"></div>
                                <div class="indexFooterDescPara">Just wanted to say thank you for all your support. I have published my web page without any hassles and am quite proud of it seeing as I am not that computer savvy. . . .</div>
                                <div class="indexFooterDescDate">Mark Antony, <b>Aug 15</b></div>
                            </div>
                        </div>
                        <div class="indexFooterLeftSlide" style="display:none;">
                            <div class="indexFooterDesc">
                                <div class="indexFooterDescQuote"></div>
                                <div class="indexFooterDescPara">Just wanted to say thank you for all your support. I have published my web page without any hassles and am quite proud of it seeing as I am not that computer savvy. . . .</div>
                                <div class="indexFooterDescDate">Mark Antony, <b>Aug 15</b></div>
                            </div>
                            <div class="indexFooterSpace"></div>
                            <div class="indexFooterLeftUser"></div>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="inndexFooterSignUp pull-right">
                        <div id="errormsg"></div>
                        <form name="registerform" id="registerform" method="post" class="no-mar">
                            <div class="indexFooterSignupInn">
                                <input class="indexFooterSignupInnLable" type="text" name="full_name" id="full_name" placeholder="Full Name" value="" />
                                <input class="indexFooterSignupInnLable" type="text" name="email" id="email" placeholder="Email" value="" />
                                <input class="indexFooterSignupInnLable" type="password" name="password" id="password" placeholder="Password" value="" />
                                <input class="indexFooterSignupInnButton" type="submit" name="signupsubmit" id="signupsubmit" value="Sign up it's Free" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="indexFooterStaticPage">
            <a href="{{ URL::route('page.show.terms') }}">Terms</a> | <a href="{{ URL::route('page.show.privacy') }}">Privacy</a> &copy; {{ date('Y') }}, {{ $settings['site.title'] }}, Inc.
        </div>
    </div>
</div>