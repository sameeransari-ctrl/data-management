/* For load summernote editor */
loadEditor('description');

/* For submit CMS */
$("#submitCmsBtn").on('click', (function(e) {
    let frm = $('#editCmsForm');
    let btn = $('#submitCmsBtn');
    let cancelBtn = $('#cancelCmsBtn');
    let btnName = btn.text();
    let url = frm.attr('action');
    if (frm.valid()) {
        $.ajax({
            url: url,
            type: "POST",
            data: frm.serialize(),
            beforeSend: function() {
                showButtonLoader(btn, btnName, true); 
                // cancelBtn.css({'pointer-events':"none"});
            },
            success: function(response) {
                if (response.success) {
                    successToaster(response.message, 'Manage CMS');
                }
                setTimeout(function(){
                    window.location.reload();
                }, 2000);
            },
            error: function(error) {
                handleError(error);
            },
            complete: function() {
                showButtonLoader(btn, btnName, false);
                // cancelBtn.css({'pointer-events':"auto"});
            }
        });
    }
    else {
        $('html, body').animate({
            scrollTop: $('.error-help-block').offset().top
        }, 1000);
    }
}));