$('#btn-check-domain').click(function() {
    /*var name       = $.trim($('#suggested_names').val());
    var domain_ext = $.trim($('#doamin_ext').val());*/
    var name_obj        = $('#suggested_names');
    var domain_ext_obj  = $('#doamin_ext');
    var search_status_obj = $("#vpb_search_status");
    
    var name = $.trim(name_obj.val());
    var domain_ext = $.trim(domain_ext_obj.val());
    if (name == "" || name == "Enter a desired domain name here")
    {
        search_status_obj.html('<div class="info marTop10">Please enter a domain name of your choice to search.</div>');
        name_obj.focus();
        return false;
    }
    
    if(name.match(/\ /))
    {
        search_status_obj.html('<div class="info marTop10">Your domain names contains space.</div>');
        var nospaceval = name.replace(/ /g,'');
        name_obj.val(nospaceval);
        name_obj.focus();
        return false;
    }
    else
    {
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: ajaxCheckURL,
            data: {
                'domain' : escape(name + '.' + domain_ext),
            },
            cache: false,
            beforeSend: function() 
            {
                html = '<font style="font-family:opensansbold; padding-right:10px; font-size:12px;">Please wait</font>'
                    + '<img src="' + base_url + '/images/loadings.gif" align="absmiddle" alt="Loading...">'
                    + '<br clear="all"><br clear="all"><br clear="all">';
                search_status_obj.html(html);
            }, 
            success: function(res){
                search_status_obj.html(unescape(res));    
            }
        }); 
    }    
});

//domain name based add the mailadd functions
$('.btn-email-setup').click(function () {
    domain_id = $(this).data('index');
    $.ajax({
        type: "POST",
        headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
        url: ajaxEmailSetupURL,
        data: {
            'domain_id' : domain_id,
        },
        cache: false,
        beforeSend: function() 
        {
            $("#loaderMask,.ui-loader,#maska").show();
        }, 
        success: function(res){
            $("#loaderMask,.ui-loader,#maska").hide();
            
            result = $.parseJSON(res);            
            if(result.status == true)
            {
                window.location.reload(); 
            }
            else
            {
                alertify.error(result.msg);
            }
        }
    });  
});

//domain name based add the mailadd functions
/*$('.btn-email-login').click(function () {
    domain_id = $(this).data('index');
    $.ajax({
        type: "POST",
        headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
        url: ajaxEmailLoginURL,
        data: {
            'domain_id' : domain_id,
        },
        cache: false,
        beforeSend: function() 
        {
            $("#loaderMask,.ui-loader,#maska").show();
        }, 
        success: function(res){
            $("#loaderMask,.ui-loader,#maska").hide();
            
            $("#emailloginurl .modal-body").html(res); 
        }
    });  
});*/

//show the domain details div for domain assign 
var scrollPosition = 0;
$('.btn-domain-manage').click(function() {
    scrollPosition = $(window).scrollTop();
    
    domain_id = $(this).data('index');
    
    $("#domainprocessdiv").hide();
    $("#domaininfodiv").show();
    $("#domaininfodiv_" + domain_id).show();
});

$('#btn-show-domains').click(function() {
    $("#domainprocessdiv").show();
    $('.indivdomaindetails').hide();
    $("#domaininfodiv").hide();
    
    $(window).scrollTop(scrollPosition);
});

//empty content when close
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal').find(".modal-content").empty();
});

//lock status popup
//change register status
$(document).on('click', '#btn-save-register-status', function () {
    product_id = $(this).data('product-id');
    $.ajax({
        type: "POST",
        headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
        url: ajaxChangeRegisterStatusURL,
        data: {
            'product_id' : product_id,
            'register_status' : $("input[name='reg_lock']:checked").val(),
        },
        cache: false,
        beforeSend: function() 
        {
            $("#loaderMask,.ui-loader,#maska").show();
        }, 
        success: function(res){
            $("#loaderMask,.ui-loader,#maska").hide();
            $("#commonpopupdiv").modal('hide');
            
            result = $.parseJSON(res);
            if(result.status == true)
            {
                $('#registlock_' + product_id).html(result.value);
            }
            else
            {
                alertify.error(result.msg);    
            }
        }
    });  
});

//domain assign popup
//when click 'assign' option
$(document).on('click', "[name='assign_domain']", function(){                    
    if( $(this).val() == 'assign' )
    {
        $("#unassigndomains").show();                        
    }
    else
    {
       $("#unassigndomains").hide();  
    }
});

//change assign status
$(document).on('click', '#btn-save-assign', function () {
    domain_id = $(this).data('index');
    
    $.ajax({
        type: "POST",
        headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
        url: ajaxSiteAssignURL,
        data: {
            'domain_id' : domain_id,
            'assign_id' : $("input[name='assign_domain']:checked").val(),
        },
        cache: false,
        beforeSend: function() 
        {
            $("#loaderMask,.ui-loader,#maska").show();
        }, 
        success: function(res){
            $("#loaderMask,.ui-loader,#maska").hide();
            $("#commonpopupdiv").modal('hide');
            
            result = $.parseJSON(res);
            if(result.status == true)
            {
                //ToDo here    
            }
            else
            {
                alertify.error(result.msg);    
            }
        }
    });  
});