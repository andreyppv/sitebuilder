@extends('frontend.layouts.home')

@section('content')

<div class="marTop20">
    <div class="container">
        <div class="sucriptionPage">
            <h2>{{ $pageTitle }}</h2>       
            <div class="row-fluid">
                {!! Form::open(array('url' => $formUrl, 'method' => 'post', 'id' => 'paymentform')) !!}            
                    {!! Form::hidden('domain', $domainName) !!}
                    {!! Form::hidden('productId', $productId) !!}
                    
                    <div class="span6" style="margin-left:0;">
                        <div class="newpaymentinner">
                            <div class="paypalNote mar10 blue">
                                <span class="domaincheckAvail">{{ $domainName }}</span>
                            </div>
                            
                            <div class="row-fluid marTop10">
                                <ul class="paymentDomainul">
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Hide your personal information in the public WHOIS directory</li>
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Prevent domain-related spam email</li> 
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Help stop attempts to hijack your domain</li> 
                                </ul>
                            </div>  
                            
                        </div>
                    </div>
                    <div class="span6">
                        @include('frontend.payment.paymentForm')
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script src="{{ asset('scripts/pages/payment.js') }}"></script>
@stop