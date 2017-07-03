@extends('frontend.layouts.inner')

@section('content')
    
    @include('frontend.site.customize.left')

    <div class="mainRightPanel pull-right" style="padding-right:0;">
        <ul id="theme-pages" class="menuTopUl">       
            @foreach($site->pages as $i => $pg)
                @if($pg->type == 'basic')
                <li class="navTabSub @if($pg->id==$page->id) active @endif"><a data-index="{{ $pg->id }}">{{ $pg->title }}</a></li>    
                @endif
            @endforeach
        </ul>
        
        <div id="content-edit">
            <div id="iframe-box">
                <div id="content-iframe">
                    @include('frontend.site.element.iframe')            
                </div>
            </div>
            
            <!--theme list-->
            <div id="theme-list-box" style="display:none;">
                <div class="themeselectionOuter chooseDomainPop container borderbox">
                    <div class="ChooseThemeTxt">Choose a Theme </div>
                    <div class="row-fluid marTop40">
                        @foreach($themes as $i => $theme)
                        <!--start theme-->
                        <div class="span6 themeMenuWidt @if($i%2 == 0) offset0 @endif">
                            <div class="themeBg">
                                <div class="preview-box">
                                    <img src="{{ $theme->imgPath($theme->mainColor()->image) }}" class="preview-image">
                                </div>
                                <div class="themeMenu">    
                                    <div class="row-fluid">
                                        <ul class="theme-colors span7">
                                            @foreach($theme->colors as $i => $cls)
                                            <li style="background-color:{{ $cls->value }}" class="themeclrselected @if($i == 0) active @endif" data-image="{{ $theme->imgPath($cls->image) }}" data-index="{{ $cls->id }}"></li>
                                            @endforeach
                                        </ul>
                                        <button class="btn btn-success pull-right marRht10 btn-select-theme" value="{{ $theme->id }}">Choose</button>
                                    </div>
                                </div>
                                <div class="themeMenu1">
                                    <div class="top_theme">
                                        <ul class="top_theme_list">
                                            @foreach($theme->pages as $pg)
                                            <li><a href="#">{{ ucfirst($pg->name) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>  
                            </div>                                
                        </div> 
                        <!--end theme-->
                        @endforeach    
                    </div>
                </div>                                
            </div>
            <!--end theme list-->
        </div>
    </div>
    
@stop

@section('additional')
@include('frontend.site.builder.additional')  
@stop

@section('styles')
@stop

@section('scripts_plugin')   
<script src="{{ asset('vendor/build-plugins.js') }}"></script>
@stop
@section('scripts_common')
@include('frontend.site.urls')
@stop

@section('scripts')    
common.pageBuilder = true;
<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/builder.js') }}"></script>
<script src="{{ asset('scripts/pages/site/iframe.js') }}"></script>
<script src="{{ asset('scripts/pages/site/customize.js') }}"></script>
@stop