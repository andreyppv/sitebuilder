@extends('frontend.layouts.inner')

@section('content')
    
    @include('frontend.site.builder.left')

    <div class="mainRightPanel pull-right" style="padding-right:0;">
        <ul id="theme-pages" class="menuTopUl">       
            @foreach($site->pages as $i => $pg)
                @if($pg->type == 'basic')
                <li class="navTabSub @if($pg->id==$page->id) active @endif"><a data-index="{{ $pg->id }}">{{ $pg->title }}</a></li>    
                @endif
            @endforeach
        </ul>
        
        <div id="content-edit"><div id="content-iframe">
            @include('frontend.site.element.iframe')            
        </div></div>
    </div>
    
@stop

@section('additional')
@include('frontend.site.builder.additional')  
@stop

@section('styles')
@stop

@section('scripts_plugin')   
@stop

@section('scripts_common')
@include('frontend.site.urls')
<script src="{{ asset('vendor/build-plugins.js') }}"></script>
@stop

                        
@section('scripts')   
common.pageBuilder = true;
<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/builder.js') }}"></script>
<script src="{{ asset('scripts/pages/site/iframe.js') }}"></script>
@stop