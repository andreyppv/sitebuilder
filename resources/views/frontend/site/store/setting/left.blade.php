<div id="store-settings-left" class="mainLeftPanel overhidden span3">
    <a class="goBack btn btn-warning frt marTop20" id="btn-back-to-store" href="{{ route('site.store') }}">Go Back</a>
    <div class="clearfix"></div>
    
    <ul class="menuUl ui-sortable">
        <li class="ui-sortable-handle">
            <a id="store-setting-general" class="@if($page=='general') active @endif" href="{{ route('site.store.settings') }}" data-target="#store-general-setting-box"><img title="storegeneral" alt="storegeneral" src="{{ asset('images/storegeneral.png') }}"> <span>General</span></a>    
        </li>
        <li class="ui-sortable-handle">
            <a id="store-setting-checkout" class="@if($page=='checkout') active @endif" href="{{ route('site.store.settings.checkout') }}" data-target="#store-checkout-setting-box"><img title="storecheckout" alt="storecheckout" src="{{ asset('images/storecheckout.png') }}"> <span>Checkout</span></a>    
        </li>
        <li class="ui-sortable-handle">
            <a id="store-setting-taxes" class="@if($page=='tax') active @endif" href="{{ route('site.store.settings.tax') }}" data-target="#store-taxes-setting-box"><img title="storetax" alt="storetax" src="{{ asset('images/storetax.png') }}"> <span>Taxes</span></a>    
        </li>
        <li class="ui-sortable-handle">
            <a id="store-setting-shipping" class="@if($page=='shipping') active @endif" href="{{ route('site.store.settings.shipping') }}" data-target="#store-shipping-setting-box"><img title="storeshipping" alt="storeshipping" src="{{ asset('images/storeshipping.png') }}"> <span>Shipping</span></a>    
        </li>
        <li class="ui-sortable-handle">
            <a id="store-setting-email" class="@if($page=='email') active @endif" href="{{ route('site.store.settings.email') }}" data-target="#store-email-setting-box"><img title="storeemail" alt="storeemail" src="{{ asset('images/storeemail.png') }}"> <span>Email</span></a>    
        </li>
    </ul>
</div>