//////////////////////////////////////////////////////////////////////
//events
{
    $('#dropdown-menu').click(function() {
        $('#topoptions').toggle('show');
    });

    $('#btn-full-screen').click(function() {
        FullSreeen();
    });

    $('#publishbutton').click(function() {
        $('#modal-publish').modal('show');

        box = $('#modal-publish');
        $(".loadingurl_gif", box).show();
        $('#domainname', box).html("");                        
        $('#publish', box).show();
        
        ajaxRequest(
            false,
            url.publish,
            null,
            function(res) {
                resJSON = $.parseJSON(res);
                if(resJSON.status == true)
                {
                    //$('#domainname', box).html("<a href=" + resJSON.url + " target='_blank'>" + resJSON.url + "</a>").hide();                                   
                    $(".loadingurl_gif", box).hide();
                    $(".publishUrl", box).show(); 
                    $('#domainname', box).html("<a href=" + resJSON.url + " target='_blank'>" + resJSON.url + "</a>");
                }
                else
                {
                    $('#domainname', box).html("<a href=" + resJSON.url + " target='_blank'>" + resJSON.url + " <span class='arrowRight'>Not Ready</span></a>");   
                }
            }
        );
    });   
    
    // Start Update Left & Right Panel Height
    $(function() {
        $(window).resize(function(){
            updateLeftRightHeight();
        });

        updateLeftRightHeight();
    });
    // End Update Left & Right Panel Height
}

//functions
{
    //update margin for left and right
    function updateLeftRightHeight()
    {
        $(".mainRightPanel,.mainLeftPanel").css("margin-top",$(".headerSec").height());
        $(".mainLeftPanel").css("min-height",$(window).height()-$('.headerSec').height());     
    }
    
    function refreshPage() {
        window.location = url.current;
    }
    
    function checkSubDomain(obj, check)
    {
        box = $(obj).parents('.domainTypeRow');
                
        //hide all mark images
        $('.img-mark').hide();
        $('.continue-btn-box').hide();
        
        //set cehcked radio
        $('input[name=domain]', box).prop('checked', true);
        
        //reset other input fields
        $('.domainTypeRow').not(box).each(function() {
            $('input.domainnamefilter', $(this)).val('');   
            $("#newdomainlist option[value='']", $(this)).prop('selected', true); 
        });   
        
        if(check)
        {
            //get object id
            obj_id = obj.attr('id');
            
            //when subdomain
            if(obj_id == 'subdomainurl')
            {
                var subdomainname = $.trim(obj.val());
                //var domainid      = $.trim($("#domain_id").val());
                
                if(/^[a-zA-Z0-9]*$/.test(subdomainname) == false) 
                {
                    obj.val("").focus();
                    return false;
                }
                else if(subdomainname.match(/\ /))
                {
                    var nospaceval = subdomainname.replace(/ /g,'');
                    obj.val(nospaceval).focus();
                    return false;
                } 
                       
                if(subdomainname != '')
                {
                    var subdomain_url = subdomainname + '.' + common.domainName;
                    
                    ajaxRequest(
                        false,
                        url.checkSubdomain,
                        {'subdomain' : $.trim(subdomain_url)},
                        function(res) {
                            $('.img-mark', box).hide();
                            
                            if(res == 'invalid')
                            {
                                $('.taken-mark', box).show();  
                                $("#subdomain-btn-box").hide();
                            }
                            else
                            {
                                $('.available-mark', box).show();    
                                $("#subdomain-btn-box").show();
                            }
                        },
                        function() {
                            $('.loading-mark', box).show();
                        }
                    );
                }  
            }  
            else if(obj_id == 'point_domain_name_url')
            {
                var point_domain_name = $.trim(obj.val());
                var regUrl      =  new RegExp(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/);                        
                if(/^[a-zA-Z0-9.]*$/.test(point_domain_name) == false) 
                {
                    obj.val("").focus();
                    return false;
                }
                else if(point_domain_name.match(/\ /))
                {
                    var nospaceval = point_domain_name.replace(/ /g,'');
                    obj.val("").focus();
                    return false;
                }   
                else if(regUrl.test('http://'+point_domain_name) == false)
                {
                    $('.img-mark', box).hide();
                }     
                
                if(point_domain_name != '' && regUrl.test('http://'+point_domain_name) != false) 
                {
                    var point_domain_name_url = 'http://' + point_domain_name;
                    
                    ajaxRequest(
                        false,
                        url.checkPointDomain,
                        {'pointdomain' : point_domain_name_url},
                        function(res) {
                            $('.img-mark', box).hide();
                            
                            if(res == 'invalid')
                            {
                                $('.taken-mark', box).show();  
                                $("#subdomain-btn-box").hide();
                            }
                            else
                            {
                                $('.available-mark', box).show();    
                                $("#subdomain-btn-box").show();
                            }
                        },
                        function() {
                            $('.img-mark', box).hide();
                            $('.loading-mark', box).show();
                        }
                    );
                }
            }
            else if(obj_id == 'newdomainlist')
            {
                if(obj.val() != '')
                {
                    $("#subdomain-btn-box").show();
                }
                else
                {
                    $("#subdomain-btn-box").hide();
                }    
            }
        }      
    }

    function checkNewDomain()
    {
        box = $("#new_domain_name").parents('.domainTypeRow');
        
        var newdomainname = $.trim($("#new_domain_name").val());
        var domainext     = $.trim($("#domain_extension").val());
        var regUrl        = new RegExp(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/);            
        if(/^[a-zA-Z0-9]*$/.test(newdomainname) == false) 
        {
            $("#new_domain_name")
                .val("")
                .focus();
                
            return false;
        }
        else if(newdomainname.match(/\ /))
        {
            var nospaceval = newdomainname.replace(/ /g,'');
            $("#new_domain_name")
                .val(nospaceval)
                .focus();
                
            return false;
        } 
        
        if(newdomainname != '' && domainext != '' )
        {        
            ajaxRequest(
                false,
                url.checkDomain,
                {
                    'domain' : escape(newdomainname+domainext),
                    'action' : 'newdomain'
                },
                function(res) {
                    $('.img-mark', box).hide();
                    
                    if(res == 'taken' || res == 'try')
                    {
                        $('.taken-mark', box).show();  
                        $("#newdomain-btn-box").hide();
                    }
                    else
                    {
                        $('.available-mark', box).show();    
                        $("#newdomain-btn-box")
                            .show()
                            .html(unescape(res));
                    }
                },
                function() 
                {
                    $('.img-mark', box).hide();
                    $('.loading-mark', box).show();
                } 
            );
        }
    }
}

