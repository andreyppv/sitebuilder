//Login
$("form#loginform").submit(function()
{
    var user_email          = $("#user_email").val();
    var user_password       = $("#user_password").val();
    var remember_checked_val= $("#remember_me").attr("checked");

    if(remember_checked_val)
    {
         var remember_me = 1;
    }
    else
    {
         var remember_me = 0;
    }

    if(user_email == '')
    {
        $("#error_msglogin").addClass('errormsg').html("Please enter Email Or Username");
        $("#user_email").focus();
        return false;
    }
    if(!validEmail(user_email))
    {
        $("#error_msglogin").addClass('errormsg').html("Please enter Valid Email");
        $("#user_email").focus();
        return false;
    }
    if(user_password == '')
    {
        $("#error_msglogin").addClass('errormsg').html("Please Enter Your Password");
        $("#user_password").focus();
        return false;
    }
    else
    {
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxLoginURL,
            data: {
                'email'     : user_email,
                'password'  : user_password,
                'remember'  : remember_me
            },
            success: function(res){
                result = $.parseJSON(res);
                if(result.status == true)
                {
                    window.location.href = base_url;
                }
                else
                {
                    $("#error_msglogin").addClass('errormsg').html(result.msg);
                }
            }
        });              
    }
    return false;
});