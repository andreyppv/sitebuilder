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
        <div id="orderssetting">
            <div id="listcat">
                <div class="storeDahboradHead clearfix">Orders</div>
            </div>
            
            <div id="repSearch" class="dataTables_wrapper">
                <table class="table table-bordered table-striped table-hover dataTable" id="order-table">
                <colgroup>
                    <!--<col width="50"/>-->
                    <col width=""/>
                    <col width="250"/>
                    <col width="150"/>
                    <col width="100"/>
                    <col width="130"/>
                    <col width="100"/>
                    <col width="80"/>
                </colgroup>
                <thead>
                    <tr>
                        <!--<th class="text-center">No</th>-->
                        {!! sort_th('site.store.order.list', 'Name', 'name') !!}  
                        {!! sort_th('site.store.order.list', 'Email', 'email') !!}  
                        {!! sort_th('site.store.order.list', 'Phone', 'phone') !!}  
                        {!! sort_th('site.store.order.list', 'Price', 'price') !!}  
                        {!! sort_th('site.store.order.list', 'Created', 'created_at') !!}  
                        {!! sort_th('site.store.order.list', 'status', 'status') !!}  
                        <th class="text-center"></th>
                    </tr>
                </thead>                                 
                <tbody>
                    @foreach($orders as $row)
                    <tr>
                        <!--<td></td>-->
                        <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td>{{ $row->payer_email }}</td>
                        <td class="text-center">{{ $row->phone_no }}</td>
                        <td class="text-right">{{ $row->grand_total }}</td>
                        <td class="text-center">{{ date('Y-m-d H:i', strtotime($row->created_at)) }}</td>
                        <td>{{ $row->status }}</td>
                        <td class="text-center"><a class="btn-view-order-detail">View</a></td>
                    </tr>
                    @endforeach
                
                    @if($orders->isEmpty())
                    <tr>
                        <td colspan="20">There is no data.</td>
                    </tr>
                    @endif
                </tbody>   
                </table>
            </div>
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