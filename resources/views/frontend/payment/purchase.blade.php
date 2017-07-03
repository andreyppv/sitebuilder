@extends('frontend.layouts.home')

@section('content')

<div class="marTop20">
    <div class="container">
        <div class="sucriptionPage">
            <h2>{{ $pageTitle }}</h2>       
            <div class="row-fluid">
                {!! Form::open(array('url' => URL::route('payment.new.post'), 'method' => 'post', 'id' => 'paymentform')) !!}            
                    {!! Form::hidden('domain', $domainName) !!}
                     
                    <div class="span6" style="margin-left:0;">
                        <div class="newpaymentinner">
                            <div class="paypalNote mar10 blue">
                                <span class="domaincheckAvail">{{ $domainName }}</span>
                                @if($domainAvailable == 'Yes')
                                <img class="domaincheckAvail" src="{{ asset('images/available.png') }}" alt="tickImg" title=""/>
                                @endif
                            </div>
                            
                            <div class="row-fluid marTop10">
                                <ul class="paymentDomainul">
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Your new domain is instantly online</li>
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>No setup whatsoever</li>
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Full ownership and DNS control</li>
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>Rock solid hosting included</li>
                                    <li><img src="{{ asset('images/tickImg.png') }}" alt="tickImg" title=""/>$100 in Google AdWords Advertising</li>
                                </ul>
                            </div>

                            <div class="paypalListInnerHead">Choose a term:</div>
                            <span id="priceplanerror" class="text-danger">{{ $errors->first('priceplan', ':message') }}</span>
                            <div class="paypalNote mar10 pad5">
                                <label class="subcriptionPlanHead clearfix">
                                    @if($domainAvailable != 'Yes')
                                        {!! Form::radio('priceplan', 1, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_one_year'], 'disabled')) !!}
                                    @else
                                        {!! Form::radio('priceplan', 1, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_one_year'])) !!}
                                    @endif
                                    
                                    <span class="subcriptionPlanName">1 year</span>
                                    <span class="subcriptionPlanPrice">$ {{ $settings['price_one_year'] }}/yr</span>
                                </label>
                            </div>
                            <div class="paypalNote mar10 pad5">
                                <label class="subcriptionPlanHead clearfix">
                                    @if($domainAvailable != 'Yes')
                                        {!! Form::radio('priceplan', 2, true, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_two_year'], 'disabled')) !!}
                                    @else
                                        {!! Form::radio('priceplan', 2, true, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_two_year'])) !!}
                                    @endif
                                    
                                    <span class="subcriptionPlanName">2 years</span>
                                    <span class="subcriptionPlanPrice">$ {{ $settings['price_two_year'] }}/yr</span>
                                </label>
                            </div>
                            <div class="paypalNote mar10 pad5">
                                <label class="subcriptionPlanHead clearfix">
                                    @if($domainAvailable != 'Yes')
                                        {!! Form::radio('priceplan', 5, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_five_year'], 'disabled')) !!}
                                    @else
                                        {!! Form::radio('priceplan', 5, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_five_year'])) !!}
                                    @endif

                                    <span class="subcriptionPlanName">5 years</span>
                                    <span class="subcriptionPlanPrice">$ {{ $settings['price_five_year'] }}/yr</span>
                                </label>
                            </div>
                            <div class="paypalNote mar10 pad5">
                                <label class="subcriptionPlanHead clearfix">
                                    @if($domainAvailable != 'Yes')
                                        {!! Form::radio('priceplan', 10, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_ten_year'], 'disabled')) !!}
                                    @else
                                        {!! Form::radio('priceplan', 10, false, array('class' => 'subcriptionPlanInput', 'data-price' => $settings['price_ten_year'])) !!}
                                    @endif
                                    
                                    <span class="subcriptionPlanName">10 years</span>
                                    <span class="subcriptionPlanPrice">$ {{ $settings['price_ten_year'] }}/yr</span>
                                </label>
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