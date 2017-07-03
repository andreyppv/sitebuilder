<div class="mainLeftPanel overhidden span3">
    <ul id="sortablePage" class="menuUl ui-sortable">
        <li class="ui-sortable-handle">
            <a class="clearfix @if($page=='dashboard') active @endif" id="store-dashboard" href="{{ route('site.store') }}"><img title="Dashboard" alt="Dashboard" src="{{ asset('images/stroeDashboard.png') }}"> <span>Dashboard</span></a>    
        </li>
        <li class="ui-sortable-handle">                
            <a class="clearfix @if($page=='product') active @endif" id="store-products" href="{{ my_route('site.store.product.list') }}"><img title="Products" alt="Products" src="{{ asset('images/storeproduct.png') }}"> <span>Products</span></a>
        </li>
        <li class="ui-sortable-handle">
            <a class="clearfix @if($page=='category') active @endif" id="store-category" href="{{ my_route('site.store.category.list') }}"><img title="Product Type" alt="Product Type" src="{{ asset('images/storecategory.png') }}"> <span>Product Type</span></a>
        </li>
        <li class="ui-sortable-handle">
            <a class="clearfix @if($page=='order') active @endif" id="store-orders" href="{{ my_route('site.store.order.list') }}"><img title="Orders" alt="Orders" src="{{ asset('images/storeorder.png') }}"> <span>Orders</span></a>
        </li>
        <li class="ui-sortable-handle">
            <a class="clearfix @if($page=='settings') active @endif" id="store-settings" href="{{ my_route('site.store.settings') }}"><img title="Settings" alt="Settings" src="{{ asset('images/storresetting.png') }}"> <span>Settings</span></a>
        </li>
    </ul>
</div>