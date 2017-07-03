<div class="mySiteBg">
    <div class="container">
        <div class=" pad-l-r-10">
            <div class="mySiteBgTop bg-white  pad-l-r-10 clearfix">
                <a class="inlineblock" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo1.png') }}" alt="logo" title="{{ $settings['site.title'] }}" />
                </a>
                <div class="span4 text-right pull-right">
                    <a href="{{ URL::route('logout') }}" class="logoutdomain">Logout</a>
                    <span class="domainemail">{{ $current_customer->name }}</span>
                </div>
            </div>
        
            <ul class="mysiteNav clearfix">
                <li><a href="{{ URL::route('site.list') }}" class="@if($menu == 'site') active @endif">My Sites</a></li>
                <li><a href="{{ URL::route('transaction.list') }}" class="@if($menu == 'transaction') active @endif">My Transaction</a></li>    
                <li><a href="{{ URL::route('domain.list') }}" class="@if($menu == 'domain') active @endif">Domain</a></li>
                <li><a href="{{ URL::route('invite.list') }}" class="@if($menu == 'invite') active @endif">Invites</a></li>
                <li><a href="{{ URL::route('supports') }}" class="@if($menu == 'features') active @endif">Features</a></li>
                <li><a href="{{ URL::route('profile') }}" class="@if($menu == 'account') active @endif">Account</a></li>
            </ul>
        </div>
    </div>
</div>