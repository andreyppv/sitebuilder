//domain purchase payamount calculation
$('input.subcriptionPlanInput, #private_register').click(function() {
    obj = $("input.subcriptionPlanInput:checked");
    if(obj.length == 0) return;
    
    var plan_year = obj.val();
    var plan_price= obj.data('price');
    var privacy_price = 0.00;
    
    if($('#private_register:checked').length == 1)
    {
        privacy_price = $('#private_register').data('price');
    }

    var total = ( parseFloat(plan_year) * parseFloat(plan_price) ) + ( parseFloat(plan_year) * parseFloat(privacy_price) );
    
    $("#price_auth").html(total.toFixed(2)); 
});

//call calculation at first
$("input.subcriptionPlanInput:checked").trigger('click');

//change payment type
$('input[name="payment-type"]').click(function() {
    type = $(this).val();
    if(type == 'CreditCard')
    {
        $('#creditcardsection').show('fast');
    }
    else
    {
        $('#creditcardsection').hide('fast');
    }
});

//call payment at first
$('input[name="payment-type"]:checked').trigger('click');

//domain purchase payment validation
$("form#paymentform").submit(function() {
    
    var firstname   = $("#first-name");
    var lastname    = $("#last-name");
    var cc_number   = $("#cc-number"); 
    var cc_cvc      = $("#cc-cvc");  
    var postal      = $("#postal-code"); 
    var phone       = $("#phone-no");
    
    if($('input[name="priceplan"]').length > 0)
    {
        if($('input[name="priceplan"]:checked').length == 0) {
            $("#priceplanerror").addClass('errormsg').html("Please select Any Price per Plan");
            return false;
        } else {
             $("#priceplanerror").html('');
        }
    }
    
    //payment type   
    if($('input[name="payment-type"]:checked').length == 0) {
        $("#paymentdetails-msg").html("Please Select Any Payment Method"); 
        return false;
    } else {
         $("#paymentdetails-msg").html('');
    }        
    
    //first name
    if(otrim(firstname) == '') {
        show_error(firstname, "Please Enter the First Name");
        firstname.focus();
        return false;
    } else {
        show_error(firstname, "");
    }
    
    //last name
    if(otrim(lastname) == '') {
        show_error(lastname, "Please Enter the Last Name");
        lastname.focus();
        return false;
    } else {
        show_error(lastname, "");
    }
    
    //creditcard
    if($('input[name="payment-type"]:checked').val() == 'CreditCard') {
        //cc_number
        if(otrim(cc_number) == '') {
            show_error(cc_number, "Please Enter the Credit card Number");
            cc_number.focus();
            return false;
        } else if(!validCardNumber(otrim(cc_number))) {
            show_error(cc_number, "Please Enter the Valid Credit card Number");
            cc_number.focus();
            return false;
        } else {
            show_error(cc_number, "");
        }
        
        //cvc
        if(otrim(cc_cvc) == '') {
            show_error(cc_cvc, "Please Enter the CVC");
            cc_cvc.focus();
            return false;
        } else if(isNaN(otrim(cc_cvc))) {
            show_error(cc_cvc, "Please Enter the Valid CVC");
            cc_cvc.focus();
            return false;
        } else {
            show_error(cc_cvc, "");
        }
    }   
    
    if(otrim(postal) == '') {
        show_error(postal, "Please Enter Postal Code");
        postal.focus();
        return false;
    } else {
        show_error(postal, "");
    }
    
    if(otrim(phone) == '') {
        show_error(phone, "Please Enter Phone number");
        phone.focus();
        return false;
    } else {
        show_error(phone, "");
    }
});

function show_error(obj, msg) {
    $('.text-error', obj.parents('.input-wrapper')).html(msg);    
}