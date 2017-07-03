/////////////////////////////////////////////////
//Events
/////////////////////////////////////////////////
$('th.sort_th').click(function() {
    if(link = $(this).data('href'))
    {
        document.location.href = link;
    }
});

/////////////////////////////////////////////////
//Functions
/////////////////////////////////////////////////
function hideLoading()
{
    $("#loading").fadeOut("slow");
}

function showLoading()
{
    $("#loading").fadeOut("slow");
}

function siteUrl(uri)
{
    return base_url + uri;
}

function validEmail(str)
{
    var email_check = /^([a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;        
    if(!str.match(email_check))
    {
        return false;
    }
    
    return true;
}

function validEmails(str)
{
    emails = str.split(',');
    for(i=0; i<emails.length; i++)
    {
        email = emails[i];
        
        if(email == '') continue;
        
        if(!validEmail(email)) return false;
    }
    
    return true;
}

function validCardNumber(value)
{
    if (/[^0-9-\s]+/.test(value)) return false;

    // The Luhn Algorithm. It's so pretty.
    var nCheck = 0, nDigit = 0, bEven = false;
    value = value.replace(/\D/g, "");

    for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n),
              nDigit = parseInt(cDigit, 10);

        if (bEven) {
            if ((nDigit *= 2) > 9) nDigit -= 9;
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) == 0;
}

function ctrim(str)
{
    return $.trim(str);
}

function otrim(obj)
{
    return $.trim(obj.val());
}

function FullSreeen()
{
    var docElement, request;
    docElement = document.documentElement;
    request = docElement.requestFullScreen || docElement.webkitRequestFullScreen || docElement.mozRequestFullScreen || docElement.msRequestFullScreen;            
    if(typeof request!="undefined" && request){
        request.call(docElement);
    }
}

function showMask() {
    $("#loaderMask,.ui-loader,#maska").show();
}
function hideMask() {
    $("#loaderMask,.ui-loader,#maska").hide();
}

function ajaxRequest(loading, url, params, backFunc, beforeFunc)
{
    $.ajax({
        type: "POST",
        headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
        url: url,
        cache: false,
        data: params,
        beforeSend: function() 
        {
            if(loading == true)
            {
                $("#loaderMask,.ui-loader,#maska").show();
            }
            
            if(beforeFunc)
            {
                beforeFunc();
            }
        }, 
        success: function(res){
            if(loading == true)
            {
                $("#loaderMask,.ui-loader,#maska").hide();
            }   
            
            if(backFunc)
            {
                backFunc(res);
            }
        }
    });
}

/////////////////////////////////////////////////
//Extend Plugins
/////////////////////////////////////////////////
String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
}