@extends('frontend.layouts.default')

@section('content')

@if($pending)
<div class="label-warning" style="padding:10px; vertical-align: middle;">
    <div class="row-fluid">
        There is a pending in editing.
        
        <div class="pull-right">
            <input type="button" class="btn btn-default btn-for-pending" value="Continue" id="btn-continue-pending" data-href="{{ route('site.builder') }}"/>
            <input type="button" class="btn btn-default btn-for-pending" value="Ignore" id="btn-ignore-pending" data-href="{{ route('site.exit.session') }}"/>
        </div>
    </div>
</div>
@endif

<div class="HeadingUser">
    My Site
    <a class="success-btn pull-right" href="{{ URL::route('site.themes') }}"><i class="icon-plus maring10right"></i>Add Site</a>
</div>
<div class="clr"></div>
<div class="MyAccPageOuter">
    <div style="margin-top:10px;">
        <table class="userhometablecont table table-bordered table-striped table-hover dataTable" id="data-table">
            <colgroup>
                <col width="120"/>
                <col width="150"/>
                <col width="150"/>
                <col width="150"/>
                <col width=""/>
                <col width="220"/>
            </colgroup>
            <thead>
                <tr>
                    {!! sort_th('site.list', 'Status', 'status', 'center') !!}  
                    {!! sort_th('site.list', 'Product Type', 'product', 'center') !!}                          
                    {!! sort_th('site.list', 'Compnay Name', 'company', 'center') !!}                          
                    {!! sort_th('site.list', 'Offer Name', 'offername', 'center') !!}                          
                    {!! sort_th('site.list', 'URL', 'url', 'center') !!}                          
                    <th class="center">Action</th>
                </tr>
            </thead>
            <tbody>             
                @foreach($rows as $row)
                <tr>
                    <td>@if($row->status == STATUS_ACTIVE) <span class="active_bdr">Active</span> @else <span class="deactive_bdr">Deactive</span> @endif</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td><a class="ListContainUrl" href="{{ $row->getRealUrl() }}" target="_blank">{{ $row->getRealUrl() }}</a></td>
                    <td>
                        <a class="editdomainhome" href="{{ route('site.edit.session', $row->id) }}"><span class="text">Edit<i class="icon-cog margin5"></i></span></a>
                        
                        <span class="sitedomainoption">Options <span class="bg_option"><span class="caret margin10"></span></span></span>
                        <div class="domainoptiontoggle" style="display:none;">
                            <a><img src="{{ asset('images/clonewebsite.png') }}"/>Orders</a>
                            <a><i class="fa fa-share"></i> <span>Form Entries</span></a> 
                            <a><img src="{{ asset('images/editsite.png') }}"/>Edit or Point Domain</a>
                            <a><img src="{{ asset('images/websitestatus.png') }}"/>View Website Status</a>
                            <a><img src="{{ asset('images/clonewebsite.png') }}"/>Clone Website</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            
                @if($rows->isEmpty())
                <tr>
                    <td colspan="20">There is no data.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@stop

@section('styles')
<link href="{{ asset('fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop

@section('scripts')
<script src="{{ asset('scripts/pages/site/index.js') }}"></script>
@stop