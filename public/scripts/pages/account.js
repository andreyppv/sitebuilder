//this is for chang e password page in user side
$("form#changePassForm").submit(function(){
 
    var oldpass         = $("#current_pass");
    var newpass         = $("#new_pass");
    var confirm_newpass = $("#confirm_newpass");
    var msg_box         = $('.cst_errormsg', $(this));
 
    if(oldpass.val() == ''){
        msg_box.addClass('errormsg').html("Please enter Current Password");
        oldpass.focus();
        return false;
    }
    else if(newpass.val() == ''){
        msg_box.addClass('errormsg').html("Please enter New Password");
        newpass.focus();
        return false;
    }
    else if(newpass.val().match(/\ /)){
        msg_box.addClass('errormsg').html("Your New Password Contains Space");
        newpass.focus();
        return false;
    }
    else if(confirm_newpass.val() == ''){
        msg_box.addClass('errormsg').html("Please enter Confirm New Password");
        confirm_newpass.focus();
        return false;
    }
    else if(confirm_newpass.val() != newpass.val()){
        msg_box.addClass('errormsg').html("New Password  and Confirm Password should be the same");
        confirm_newpass.focus();
        return false;
    }
    else{
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxUpdatePasswordURL,
            data: {
                'current_password' : oldpass.val(),
                'password' : newpass.val(),
                'password_confirm' : confirm_newpass.val(),
            },
            success: function(res){
                result = $.parseJSON(res);
                if(result.status == true)
                {
                    msg_box.addClass('successmsg').html(result.msg);
                    
                    setTimeout(function() {
                        window.location.href = base_url; //require to relogin
                    }, 2000);
                }
                else
                {
                    msg_box.addClass('errormsg').html(result.msg);
                    
                    oldpass.val('');
                    newpass.val('');
                    confirm_newpass.val('');
                }
            }
        }); 
        
        return false;
    }
    return false;            
});

//this is for change email page in user side
$("form#emailcolumn").submit(function(){
 
    var email   = $("#email");
    var msg_box = $('.cst_errormsg', $(this));
    
    if(email.val() == '')
    {
        msg_box.addClass('errormsg').html("Please enter Email Address");
        email.focus();
        return false;
    }
    if(!validEmail(email.val())){
        msg_box.addClass('errormsg').html("Please enter Valid Email");
        email.focus();
        return false;
    }
    else{
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxUpdateEmailURL,
            data: {
                'email' : email.val(),
            },
            success: function(res){
                result = $.parseJSON(res);
                if(result.status == true)
                {
                    msg_box.addClass('successmsg').html(result.msg);
                    
                    setTimeout(function() {
                        window.location.href = accountURL; //require to relogin
                    }, 2000);
                }
                else
                {
                    msg_box.addClass('errormsg').html(result.msg);
                }
            }
        }); 
        
        return false;
    }
    return false;            
});    

//this is for change fullname page in user side
$("form#fullnameForm").submit(function(){

    var fullname = $("#fullname");
    var msg_box = $('.cst_errormsg', $(this));
    
    if(fullname.val() == ''){
        msg_box.addClass('errormsg').html("Please enter Full name");
        fullname.focus();
        return false;
    }
    else{
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxUpdateNameURL,
            data: {
                'full_name' : fullname.val(),
            },
            success: function(res){
                result = $.parseJSON(res);
                if(result.status == true)
                {
                    msg_box.addClass('successmsg').html(result.msg);
                    
                    setTimeout(function() {
                        window.location.href = accountURL; //require to relogin
                    }, 2000);
                }
                else
                {
                    msg_box.addClass('errormsg').html(result.msg);
                }
            }
        }); 
        
        return false;
    }
    return false;            
});