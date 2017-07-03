resizeIndexBanner();

//when click Menu on the top right            
$("#menuclick").click(function() {
    $("#menuList").slideToggle();
});

$("#menuList li a").click(function() {
    $(".menuList").slideToggle();
});

$("#loginPop").click(function() {
    $("#menuList").slideUp("slow");
});

$('#btn-signup-now').click(function() {
    $('html, body').animate({
        scrollTop: $("#signup_content").offset().top
    }, 500);
});

//testimonial slider
$('.indexFooterLeft:gt(0)').hide();
setInterval(function() {
    $(".indexFooterLeftSlide:first-child").fadeOut(2000).next(".indexFooterLeftSlide").fadeIn(2000).end().appendTo("#show_case")
}, 7000);

//windows resize event
$(window).resize(function() {
    resizeIndexBanner();
});

///////////////////////////////////////////////////////
//functions
///////////////////////////////////////////////////////
function resizeIndexBanner()
{
    var winHei = $(window).height();
    var winWidt = $(window).width();
    $("#indexBanner").css({
        "width": winWidt,
        "height": winHei
    });
}