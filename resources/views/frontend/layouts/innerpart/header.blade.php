<div class="headerSec">
    <div class="containerSec">
        <div class="TopMenuNav clearfix">
            <div class="span3 bgblue">
                <a href="{{ url('/') }}" class="inlineblock mar-t1220">
                    <img src="{{ asset('images/logo1.png') }}" alt="logo" title="{{ $settings['site.title'] }}" width="130"/>
                </a>
            </div>
            <ul class="span1 unstyled TopMenuNavListSec pull-right whitebg">
                <li class="main_rightNav_opt span2 offset0 autowidth pull-right"><a class="navListtop autowidth listsites" id="dropdown-menu"><i class="icon icon-arrow-down"></i></a></li>
            </ul>
            <ul class="span8 unstyled TopMenuNavListSec pull-right ">
                <li class="main_rightNav_pub widthsixColumn offset0 pull-right">
                    <label id="_publishPop" for="publishbutton" class="navListtop">Publish</label>
                    <button id="publishbutton" style="display:none;"></button>
                </li>
                <li class="widthsixColumn offset0 pull-right"><a id="menu-settings" class="navListtop @if($menu=='settings') active @endif" href="{{ route('site.settings') }}"><span class="arrow"></span> Site Settings</a></li>
                <li class="widthsixColumn offset0 pull-right"><a id="menu-store" class="navListtop showTopTogg @if($menu=='store') active @endif" href="{{ route('site.store') }}"><span class="arrow"></span> Store</a></li>
                <li class="widthsixColumn offset0 pull-right"><a id="menu-pages" class="navListtop mainPageTogg @if($menu=='pages') active @endif" href="{{ route('site.pages') }}"><span class="arrow"></span>  Pages</a></li>
                <li class="widthsixColumn offset0 pull-right"><a id="menu-customize" class="navListtop @if($menu=='customize') active @endif" href="{{ route('site.customize') }}"><span class="arrow"></span> Customize</a></li>
                <li class="headerNavBorLft widthsixColumn offset0 pull-right"><a id="menu-builder" class="navListtop @if($menu=='create') active @endif" href="{{ route('site.builder') }}"><span class="arrow"></span> Create</a></li>
            </ul>
        </div>
    </div>
    <ul style="display:none" id="topoptions">
        <li><a id="btn-full-screen">Full Screen</a></li>
        <li><a href="{{ URL::route('site.exit.session', 'domain') }}">Domains</a></li>
        <li><a href="{{ URL::route('site.exit.session') }}">Exit Editor</a></li>
    </ul>

</div>