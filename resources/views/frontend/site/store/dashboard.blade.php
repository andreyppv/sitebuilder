@extends('frontend.layouts.inner')

@section('content')

    @include('frontend.site.store.left')
    
    <div class="mainRightPanel pull-right span9">    
        <div id="dashboardsetting">
            <nav id="cbp-spmenu-s3" class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-top">
                <div class="storeDahboradHead clearfix">
                    Store Dashboard <a href="#">View Store</a>
                </div>
                <div class="tableOuterBor marTop20">
                    <table cellspacing="0" cellpadding="0" class="table no-mar">
                        <thead>
                            <tr>
                                <th class="dashBrdHead bgSkyBlue" colspan="3">Getting Started</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="dc"><span>1</span></td>
                                <td>
                                    <div class="TableStoreDashTop">Add Product</div>
                                    <div class="TableStoreDashBott">Add or import products to your store</div>
                                </td>
                                <td class="add_btn"><span><a id="dsh-btn-add-product" class="dashStorAdd">Add</a></span></td>
                            </tr>
                            <tr class="lightGrey">
                                <td class="dc"><span>2</span></td>
                                <td>
                                    <div class="TableStoreDashTop">Add Store Information</div>
                                    <div class="TableStoreDashBott">This information will appear on invoices to customers</div>
                                </td>
                                <td class="add_btn"><span><a id="dsh-btn-store-setting" class="dashStorAdd">Add</a></span></td>
                            </tr>
                            <tr>
                                <td class="dc"><span>3</span></td>
                                <td>
                                    <div class="TableStoreDashTop">Accept Payments</div>
                                    <div class="TableStoreDashBott">Choose how to collect money from customers</div>
                                </td>
                                <td class="add_btn"><span><a id="dsh-store-setting-checkout" class="dashStorAdd">Add</a></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </nav>
        </div>
    </div>
@stop

@section('additional')
@stop

@section('styles')
@stop

@section('scripts')
@include('frontend.site.urls')                                              

<script src="{{ asset('scripts/pages/site/common.js') }}"></script>
<script src="{{ asset('scripts/pages/site/store.js') }}"></script>
@stop