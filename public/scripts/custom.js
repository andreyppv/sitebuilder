var APPLICATION = function() {
    var initPage = function() {
        //hide loading screen
        if(common.builderPage == false) {
            hideLoading();
        }
    };
    
    return {
        init: function() {
            initPage();            
        },
    }
}();

$(document).ready(function() {
    APPLICATION.init();
});