<!--<div class="mainLeftPanel overhidden fixeddiv span3" style="padding:10px;">-->
<div class="mainLeftPanel overhidden span3" style="padding:10px;">
    <h2 class="mainpage-tit">Main Pages</h2>
    <ul class="menuUl marTop20 mainLeftPanelScrollActive LeftNone">
        @foreach($site->pages as $i => $pg)
            @if($pg->type == 'basic')
            <li class="posRel pageRow">
                <a data-index="{{ $pg->id }}" @if($pg->id == $page->id) class="active" @endif><img title="tablist" alt="tablist" src="{{ asset('images/tablist.png') }}" class="controlMidBg"> <span>{{ $pg->title }}</span></a>
            </li>
            @endif
        @endforeach
    </ul>
    
    <h2 class="mainpage-tit">Static Pages <a id="btn-add-new-page" class="dropdown-toggle addPageTab pull-right">Add Page +</a></h2>
    <ul class="menuUl marTop20 mainLeftPanelScrollActive LeftNone">
        @foreach($site->pages as $i => $pg)
            @if($pg->type == 'static')
            <li class="posRel pageRow">
                <a data-index="{{ $pg->id }}" @if($pg->id == $page->id) class="active" @endif><img title="tablist" alt="tablist" src="{{ asset('images/tablist.png') }}" class="controlMidBg"> <span>{{ $pg->title }}</span></a>
            </li>
            @endif
        @endforeach
    </ul>
</div>