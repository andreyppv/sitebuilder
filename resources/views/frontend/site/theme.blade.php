@extends('frontend.layouts.blank')

@section('content')

<div class="theme_title">        
    <div class="span12">                
        <h1 class="theme_head">Choose Template</h1>
        <h5 class="theme_sub_head">You can easily change this later</h5>
    </div>                
</div>

<div class="themeselectionOuter">
    <div id="themeselection">
        <div id="sucess_disp"></div>
        <div class="row-fluid">
            
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

@stop

@section('additional')
@stop

@section('scripts')
@include('frontend.site.urls')  
<script src="{{ asset('scripts/pages/site/theme.js') }}"></script>
@stop