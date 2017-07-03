@extends('frontend.layouts.inner')

@section('content')

    @include('frontend.site.pages.content')

@stop

@section('additional')
@stop

@section('styles')
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/pages.js') }}"></script>
@stop