var brain = {
    state : {
        dropAction : function() {},
        dropTimer : null
    },
    registerDrop  : function(cb) {
        brain.state.dropAction = cb;
        if (brain.state.dropTimer) {
            clearTimeout(brain.state.dropTimer);
        }
        brain.state.dropTimer = setTimeout(function() {
            brain.state.dropAction();
        }, 2);
    }
};

var INSIDEPAGE = function() {
    
    // start instruction popup
    var helperInstructionModal = function() {
        $(function() {
            $(document).on('click', '#btn-show-instruction', function() {
                $('#bulidinst_popup').show();
                
                $("#loaderMask").show();
            });

            $(document).on('click', '#bulidinst_popup .instpopclose', function() {
                $('#bulidinst_popup').hide();
                $('#loaderMask').hide();
            });
        }); 
    };
    
    //domain modal
    var helperDomainModal = function() {
        //when focus 
        $(document).on('focus', 'input.domainnamefilter', function() {
            checkSubDomain($(this), false);    
        });
        
        //when keyup
        $(document).on('keyup', 'input.domainnamefilter', function() {
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
            showMask();
            
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
        
        if(common.firstLoading == true) 
        {
            setTimeout(function() {
                $('#modal-domain').modal('show');
            }, 500);
        }
    };
    
    //left menu
    var helperToolBox = function() {
        $(document).on('click', '#toolssection li a', function(e) {
            if($(this).hasClass('active'))
            {
                e.stopImmediatePropagation();
                return;
            }
            
            $('#toolssection li a').removeClass('active');
            $(this).addClass('active');
            
            targetId = '#' + $(this).attr('id') + '_content';
            $('#draggable_content .toolsectioncontent').hide();
            $(targetId).show();
        });
    };
    
    //show/hide theme list box
    var helperThemeListBox = function() {
        $('#change-theme-button').click(function() {
            themeListBox = $('#theme-list-box');
            iframeBox = $('#iframe-box');
            
            if(themeListBox.is(":visible")) {
                themeListBox.hide();
                iframeBox.show();
            } else {
                iframeBox.hide();
                themeListBox.show();                
            }
        });
    };
    
    var helperThemeListBoxEvent = function() {
        //color picker for each theme
        $('#theme-list-box li.themeclrselected').click(function() {
            box = $(this).parents('.themeMenuWidt');
            
            img = $(this).data('image');
            old_img = $('img.preview-image', box).attr('src');
            if(img != old_img)
            {
                $('img.preview-image', box).attr('src', $(this).data('image'));
            }
            
            $('li.themeclrselected', box).removeClass('active');
            $(this).addClass('active');
        });

        //choose theme finally
        $('.btn-select-theme').click(function() {
            box = $(this).parents('.themeMenuWidt');
            
            theme_id = $(this).val();
            color_id = $('ul.theme-colors li.themeclrselected.active', box).data('index');
            
            if(theme_id && color_id)
            {
                modal = swal({
                    title: "Are you sure?",
                    text: "You will lost current customization contents.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#46be8a',
                    confirmButtonText: 'Yes',
                    cancelButtonText: "No!",
                    /*closeOnConfirm: false,*/
                    //closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        ajaxRequest(
                            true,
                            url.updateTheme,
                            {'theme_id': theme_id, 'color_id': color_id},
                            function(res){
                                result = $.parseJSON(res);
                                if(result.status)
                                {
                                    document.location.href = url.toPageBuilder;
                                }
                                else                                                                  
                                {
                                    alertify.error(result.msg);
                                }
                            }
                        );
                    }
                });    
            }
            else
            {
                alertify.error('Please select theme and color option.');
            }
        });
    };
    
    //update theme color when click colors on the left
    var helperUpdateThemeColor = function() {
        $('#theme-color-picker li').click(function() {
            $('li.themeclrselected', $(this).parent()).removeClass('active');
            $(this).addClass('active');
    
            color_id = $(this).data('index');
            ajaxRequest(
                true,
                url.updateThemeColor,
                {'color_id':color_id},
                function(res) {
                    resJSON = $.parseJSON(res);
                    if(resJSON.status == true)
                    {
                        alertify.success('Color is updated');
                    }
                    else
                    {
                        alertify.error(resJSON.msg);
                    }
                }
            );
        });
    };
    
    //font option events
    var helperFontOptions = function() {
        $('#change-fonts-button').click(function() {
            $("#fontlist").slideDown();
        });
    };
    
    //change page :index, checkout, thanks
    var helperChangePage = function() {
        $(document).on('click', '#theme-pages li a', function() {
            if($(this).hasClass('active')) return;
            
            console.log('page changed');
            
            pageId = $(this).data('index');
            dataArray = {'page_id': pageId};
            ajaxRequest(true, url.changePage, dataArray, function(res) {
                json = $.parseJSON(res);
                if(json.status == true)
                {
                    refreshPage();
                }
            });    
        });
    };
    
    return {
        init: function() {
            helperInstructionModal();  
            helperDomainModal();   
            helperToolBox();   
            
            helperUpdateThemeColor();
            helperThemeListBox();
            helperThemeListBoxEvent();
            helperFontOptions();  
            
            helperChangePage();  
        },
    }
    
}();
   
var IFRAMEPAGE = function() {
    var hideToolbarAndDeletePopup = function(event) {
        if($(event.target).closest("#deletePopup, .deleteOuter").length == 0) { 
            $("#deletePopup").hide().removeAttr("style");
        } 
        if($(event.target).closest("[data-rapidcms-engine], #toolbar, #buttomtoolbar").length == 0) {
            $("#toolbar").hide().removeAttr("style");
            $("#buttomtoolbar").hide();
        } 
    };
    
    //link mdal
    var toolbarLinkModal = function() {
        //swtich mail & link
        $('#modal-link .link-radio').click(function() {
            type = $(this).val();
            if(type=='url')
            {
                $('#link-mail').hide();
                $('#link-url-label, #link-url').show();
            }      
            else
            {
                $('#link-url-label, #link-url').hide();
                $('#link-mail').show();
            } 
        });
        
        //submit link form
        $('#btn-submit-urlform').click(function() {
            var url;
            var target="";
            var type=$(".link-radio:checked").val();
            
            if(type=='url') {
                url = $('#link-url').val();
                if(url.match('http://') != 'http://' && url.match('https://')!='https://') {
                    url = 'http://' + url;
                }
                
                if($("#open-target:checked").length>0) {
                    target = '_blank';
                }
            } else {
                url = 'mailto:' + $('#link-mail').val();
                target = "";
            } 
            
            if(target != '') {
                target=" target='"+target+"' ";
            }  
            
            var selectedText = document.getSelection().toString(); 
            if(selectedText=='') {
                selectedText = url;
            }
            
            document.execCommand('insertHTML', false, "<a href='" + url + "'" + target + ">" + selectedText + "</a>");
            
            $('#modal-link').modal('hide');
        });
    };
    
    var initEditableItems = function() {
        $(elements).each(function() {
            item = this;
            
            if(common.hasIFRAME())
            {   
                itemObject = $('#'+item.tid)
                    .attr('data-type', item.type);
                
                if(item.type == 'html')
                {                    
                    //skip if original
                    itemObject.attr("contentEditable",true)
                    .attr("data-rapidcms-engine", true)
                    .mouseover(function(event) {
                        $(this).addClass('outline-element');
                    })
                    .mouseout(function(event) {
                        $(this).removeClass('outline-element');
                    })
                    .addClass('data-rapidcms-engine')
                    .keypress(function(e) {
                        if (e.keyCode == 9) {
                            e.preventDefault();
                        }
                    })
                    .keyup("v",function(e) {
                        if(e.ctrlKey && e.keyCode == 86)
                        {            
                            $(this).find("*").removeAttr("style");
                            $(this).find("img").remove();            
                        }
                        
                    })
                    .mousedown(function(event){
                        $(".btn-toolbar").remove();
                        $("#toolbar").show().css({"top":event.pageY - 90});
                        $("#toolbar").css("left", "28%");
                        $(this).freshereditor({toolbar_selector: "#toolbar"});                                 
                    })
                    .bind({
                        paste:function(e) {
                            e.preventDefault();
                            var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
                            document.execCommand('insertText', false, text);
                        }
                    })
                    .blur(function() {
                        //save text to db
                        common.selectedObject = $(this);
                        
                        $(document).one('click', function(event) {
                            hideToolbarAndDeletePopup(event);    
                            
                            if(common.updatingTimer) clearTimeout(common.updatingTimer);
                            common.updatingTimer = setTimeout(updateEditableItemContent(), 100);
                        });
                    }); 
                    
                    $(document).click(function(event){
                        hideToolbarAndDeletePopup(event);    
                    });
                }
                else if(item.type == 'image')
                {    
                    itemObject
                    .attr("data-rapidcms-engine", true)
                    .mouseover(function(event) {
                        $(this).addClass('outline-element');
                    })
                    .mouseout(function(event) {
                        $(this).removeClass('outline-element');
                    });  
                           
                    fileInputObject = '<input type="file" id="' + item.tid + '_image" name="' + item.tid + '_image" data-target="' + item.tid + '" style="display:none;">';
                    fileObj = $(fileInputObject)
                        .insertAfter(itemObject)
                        .change(function(){
                            currentElement = this;
                            targetElement = $(this).prev(); 
                            
                            ajaxUpload(
                                currentElement, 
                                url.uploadImage,
                                function(res) {
                                    resJSON = $.parseJSON(res);
                                    if(resJSON.status == true)
                                    {
                                        targetElement.attr('src', resJSON.path);
                                    } 
                                },   
                                function() {
                                    targetElement.attr('src', base_url + '/images/ajax-loader.gif');    
                                }
                            );       
                        });                    
                    
                    itemObject.css('cursor', 'pointer')
                        .click(function() {
                            fileObj.trigger('click');    
                        });
                }
                else if(item.type == 'picture')
                {    
                    itemObject
                    .attr("data-rapidcms-engine", true)
                    .mouseover(function(event) {
                        $(this).addClass('outline-element');
                    })
                    .mouseout(function(event) {
                        $(this).removeClass('outline-element');
                    });    
                      
                    fileInputObject = '<input type="file" id="' + item.tid + '_image" name="' + item.tid + '_image" data-target="' + item.tid + '" style="display:none;">';
                    fileObj = $(fileInputObject)
                        .insertAfter(itemObject)
                        .change(function(){
                            currentElement = this;
                            targetElement = $(this).prev(); 
                            
                            ajaxUpload(
                                currentElement, 
                                url.uploadImage,
                                function(res) {
                                    resJSON = $.parseJSON(res);
                                    
                                    hideMask();
                                    if(resJSON.status == true)
                                    {
                                        targetElement.html('<img src="' + resJSON.path + '" width="100%" height="100%"/>');
                                    }
                                    else
                                    {
                                        alertify.error(resJSON.msg);
                                    } 
                                },   
                                function() {
                                    showMask();
                                }
                            );       
                        });                    
                    
                    itemObject.css('cursor', 'pointer')
                        .click(function() {
                            fileObj.trigger('click');    
                        });
                }
                else if(item.type == 'blankbox')
                {
                    itemObject.addClass('blankbox');   
                }
            }  
        });
    }; 
    
    var initDragDropPlace = function() {
        //Sortable
        $( "#droppable_content" ).sortable({
            update: function(event, ui) {
                $.post(url.sortElements, { pages: $('#droppable_content').sortable('serialize') } );
            },
            cancel: '.contentedit,.contactlabelsPopup,.youtubelabelsPopup,.widtTenPer,.authorBlogReplaceTextarea,.formEntryPopForm,.control-group,.text,.socialPop,.contactform,.mainPageThmeTitle,.galleryimagepop,.galleryimage,.mapinputTxt,.google_ansen,.google_url_text,.addGooginputTxt,.columncount',
            handle: ".controlMidBg"
        });         
        
        //Drag && Drop
        //Drag
        var origin = 'selected';
        $("#draggable_content li").draggable({ 
            iframeFix: true,
            //containment: "window",
            scroll: true,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            revert: 'invalid',
            connectToSortable: '#droppable_content',
            helper: 'clone',
            start: function(event,ui) {
                origin = 'draggable_content';
                //$("#loaderMask, #maska").show();
                //console.log('start drag');
                
                $("#dropBox").css({"border":"1px dashed #3399FF"});
                $("#dropBox").addClass("highlight_bg");
            },
            stop: function(event,ui) {
                //$("#loaderMask, #maska").hide();
                
                $("#dropBox").css({"border":"1px dashed transparent"});
                $("#dropBox").removeClass("highlight_bg");
            },
            drag: function(event,ui) {
                //console.log('dragging');
                
                var leftvalue = $(this).attr("id").split('box')[1];
                var dragleft = $(this).width()/2;
                var dragtop = $(this).height()+($(this).height()/2);
                
                $(ui.helper).css("left", event.clientX - dragleft);
                $(ui.helper).css("top", event.clientY - dragtop);
            }
        });
        
        //Drop
        $("#droppable_content").droppable({
            drop: function(event, ui) {
                var dropvalid = $(this).attr('id');
                
                brain.registerDrop(function() {
                    if (dropvalid == 'droppable_content' && origin === 'draggable_content') {
                        var tdid = $(this).attr('id');
                        var drop_content_id = ui.draggable.attr("data-title"); 
                        ui.draggable.css('opacity', 0);
                        updateDragDropElementList(drop_content_id);

                        $('#droppable_content li').click(function(e) {
                            $(this).closest('li').droppable("destroy");
                            this.focus();
                        }).blur(function() {
                            $(this).closest('li').droppable();
                        });
                        origin = 'droppable_content';
                    }
                });
            }
        }).sortable({
            revert: true,
            handle: '.controlMidOuter',
            placeholder: 'highlightLine'
        }); 
        
        //Show Delete Popup when click DeleteIcon
        {
            $(document).on('click', '#droppable_content .deleteBg', function(event){
                box = $(this).parents('li');
                elementId = box.data('index');
                elementType = box.data('type');
                
                $('#element-id').val(elementId);
                $('#element-type').val(elementType);
                
                var x = $(window).width() - box.width() - box.offset().left;
                var y = event.pageY - this.offsetTop - 140;
                $("#deletePopup").fadeIn().css({"right":x-30, "top":y});
            });
            
            $(document).on('click', '#btnDeletePopupButton', function() {
                $("#deletePopup").hide();
                
                elementId = $('#element-id').val();
                elementType = $('#element-type').val();
                targetId = '#page_' + elementId;
                if($(targetId).length > 0)
                {
                    $(targetId).remove();
                    ajaxRequest(
                        true,
                        url.deleteElement,
                        {'element-id':elementId, 'element-type':elementType}
                    );
                }
            });
        } 
        
        //map popup
        {
            $(document).on('click', '.btn-map-setting-popup', function() {
                box = $(this).parents('li');
                
                $('#map_element_id').val(box.data('index'));
                $('#map_address').val(box.data('address'));
                $('#map_latitude').val(box.data('latitude'));
                $('#map_longitude').val(box.data('longitude'));
                $('#map_zoom').val(box.data('zoom'));
                
                
                $('#modal-map-setting').modal('show');
            });
            
            $(document).on('click', '#btn-save-map-setting', function() {
                ajaxRequest(
                    true,
                    url.updateMapElement,
                    $('#map-setting-form').serialize(),
                    function(res) {
                        json = $.parseJSON(res);
                        
                        if(json.status) {
                            refreshElementSection();
                        }
                    }
                )
            });
        }
        
        //file 
        {
            $(document).on('click', '.btn-download-file', function() {
                box = $(this).parents('li');
                $('#file-element').data('target', box.data('index'));
                
                $('#modal-download-file').modal('show');
            });
            
            $('#modal-download-file #file-element').change(function() {
                ajaxUpload(
                    this,
                    url.updateFileElement,
                    function() {
                        $('#imageloadstatuslogo').hide();
                        $('#imageloadbutton').show();
                        
                        $('#modal-download-file').modal('hide');
                        
                        refreshElementSection();
                    },
                    function() {
                        $('#imageloadbutton').hide();
                        $('#imageloadstatuslogo').show();
                    }
                );
            });
        }
        
        //youtube
        {
            $(document).on('click', '.btn-youtube-setting-popup', function() {
                box = $(this).parents('li');
                $('#youtube_element_id').val(box.data('index'));
                $('#youtube_url').val(box.data('url'));
                $('#youtube_space').val(box.data('space'));
                $('#youtube_width').val(box.data('width'));
                
                //$('#btn-save-youtube-setting').attr('disabled', 'disabled');
                $('#modal-youtube-setting').modal('show');   
            });
            
            $(document).on('click', '#btn-save-youtube-setting', function() {
                yurl = $('#youtube_url').val();
                var c = yurl.replace(/(?:http:\/\/)?(?:https:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/)?(.+)/g,'$1'); 
                var d = "http://www.youtube.com/embed/"+c;       
                $('#youtube_url').val(d);                
                
                $('#modal-youtube-setting').modal('hide');   
                
                ajaxRequest(
                    true,
                    url.updateYoutubeElement,
                    $('#youtube-setting-form').serialize(),
                    function(res) {
                        json = $.parseJSON(res);
                        
                        if(json.status) {
                            refreshElementSection();
                        }
                    }
                );
            });
        }
        
        //social
        {
            $(document).on('click', '.buildSocialIcon', function() {
                $('#modal-social-setting').modal('show');       
            });
        }
    }; 
    
    var initDragDropElements = function() {
        $('#droppable_content .element').each(function() {
            itemObject = $(this);
            dataIndex = itemObject.data('index');
            dataType = itemObject.data('type');
            if(dataType == itemTypes.title || dataType == itemTypes.paragraph) //title
            {
                //skip if original
                itemObject
                .keypress(function(e) {
                    if (e.keyCode == 9) {
                        e.preventDefault();
                    }
                })
                .keyup("v",function(e) {
                    if(e.ctrlKey && e.keyCode == 86)
                    {            
                        $(this).find("*").removeAttr("style");
                        $(this).find("img").remove();            
                    }
                    
                })
                .mousedown(function(event){
                    $(".btn-toolbar").remove();
                    $("#toolbar").show().css({"top":event.pageY - 90});
                    $("#toolbar").css("left", "28%");
                    $(this).freshereditor({toolbar_selector: "#toolbar"});                                 
                })
                .bind({
                    paste:function(e) {
                        e.preventDefault();
                        var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
                        document.execCommand('insertText', false, text);
                    }
                })
                .blur(function() {
                    //save text to db
                    common.selectedObject = $(this);
                    
                    $(document).one('click', function(event) {
                        hideToolbarAndDeletePopup(event);    
                        
                        if(common.updatingTimer) clearTimeout(common.updatingTimer);
                        common.updatingTimer = setTimeout(updateDragDropElement(), 100);
                    });
                }); 
            }
            else if(dataType == itemTypes.image)
            {
                itemObject.click(function() {
                    
                });    
            }
        });
    }
    
    var updateDragDropElementList = function(itemType)
    {
        $(document).trigger("click");
        
        var inc=0;
        var array_elementids=new Array();
        var tmp;
        
        setTimeout(function() {
            $("#droppable_content li").each(function(){
               if($(this).html()!="")
               {
                    if($(this).attr("id") != undefined)
                    {
                        tmp=$(this).attr("id");
                    }
                    else
                    {
                        tmp='000';
                    }    
                    array_elementids[inc]=tmp;
                    inc++;
               }
            });
            
            ajaxRequest(
                true,
                url.updateElementSection,
                {
                    'item_type':itemType,
                    'element_ids': array_elementids
                },
                function(res) {
                    $('#droppable_content').html(res);
                    
                    initDragDropElements();
                }
            );
        }, 700);
    }
    
    return {
        init: function() {            
            toolbarLinkModal();            
            initEditableItems(); 
            initDragDropPlace();
            initDragDropElements();
                
            hideLoading();
        },
    }
}();

$(document).ready(function() {
    INSIDEPAGE.init();
    IFRAMEPAGE.init();
});


//////////////////////////////////////////////////
{
    function refreshPage() {
        document.location.href = url.current;   
    };   
    
    function closesizefont(){
        $("#jquery-colour-picker").hide();
    }

    function showLinkModal() {
        $('#modal-link').modal('show');
    }

    function ajaxUpload(object, url, successFunc, beforeFunc) {
        var fd = new FormData();            
        fd.append("Filedata", object.files[0]);
        fd.append('ObjectID', $(object).data('target'));
        
        $.ajax({
            type: "POST",
            headers: { 'X-XSRF-TOKEN' : $('#csrf-token').attr('content') }, 
            url: url,
            cache: false,
            data: fd,
            processData: false,
            contentType: false,
            beforeSend: beforeFunc, 
            success: function(res){
                if(successFunc)
                {
                    successFunc(res);
                }
            }
        });
    }

    //update editable items
    function updateEditableItemContent() {
        if(common.selectedObject != null && common.isUpdating == false)
        {      
            objectId = common.selectedObject.attr('id');
            content = $.trim(common.selectedObject.html().nl2br());
                  
            ajaxRequest(
                false,
                url.updateItemContent,
                {'objectId': objectId, 'content': content} ,
                function() {
                    common.isUpdating = false;
                },
                function() {
                    common.isUpdating = true;    
                }
            );
        }   
    };

    function updateDragDropElement() {
        if(common.selectedObject != null && common.isUpdating == false)
        {      
            objectId = common.selectedObject.data('index');
            objectType = common.selectedObject.data('type');
            content = $.trim(common.selectedObject.html().nl2br());
                  
            ajaxRequest(
                false,
                url.updateElement,
                {'objectId': objectId, 'objectType': objectType, 'content': content} ,
                function() {
                    common.isUpdating = false;
                },
                function() {
                    common.isUpdating = true;    
                }
            );
        }       
    }
    
    function refreshElementSection() {
        ajaxRequest(
            false,
            url.refreshElementSection,
            null,
            function(res) {
                $('#droppable_content').html(res);   
            }
        )
    }
}