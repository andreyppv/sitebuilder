@extends('frontend.layouts.default')

@section('content')

<div class="HeadingUser">Transaction Details</div>
<div class="clr"></div>
<div class="MyAccPageOuter">
    <div style="margin-top:10px;">
        <table class="table table-bordered table-striped table-hover dataTable" id="data-table">
            <colgroup>
                <col width="130"/>
                <col width=""/>
                <col width="90"/>
                <col width="110"/>
                <col width="100"/>
                <col width="200"/>
            </colgroup>
            <thead>
                <tr>
                    {!! sort_th('transaction.list', 'Date', 'created_at', 'center') !!}  
                    {!! sort_th('transaction.list', 'Description', 'description', 'center') !!}                          
                    {!! sort_th('transaction.list', 'Type', 'type', 'center') !!}                          
                    {!! sort_th('transaction.list', 'Method', 'method', 'center') !!}                          
                    {!! sort_th('transaction.list', 'Amount', 'amount', 'center') !!}                          
                    {!! sort_th('transaction.list', 'Reference', 'reference', 'center') !!}                          
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                <tr>
                    <td class="center">{{ date('d, M Y', strtotime($r->created_at)) }}</td>
                    <td>{{ $r->description }}</td>
                    <td class="center">{{ $r->type }}</td>
                    <td class="center">{{ $r->method }}</td>
                    <td class="center">{{ '$ ' . $r->amount }}</td>
                    <td>{{ $r->transaction_id }}</td>
                </tr>
                @endforeach    
            </tbody>
        </table>
        
        <div class="pull-right">
            {!! paginate_links($rows) !!}
        </div>
    </div>
</div>

@stop

@section('styles')
<link href="{{ asset('fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop

@section('scripts')
@stop