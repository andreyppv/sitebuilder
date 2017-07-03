<?php
    $exp_monthlist  = creditcard_expire_months();
    $exp_yearlist   = creditcard_expire_years();
?>

<div class="newpaymentinner">            
    <div class="paymentHead">Payment Details</div>
    <span id="paymentdetails-msg" class="paymentdetails text-danger">{{ $errors->first('payment-type', ':message') }}</span>
    <div class="paypalListInner marTop20 clearfix">
        <span class="buyCreditInnerBgContLeft">
            <label class="buyCreditClick">    
                @if($domainAvailable != 'Yes')
                    {!! Form::radio('payment-type', PAYMENT_CREDITCARD, true, array('class' => 'radioButt', 'disabled', 'checked')) !!}
                @else
                    {!! Form::radio('payment-type', PAYMENT_CREDITCARD, true, array('class' => 'radioButt', 'checked')) !!}
                @endif
                
                <span class="textedit">Credit Card Payment</span>
                <img class="marTop10" src="{{ asset('images/creditcard.jpg') }}" alt="creditcard" title="" />
            </label>    
        </span>
        <span class="buyCreditInnerBgContLeft">
            <label class="buyCreditClick">    
                @if($domainAvailable != 'Yes')
                    {!! Form::radio('payment-type', PAYMENT_PAYPAL, false, array('class' => 'radioButt', 'disabled')) !!}
                @else
                    {!! Form::radio('payment-type', PAYMENT_PAYPAL, false, array('class' => 'radioButt')) !!}
                @endif
                
                <span class="textedit">Paypal Payment</span>
                <img class="marTop10" src="{{ asset('images/paypal.png') }}" alt="paypal" title="" />
            </label>    
        </span>
    </div>
    <div class="clr"></div>
    <div class="row-fluid marTop20">
        <div class="paypalListInnerHead">Details</div>
        <div class="row-fluid marTop10">
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">First Name</div>
                {!! Form::text('first-name', null, array('id' => 'first-name', 'maxlength' => 50)) !!}
                <span class="text-error text-danger">{{ $errors->first('first-name', ':message') }}</span>
            </div>
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">Last Name</div>
                {!! Form::text('last-name', null, array('id' => 'last-name', 'maxlength' => 50)) !!}
                <span class="text-error text-danger">{{ $errors->first('last-name', ':message') }}</span>
            </div>
        </div>
        
        <div id="creditcardsection">
            <div class="row-fluid marTop10">
                <div class="paypalLabLeft span6 input-wrapper">
                    <div class="paypalListName">Card Number</div>
                    {!! Form::text('cc-number', null, array('id' => 'cc-number')) !!}
                    <span class="text-error text-danger">{{ $errors->first('cc-number', ':message') }}</span>
                </div>
                <div class="paypalLabLeft span6 input-wrapper">
                    <div class="paypalListName">Security Number</div>
                    {!! Form::password('cc-cvc', array('id' => 'cc-cvc', 'maxlength' => 4)) !!}
                    <span class="text-error text-danger">{{ $errors->first('cc-cvc', ':message') }}</span>
                </div>
            </div>
            <div class="row-fluid marTop10">
                <div class="paypalLabLeft span3 input-wrapper">
                    <div class="paypalListName">Expiry Mon/Year</div>
                    {!! Form::select('cc-expmon', $exp_monthlist, null, array('class' => 'hei34 width100 marLftNon', 'id' => 'cc-expmon')) !!}
                    <span class="cc-expmon text-danger">{{ $errors->first('cc-expmon', ':message') }}</span>
                </div>
                <div class="paypalLabLeft span3 input-wrapper">
                    <div class="paypalListName">&nbsp;</div>
                    {!! Form::select('cc-expyear', $exp_yearlist, null, array('class' => 'hei34 width100 marLftNon', 'id' => 'cc-expyear')) !!}
                    <span class="text-error text-danger">{{ $errors->first('cc-expyear', ':message') }}</span>
                </div>
            </div>
        </div>
        <div class="row-fluid marTop10">
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">State</div>
                {!! Form::select('state', $states, null, array('class' => 'hei34 width100', 'id' => 'state')) !!}
                <span class="text-error state">{{ $errors->first('state', ':message') }}</span>
            </div>
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">Country</div>
                <select class="hei34 width100 marLftNon" id="country" name="country">
                    <option value="US">United States</option>
                </select>
            </div>
        </div>
        <div class="row-fluid marTop10">
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">Postal Code</div>
                {!! Form::text('postal-code', null, array('id' => 'postal-code', 'maxlength' => 10)) !!}
                <span class="text-error text-danger">{{ $errors->first('postal-code', ':message') }}</span>
            </div>
            <div class="paypalLabLeft span6 input-wrapper">
                <div class="paypalListName">Phone</div>
                {!! Form::text('phone-no', null, array('id' => 'phone-no', 'maxlength' => 14)) !!}
                <span class="text-error text-danger">{{ $errors->first('phone-no', ':message') }}</span>
            </div>
        </div>    
        
        @if(isset($action) && $action == 'purchase')
        <div class="row-fluid marTop10">
            @if($domainAvailable != 'Yes')
                {!! Form::checkbox('private_register', 1, false, array('id' => 'private_register', 'class' => 'radioright', 'data-price' => $settings['price_privacy'], 'disabled')) !!}
            @else
                {!! Form::checkbox('private_register', 1, false, array('id' => 'private_register', 'class' => 'radioright', 'data-price' => $settings['price_privacy'])) !!}
            @endif
            Enable <b>Private Registration</b> for my domain ($ {{ $settings['price_privacy'] }}/yr)
        </div>
        @endif
        
        <div class="row-fluid marTop10 blue">By submitting, you agree to our Terms of Service & Privacy Policy.</div>
        <div class="clr"></div>
        <input type="submit" class="buyCreditListButt" @if($domainAvailable != 'Yes') disabled="disabled" @endif value="Buy Now"/>
        <div class="paypalAmount"><span class="paypalAmountInn">Total Amount :</span> <span class="payAmount">$ <span id="price_auth">{{ $settings['price_privacy'] }}</span></span></div>                    
    </div>
</div>