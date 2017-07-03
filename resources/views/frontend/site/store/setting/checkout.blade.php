@extends('frontend.layouts.inner')

@section('content')

    @include('frontend.site.store.setting.left')
    
    <div class="mainRightPanel pull-right span9">    
        
    </div>
@stop

@section('additional')
@stop

@section('styles')
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/store_settings.js') }}"></script>
@stop