@extends('frontend.layouts.default')

@section('content')

<div class="promotionBanner"></div>

<div class="container-fluid">
    <div class="row-fluid marTop20">
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/built-edit.png') }}" title="built-edit" alt="built-edit"></div>
            <div class="promotionCatDesHead">Build Editor</div>
            <div class="promotionCatDes">Building website on {{ $settings['site.title'] }} is unlike anything you've ever experienced</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/mobile-service.png') }}" title="mobile-service" alt="mobile-service"></div>
            <div class="promotionCatDesHead">Mobile Options</div>
            <div class="promotionCatDes">The content of your website will automatically adapt to match the...</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/themes.png') }}" title="themes" alt="themes"></div>
            <div class="promotionCatDesHead">Themes</div>
            <div class="promotionCatDes">{{ $settings['site.title'] }} offers over 100 professionally designed themes to choose from...</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/hosting.png') }}" title="hosting" alt="hosting"></div>
            <div class="promotionCatDesHead">Hostings</div>
            <div class="promotionCatDes">{{ $settings['site.title'] }} isn't just easiest website creation service - we are also a rock</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
    </div>
    <div class="row-fluid marTop20">
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/blogging.png') }}" title="blogging" alt="blogging"></div>
            <div class="promotionCatDesHead">Blogging</div>
            <div class="promotionCatDes">If you've used other blogging services, {{ $settings['site.title'] }} should be quite refreshing</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/photo.png') }}" title="photo" alt="photo"></div>
            <div class="promotionCatDesHead">Photos</div>
            <div class="promotionCatDes">It's easy to show off your photos in beautiful,professional photo galleries..</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/forms.png') }}" title="forms" alt="forms"></div>
            <div class="promotionCatDesHead">Forms</div>
            <div class="promotionCatDes">Our Click from builder makes it easy for you to collect information from</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
        <div class="promotionBgCategory span3">
            <div class="promotionCatDesImg"><img src="{{ asset('images/promotiondomain.png') }}" title="promotiondomain" alt="promotiondomain"></div>
            <div class="promotionCatDesHead">Domains</div>
            <div class="promotionCatDes">Your domain is the address where people find your website online</div>
            <a class="readmore" href="#">&gt;&gt;read more</a>
        </div>
    </div>
    <div class="row-fluid dc marTop20">
        <a href="javascript:void(0);" class="morefeature">More Features</a>
    </div>
</div>
<div class="row-fluid dc marTop20">
    <div class="promotionHelp"><span>Need help?</span>Call our support team 24/7 at 315-505-505</div>
    <div class="promotionHelpInner"></div>
</div>

@stop

@section('scripts')

@stop