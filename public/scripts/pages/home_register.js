//Resgister
$("form#registerform").submit(function(){
    var username    = $("#full_name").val();
    var email       = $("#email").val();
    var password    = $("#password").val();        
    if(username == ''){
        $("#errormsg").addClass('errormsg').html("Please enter Full Name");
        $("#full_name").focus();
        return false;
    }
    if(email == '')
    {
        $("#errormsg").addClass('errormsg').html("Please enter Email");
        $("#email").focus();
        return false;
    }
    if(!validEmail(email))
    {
        $("#errormsg").addClass('errormsg').html("Please enter Valid Email");
        $("#email").focus();
        return false;
    }
    if(password == '')
    {
        $("#errormsg").addClass('errormsg').html("Please enter Password");
        $("#password").focus();
        return false;
    }
    else if(password.match(/\ /))
    {
        $("#errormsg").addClass('errormsg').html("Your Password contains space.");
        $("#password").focus();
        return false;
    }
    else
    {
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxRegisterURL,
            data: {
                'email'     : email,
                'password'  : password,
                'full_name' : username
            },
            success: function(res){
                result = $.parseJSON(res);
                if(result.status == true)
                {
                    window.location.href = base_url;//registerSuccessURL;
                }
                else
                {
                    $("#errormsg").addClass('errormsg').html(result.msg);
                }
            }
        }); 
    }     
    
    return false;
});    