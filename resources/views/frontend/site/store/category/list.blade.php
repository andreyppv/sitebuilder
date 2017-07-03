<?php
    $showOptions = [
        '15'  => 'Show 15 Categories', 
        '30'  => 'Show 30 Categories', 
        '50'  => 'Show 50 Categories', 
        'all' => 'Show All Categories'
    ];
?>

@extends('frontend.layouts.inner')

@section('content')

    @include('frontend.site.store.left')

    <div class="mainRightPanel pull-right span9">    
        <div id="category-list-box">
            <div id="storecategorysetting">
                <div id="listcat">
                    <div class="storeDahboradHead clearfix">Product Type <a id="btn-add-category">Add Product Type</a></div>
                    <!--<input type="text" value="" id="search_category" name="search_category" class="searchProduct">-->
                </div>
                
                <div id="repSearch" class="dataTables_wrapper">
                    <table class="table table-bordered table-striped table-hover dataTable" id="category-table">
                    <colgroup>
                        <col width=""/>
                        <col width="200"/>
                        <col width="200"/>
                        <col width="200"/>
                    </colgroup>
                    <thead>
                        <tr>
                            {!! sort_th('site.store.category.list', 'Name', 'name', 'left') !!}  
                            {!! sort_th('site.store.category.list', 'Status', 'status', 'center') !!}  
                            {!! sort_th('site.store.category.list', 'Created', 'created_at', 'center') !!}  
                            <th class="center">Action</th>
                        </tr>
                    </thead>                                 
                    <tbody>
                        @foreach($categories as $row)
                        <tr>
                            <td><a data-index="{{ $row->id }}" class="category-row-name">{{ $row->name }}</a></td>
                            <td class="center"><a data-index="{{ $row->id }}" class="category-row-status @if($row->status == STATUS_INACTIVE) inactive-img @else active-img @endif"></a></td>
                            <td class="center">{{ date('Y-m-d H:i', strtotime($row->created_at)) }}</td>
                            <td class="center"><a class="category-row-remove" href="{{ route('site.store.category.delete', $row->id) }}"><img class="curPoint" src="{{ asset('images/storeClose.png') }}" /></a></td>
                        </tr>
                        @endforeach
                    
                        @if($categories->isEmpty())
                        <tr>
                            <td colspan="20">There is no data.</td>
                        </tr>
                        @endif
                    </tbody>   
                    </table>
                    <!--{!! Form::select('page-limit', $showOptions, null, array('class' => 'selecthome')) !!}-->
                    
                    <div><div class="pagination pull-right">
                        {!! paginate_links_with_params($categories) !!}
                    </div></div>
                </div>
            </div>
        </div>
        
        <div id="category-form-box">
        </div>
    </div>

@stop

@section('additional') 
@stop

@section('styles')
<link href="{{ asset('fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/store.js') }}"></script>
@stop