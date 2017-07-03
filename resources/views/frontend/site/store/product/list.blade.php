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
        <div id="productssetting">
            <div id="product-list-box">
                <div id="listcat">
                    <div class="storeDahboradHead clearfix">
                        Products 
                        
                        <a id="btn-add-product">Add Product</a>
                        <a id="btn-import-products">Import Products</a>
                    </div>
                </div>
                
                <div id="repSearch" class="dataTables_wrapper">
                    <table class="table table-bordered table-striped table-hover" id="product-table">
                    <colgroup>
                        <col width=""/>
                        <col width="200"/>
                        <col width="120"/>
                        <col width="130"/>
                        <col width="140"/>
                        <col width="100"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Campaign</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Rebill Price</th>
                            <th class="text-center">Shipping Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>                                 
                    <tbody>
                        @foreach($products as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td class="text-left">{{ $row->campaign->name }}</td>
                            <td class="text-right">{{ format_currency($row->price) }}</td>
                            <td class="text-right">{{ format_currency($row->rebill_price) }}</td>
                            <td class="text-right">{{ format_currency($row->shipping->price) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-product-edit" data-index="{{ $row->id }}"><i class="icon-pencil margin5"></i></button>
                                <button type="button" class="btn btn-danger btn-product-remove" href="{{ route('site.store.product.delete', $row->id) }}"><i class="icon-remove margin5"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    
                        @if($products->isEmpty())
                        <tr>
                            <td colspan="20">There is no data.</td>
                        </tr>
                        @endif
                    </tbody>   
                    </table>
                </div>
            </div>
            
            <div id="product-form-box">
                
            </div>
        </div>
    </div>

@stop

@section('styles')
<link href="{{ asset('fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('vendor/build-plugins.js') }}"></script>
<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/store.js') }}"></script>
@stop