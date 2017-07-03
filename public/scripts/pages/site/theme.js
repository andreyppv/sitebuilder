$('ul li.themeclrselected').click(function() {
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

$('.btn-select-theme').click(function() {
    box = $(this).parents('.themeMenuWidt');
    
    theme_id = $(this).val();
    color_id = $('ul.theme-colors li.themeclrselected.active', box).data('index');
    
    if(theme_id && color_id)
    {
        modal = swal({
            title: "Are you sure?",
            /*text: "You will not be able to restore this!",*/
            type: "info",
            showCancelButton: true,
            confirmButtonColor: '#46be8a',
            confirmButtonText: 'Yes',
            cancelButtonText: "No!",
            /*closeOnConfirm: false,*/
            //closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                //create new cart row
                ajaxRequest(
                    true,
                    url.newToSession,
                    {'theme_id': theme_id, 'color_id': color_id},
                    function(res){
                        result = $.parseJSON(res);
                        if(result.status)
                        {
                            alertify.success('New site is created and you can customize it. Automatically redirect in 2 seconds.');
                            
                            setTimeout(function(){
                                document.location.href = url.toPageBuilder;
                            }, 2000); 
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