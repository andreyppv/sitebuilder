var STORE = function() {
    //update category status
    var helperUpdateCategoryStatus = function() {
        $(document).on('click', '#category-table .category-row-status', function() {
            category_id = $(this).data('index');
            img_obj = $(this);
            
            ajaxRequest(
                true,
                url.updateCategoryStatus,
                {'category_id': category_id},
                function(res) {
                    result = $.parseJSON(res);
                    if(result.status)
                    {
                        if(result.category_status == 1)
                        {
                            img_obj.removeClass('inactive-img').addClass('active-img');
                        }
                        else
                        {
                            img_obj.removeClass('active-img').addClass('inactive-img');
                        }
                        
                        alertify.success(result.msg);
                    }
                    else                                                                  
                    {
                        alertify.error(result.error);
                    }
                }
            );
        });
    };
    
    //remove category item
    var helperDeleteCategory = function(event) {
        $(document).on('click', '#category-table .category-row-remove', function(e) {
            e.preventDefault();
            
            url = $(this).attr('href'); 
            modal = swal({
                title: "Are you sure?",
                /*text: "You will not be able to restore this!",*/
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
                    document.location.href = url;
                }
            });
        });
    };
    
    //get category form
    var helperGetCategoryForm = function() {
        //edit
        $(document).on('click', '#category-table .category-row-name', function() {
            dataArray = {'category_id': $(this).data('index')};   
            
            getCategoryForm(dataArray);           
        });
        
        //create
        $(document).on('click', '#btn-add-category', function() {
            dataArray = {'category_id': ''};  
            
            getCategoryForm(dataArray);       
        });
        
    };
    
    //category form events
    var helperCategoryFormEvent = function() {
        //back to category list page
        $(document).on('click', '#btn-category-form-back, #btn-category-cancel', function() {
            $('#category-form-box').html('').hide();   
            $('#category-list-box').show();
        });
        
        //save category form content
        $(document).on('click', '#btn-category-save', function() {
            if(otrim($('#category_name')) == '')
            {
                if($('.error', $('#category_name').parent()).length == 0)
                {
                    $('#category_name').after("<span class='error' style='color:#FF0000;'>Please enter the Product Type</span>");
                }
                return false;
            }
        });
    };
    
    //remove product item
    var helperDeleteProduct = function(event) {        
        $(document).on('click', '#product-table .btn-product-remove', function(e) {
            e.preventDefault();
            
            url = $(this).attr('href'); 
            modal = swal({
                title: "Are you sure?",
                /*text: "You will not be able to restore this!",*/
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
                    document.location.href = url;    
                }
            });
        });
    };
    
    //get product form
    var helperGetProductForm = function() {
        //add
        $(document).on('click', '#btn-add-product', function() {
            params = {'product_id': ''};  
            
            getProductForm(params);      
        });
        
        //edit
        $(document).on('click', '#product-table .btn-product-edit', function() {
            params = {'product_id': $(this).data('index')};   
            
            getProductForm(params);     
        }); 
    };
    
    //product form events
    var helperProductFormEvent = function() {
        //back to product list page
        $(document).on('click', '#btn-product-form-back, #btn-product-cancel', function() {
            $('#product-form-box').html('').hide();   
            $('#product-list-box').show();
        });
        
        //trial switch
        $(document).on('click', "[name=is_trial]", function(e){
            if($(this).val()=="1") $("#triallendiv").show();
            else $("#triallendiv").hide();
        });
        
        //upload product image
        $(document).on('change', '#prodimg', function() {
            var bar = $('.bar');
            var percent = $('.percent');
            $("#prodimageform").ajaxForm({
                target: '#sortableImg',
                beforeSubmit: function() {
                    console.log('Product image uploading started.');
                    
                    $(".progress").show();
                    $("#imageloadstatus").show();
                    $("#imageloadbutton").hide();
                },
                success: function() {
                    console.log('Product image uploaded.');
                    
                    $("#imageloadstatus").hide();
                    $("#imageloadbutton").show();
                    var prod_id = $('#product_id').val();
                    $("#sortableImg").sortable();
                    $(".progress").hide();
                    
                    params = { 
                        'product_id': $('#product_id').val(),
                        'product_key': $('#product_key').val(),
                    };
                    showProductImages(params);
                    
                    return false;
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    console.log("upload");

                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                error: function() {
                    console.log('xtest');
                    $("#imageloadstatus").hide();
                    $("#imageloadbutton").show();
                }
            }).submit();
        });
        
        //make image as primary
        $(document).on('click', '#sortableImg .prod_radio_img', function() {
            image_id = $(this).val();
            
            ajaxRequest(
                true,
                url.setMainProductImage,
                {
                    'image_id': image_id,
                    'product_id': $('#product_id').val(),
                    'product_key': $('#product_key').val()
                }
            );
        });
        
        //delete product image
        $(document).on('click', '#sortableImg .btn-remove-product-image', function() {
            image_id = $(this).parents('.radio_img').data('index');
            
            modal = swal({
                title: "Are you sure?",
                /*text: "You will not be able to restore this!",*/
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
                        false,
                        url.removeProductImage,
                        {
                            'image_id': image_id, 
                            'product_id': $('#product_id').val(),
                            'product_key': $('#product_key').val()
                        },
                        function(res) {
                            $('#sortableImg').html(res);
                            
                            alertify.success('Image is removed.');
                        }
                    );    
                }
            });    
        });
        
        //submit form
        $(document).on('click', '#btn-product-save', function() {
            var prd_id   = $('#product_id').val();
            var prd_name = $('#product_name').val();
            var prd_description = $('#product_description').val();
            var category        = $("#product_category").val();
            var prd_price       = $('#product_price').val();
            var is_trial        = $('[name=is_trial]:checked').val();
            var trial_length    = $('#trial_length').val();
            var campaign        = $("#product_campaign").val();
            var rebill_price    = $('#rebill_price').val();
            var shipping        = $("#shipping").val();
            var payment_name    = $("#payment_name").val();        
            
            $(".error").remove();
            if(prd_name == "")
            {
                $('#product_name').after("<br /><span style='color:#FF0000;' class='error'>Please enter Product  Name</span>");
                return false;
            }
            else if(category == "")
            {
                $("#product_category").after("<br /><span style='color:#FF0000;' class='error'>Please enter Product Category</span>");
                return false;
            }
            else if(prd_description == "")
            {
                $('#product_description').after("<br /><span style='color:#FF0000;' class='error'>Please enter Product Description</span>");
                return false;
            }
            else if(prd_price == "")
            {
                $('#product_price').after("<br /><span style='color:#FF0000;' class='error'>Please enter Product Price</span>");
                return false;
            }
            else if(trial_length == "")
            {
                $('#trial_length').after("<br /><span style='color:#FF0000;' class='error'>Please enter Trial Length</span>");
                return false;
            }
            else if(campaign == "")
            {
                $("#product_campaign").after("<br /><span style='color:#FF0000;' class='error'>Please enter Campaign ID</span>");
                return false;
            }
            else if(rebill_price == "")
            {
                $('#rebill_price').after("<br /><span style='color:#FF0000;' class='error'>Please enter Rebill Price</span>");
                return false;
            }
            else if(shipping == "")
            {
                $("#shipping").after("<br /><span style='color:#FF0000;' class='error'>Please Select Shipping ID</span>");
                return false;
            }
            else if(payment_name == "")
            {
                $('#payment_name').after("<br /><span style='color:#FF0000;' class='error'>Please enter Payment Name</span>");
                return false;
            }
            else
            {
                //submit    
            } 
        });
    };
        
    return {
        init: function() {
            helperUpdateCategoryStatus(); 
            helperDeleteCategory();  
            helperGetCategoryForm(); 
            helperCategoryFormEvent();
            
            helperDeleteProduct();
            helperGetProductForm();
            helperProductFormEvent();
        },
    }
}();

$(function() {
    STORE.init();
});


//////////////////////////////////////////////////
function getCategoryForm(params) {
    ajaxRequest(
        true,
        url.getCategoryForm,
        params,
        function(res) {
            $('#category-list-box').hide();
            $('#category-form-box').html(res).show();    
        }
    );
}

function getProductForm(params) {
    ajaxRequest(
        true,
        url.getProductForm,
        params,
        function(res) {
            $('#product-list-box').hide();
            $('#product-form-box').html(res).show();    
        }
    );   
}

function showProductImages(params) {
    ajaxRequest(
        true,
        url.getProductImages,
        params,
        function(res) {
            $('#sortableImg').html(res);
        }
    );
}   