var PAGES = function() {
    //checkboxes for page(contact, term...)
    var helperSwitches = function() {
        $(document).on('click', '.static_type', function() {
            var checked  = $(this).is(":checked");
            $(".static_type").removeAttr("checked");
            
            if(checked) $(this).prop('checked', true);
        });
    };
    
    //change page when click left menu
    var helperChangePage = function() {
        $(document).on('click', 'li.pageRow a', function() {
            if($(this).hasClass('active')) return;
            
            page_id = $(this).data('index');
            ajaxRequest(
                true,
                url.pageEdit,
                {'page_id':page_id},
                helperUpdatePage
            );            
        });
    };
    
    //add new page when click Add
    var helperAddPage = function() {
        $(document).on('click', '#btn-add-new-page', function() {
            ajaxRequest(
                true,
                url.pageCreate,
                null,
                helperUpdatePage
            ); 
        });            
    };
    
    //delete page when click delete
    var helperDeletePage = function() {
        $(document).on('click', '#btn-page-delete', function() {
            page_id = $('#page_id').val();
            
            ajaxRequest(
                true,
                url.pageDelete,
                {'page_id': page_id},
                function(res) {
                    $('#inner-content').html(res);
                                
                    updateLeftRightHeight();
                    
                    alertify.success('Page is deleted successfully.');
                }
            ); 
        });
    };
    
    //save page content to db
    var helperStorePage = function() {
        $(document).on('click', '#btn-page-save', function() {
            title = $("#page_title");
            msg   = $("#domainv");
               
            if(otrim(title) == "")
            {            
                msg.addClass('errormsg').html("Please enter Page title.");               
                title.focus();
                
                return false;
            }
            else
            {
                ajaxRequest(
                    true,
                    url.pageStore,
                    $('#page-form').serialize(),
                    function(res) {
                        $('#inner-content').html(res);
                                    
                        updateLeftRightHeight();
                        
                        alertify.success('Page is updated.');
                    }
                ); 
            }    
        });
    };
    
    var helperUpdatePage = function(res) {
        $('#inner-content').html(res);
                    
        updateLeftRightHeight();
    };
    
    return {
        init: function() {
            helperSwitches();
            helperChangePage();    
            helperAddPage();
            helperDeletePage();
            helperStorePage();
        },
    }
}();

$(function() {
    PAGES.init();
});


//////////////////////////////////////////////////