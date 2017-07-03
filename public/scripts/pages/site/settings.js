var SETTINGS = function() {
    //left menu switches    
    var helperLeftMenu = function() {
        $(document).on('click', '#settings-left li a', function() {
            if($(this).hasClass('active')) 
            {
                e.stopImmediatePropagation();
                
                return false;
            }   
            
            //add active class to clicked menu
            $('#settings-left li a').removeClass('active');
            $(this).addClass('active');  
            
            //hide all setting-box
            $('.setting-box').hide();
            
            //show target setting-box
            target_id = $(this).data('target');
            $(target_id).show();
        });
    };
    
    //show modal when click 'Change' button for domain
    var helperDomainModal = function() {
        $(document).on('click', '#btn-change-address', function () {
            $('#modal-domain').modal().show();    
        });
    };
    
    var helperDomainModalEvents = function() {
        //change checked status directly
        $(document).on('change', '.domainTypeRow input[name=domain]', function() {
            box = $(this).parents('.domainTypeRow');
            
            if($('input.domainnamefilter', box).length > 0)
            {
                $('input.domainnamefilter', box).focus();
            }    
            else
            {
                $('#newdomainlist').trigger('change');    
            }
        });
        
        //when focus 
        $(document).on('focus', 'input.domainnamefilter', function() {
            checkSubDomain($(this), false);    
        });
        
        //when keyup
        $(document).on('keyup', 'input.domainnamefilter', function() {
            checkSubDomain($(this), true);    
        });
        
        //when change option, reset old options
        $(document).on('change', '#newdomainlist', function() {
            checkSubDomain($(this), true);
        });
        
        //--register new domain
        $(document).on('keyup', '#new_domain_name', function() {
            checkNewDomain();
        });
        $(document).on('change', '#domain_extension', function() {
            checkNewDomain();
        });
        
        //click continue button
        $(document).on('click', '#btn-confirm-continue', function() {
            $("#loaderMask,.ui-loader,#maska").show();
            
            setTimeout("refreshPage()", 1000);
        });
        
        $(document).on('click', '#btn-domain-continue', function() {
            option = $('input[name=domain]:checked');
            errorbox = $("#err_msg");
            
            if(option.length == 0) {
                errorbox
                    .addClass('errormsg')
                    .html('Please select one of option below.')
                    .show(); 
                    
                return false;
            }
            
            if(option.val() == 'subdomain')
            {
                var tobj       = $("#subdomainurl");
                var name       = tobj.val();
                var doamin_url = tobj.val() + '.' + common.domainName; 
                var type       = 'SD';
                
                if(name == '')
                {
                    errorbox
                        .addClass('errormsg')
                        .html("Please enter domain name")
                        .show();
                    
                    tobj.focus();
                    
                    return false;
                }   
                              
                if(/^[a-zA-Z0-9- ]*$/.test(name) == false) 
                {
                    errorbox
                        .addClass('errormsg')
                        .html("Your domain names contains illegal characters.")
                        .show();
                    
                    tobj.val("").focus();
                        
                    return false;
                }
                else if(name.match(/\ /))
                {
                    errorbox
                        .addClass('errormsg')
                        .html("Your domain names contains space.")
                        .show();
                        
                    var nospaceval = name.replace(/ /g,'');
                    tobj.val(nospaceval).focus();
                        
                    return false;
                }
                
                ajaxRequest(
                    true,
                    url.updateUrl,
                    {
                        'subdomain_url' : doamin_url,
                        'type'          : type,
                        'action'        : 'subdomain',
                    },
                    function(res) {
                        $('#modal-domain').modal().hide();
                        $('.modal-backdrop').hide();
                        
                        errorbox.hide();
                        
                        result = $.parseJSON(res);
                        if(result.status)
                        {
                            alertify.success('Domain Update Sucessfully.');
                            
                            setTimeout("refreshPage()", 1000);
                        }
                        else
                        {
                            alertify.error(result.error);
                        }
                    }
                );
            }
            /*else if(obj.val() == 'newdomain')
            {
            }*/
            else if(option.val() == 'regdomain')
            {
                var newdomainid = $.trim($("#newdomainlist").val());
                if(newdomainid != '')
                {   
                    ajaxRequest(
                        false,
                        url.updateUrl,
                        {
                            'newdomainid' : newdomainid,
                            'assign_val'  : 'assign',
                            'action'      : 'regdomain',
                        },
                        function(res) {
                            $('#modal-domain').modal().hide();
                            $('.modal-backdrop').hide();
                            
                            errorbox.hide();
                            
                            result = $.parseJSON(res);
                            if(result.status)
                            {
                                alertify.success('Domain Update Sucessfully.');
                                
                                setTimeout("refreshPage()", 2000);
                            }
                            else
                            {
                                alertify.error(result.error);
                            }
                        }     
                    );    
                    
                    return false;
                }            
            }
            else if(option.val() == 'pointdomain')
            {
                //var domaint_txt = $('#point_domain_name_url').val();
                //var name        = $('#point_domain_name_url').val();
                var tobj        = $("#point_domain_name_url");
                var doamin_url  = tobj.val(); 
                var regUrl      =  new RegExp(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/);
                var type        = 'PD';
                
                if(doamin_url == '')
                {
                    errorbox
                        .addClass('errormsg')
                        .html("Please enter Point domain name")
                        .show();
                        
                    tobj.focus();
                    
                    return false;
                }                 
                if(doamin_url != '')
                {        
                    if(regUrl.test('http://' + doamin_url) == false){                              
                        errorbox
                            .addClass('errormsg')
                            .html("Please enter Valid Point domain name")
                            .show();
                            
                        tobj.focus();
                        
                        return false;                                
                    }
                }
                
                ajaxRequest(
                    false,
                    url.updateUrl,
                    {
                        'subdomain_url' : doamin_url,
                        'type'          : type,
                        'action'        : 'pointdomain',
                    },
                    function(res) {
                        //$('#modal-domain').modal().hide();
                        //$('.modal-backdrop').hide();
                        
                        errorbox.hide();
                        
                        result = $.parseJSON(res);
                        if(result.status)
                        {
                            $("#domainChangeOne").hide();
                            $("#domainChangeTwo").show();
                            $("#youdomainurl_set").html(doamin_url);
                            
                            alertify.success('Domain Update Sucessfully.');
                            
                            //setTimeout("refreshPage()", 2000);
                        }
                        else
                        {
                            $('#modal-domain').modal().hide();
                            
                            alertify.error(result.error);
                        }
                    }     
                );
            }
        });
    };
    
    //update favicon
    var helperUpdateFavicon = function() {
        $(document).on('change', '#fav_icon', function(event) {
            var input = event.target;
            var reader = new FileReader();        
            reader.onload = function(event){
                var dataURL = event.target.result;    
                var photo = document.getElementById('favicon');
                photo.src = dataURL;      
            };
            reader.readAsDataURL(input.files[0]);
            
            var fd = new FormData();            
            fd.append("Filedata", document.getElementById('fav_icon').files[0]);
            
            $.ajax({
                type: "POST",
                headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
                url: url.uploadFavicon,
                cache: false,
                data: fd,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#loaderMask,.ui-loader,#maska").show();
                }, 
                success: function(res){
                    result = $.parseJSON(res);
                    
                    $("#loaderMask,.ui-loader,#maska").hide();                    
                    if(result.status) 
                        alertify.success('Favicon is updated.');
                    else 
                        alertify.error('There is an unknown error.');
                }
            });
        });
    };
    
    //update status
    var helperUpdateStatus = function() {
        $(document).on('click', '#btn-save-status', function() {
            obj = $(this);
            status = $(this).val();
            
            ajaxRequest(
                true,
                url.updateStatus,
                {'action': status == 0 ? 'activate' : 'deactivate'},
                function(res) {
                    result = $.parseJSON(res);
                    
                    if(result.status)
                    {
                        alertify.success(result.msg);
                        
                        //change button value
                        new_status = status == 1 ? 0 : 1;
                        obj.val(new_status);
                        
                        $('#sitestatus')
                            .removeClass('text-success')
                            .removeClass('text-danger');
                        
                        if(new_status == 1)
                        {
                            $('#sitestatus')
                                .addClass('text-success')    
                                .html('Domain is Active');
                            obj.html('Deactivate');
                        }
                        else
                        {
                            $('#sitestatus')
                                .addClass('text-danger')    
                                .html('Domain is Deactive');
                            obj.html('Activate');
                        }
                    }               
                    else
                    {
                        alertify.error(result.error);
                    }
                }
            );      
        });
    };
    
    //update title
    var helperUpdateTitle = function() {
        $(document).on('click', '#btn-save-title', function () {
            title = $('#site_title');
            if(otrim(title) == '')
            {
                msgbox.addClass('errormsg').html('Please input site title.');
                return;
            }
            
            ajaxRequest(
                true,
                url.updateTitle,
                {'site_title': title.val()},
                function(res){
                    result = $.parseJSON(res);
                    
                    if(result.status)
                    {
                        alertify.success('Site title is updated.');
                    }               
                    else
                    {
                        alertify.error('There is an unknown error.');
                    }
                }
            );    
        });
    };
    
    //update meta information
    var helperUpdateMetaInfo = function() {
        $(document).on('click', '#btn-save-seo', function () {
            ajaxRequest(
                true,
                url.updateMetaInfo,
                {
                    'meta_description': $('#meta_description').val(),
                    'meta_keywords': $('#meta_keywords').val(),
                    'google_analytics': $('#google_analytics').val(),
                },
                function(res){
                    result = $.parseJSON(res);
                    
                    if(result.status)
                    {
                        alertify.success('Seo information is updated.');
                    }               
                    else
                    {
                        alertify.error('There is an unknown error.');
                    }
                }
            );  
        });    
    };
    
    return {
        init: function() {
            helperLeftMenu();  
            helperUpdateFavicon();  
            helperUpdateStatus();
            helperUpdateTitle();
            helperUpdateMetaInfo();
            helperDomainModal();
            helperDomainModalEvents();
        },
    }
}();

$(function() {
    SETTINGS.init();
});


//////////////////////////////////////////////////
