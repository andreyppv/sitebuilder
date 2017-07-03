//pending buttons
$('.btn-for-pending').click(function() {          
    document.location.href = $(this).data('href');
});

//site options
$('.sitedomainoption').click(function() {
    $('.domainoptiontoggle').not($('.domainoptiontoggle', $(this).parent())).hide('fast');
    
    $('.domainoptiontoggle', $(this).parent()).fadeToggle();
});