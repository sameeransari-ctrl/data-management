
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//Toastr Option
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "closeHtml": '<span class="btn-trigger">Close</span>',
    "preventDuplicates": true,
    "showDuration": "1500",
    "hideDuration": "1500",
    "timeOut": "5000",
    "toastClass": "toastr",
    "extendedTimeOut": "5000"
};


//Common success toaster
window.successToaster = function (message, title = '') {
    toastr.remove();
    toastr.options.closeButton = true;
    toastr.success(message, title, { timeOut: 5000 });
}
window.errorToaster = function (message, title) {
    toastr.remove();
    toastr.options.closeButton = true;
    toastr.error(message, title, { timeOut: 5000 });
}
window.handleError = function (errorResponse) {
    if (errorResponse.responseText) {
        var errors = JSON.parse(errorResponse.responseText);
        if (errorResponse.status === 422 || errorResponse.status === 429) {
            if (errors.errors) {
                $('.error-help-block').show();
                for (var field in errors.errors) {
                    $('#' + field + '-error').html(errors.errors[field]);
                    $('#' + field + '-error').parent('.form-group').removeClass('has-success').addClass('has-error');
                }
            } else {
                errorToaster(errors.error.message);
            }
        } else if(errorResponse.status === 403) {
            window.location.reload();
        } else {
            if (errors.message) {
                errorToaster(errors.message);
            } else {
                errorToaster(errors.error.message);
            }
            return false;
        }
    } else if (errorResponse.status === 0) {
        errorToaster(internetConnectionError);
    } else {
        errorToaster(errorResponse.statusText);
    }
}

window.elementEnableDisabled = function(id,flag){
    if(flag === true){
        $(id).attr("disabled","disabled");
    }else{
        $(id).removeAttr("disabled");
    }
}

window.pageLoader = function (id) {
    $('#' + id).html('<div class="pageLoader text-center"><div class="spinner-border" role="status"></div></div>');
}

window.loadEditor = function(descriptionClass){
    $('.'+descriptionClass).summernote({
        tabsize: 1,
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'strikethrough', 'clear']],
            ['font', ['superscript', 'subscript']],
            ['color', ['color']],
            ['fontsize', ['fontsize', 'height']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']],
        ]
    });
}

window.showButtonLoader = function (btnObj, btnName, btnStatus) {
    if (btnStatus) {
        $(btnObj).html(btnName+ '&nbsp;<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="visually-hidden"></span>');
    } else {
        btnObj.html(btnName);
    }
    btnObj.attr("disabled", btnStatus);
}

/* for close */
$(document).on('click', '.custom-close', function(e) {
    e.preventDefault();
    var modal = $(this).parents('div.modal');
    var form = modal.find('form');
    form[0].reset();
    modal.find('.error-help-block').html('');
    modal.modal('hide');
});

window.getLocalTime = function(datetime, format = 'DD MMM YYYY, hh:mm A')
{
    return moment.utc(datetime).local().format(format);
}

window.getLoader = function() {
    return '<div class="text-center contentLoader"><div class="spinner-border" role="status"> <span class="sr-only"></span></div></div>';
}

window.commanLoadSelect2 = function() {
    $('.commonSelect2').select2();
    $('.commonSelect2').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });
}
$(document).ready(function () {
    var dropdownMenu;
    let parents = [];
    let menus = [];
    $('table').on('show.bs.dropdown', function (e) {

    let target = $(e.target);
    // save the parent
    parents.push(target.parent());
    // grab the
    let dropdownMenu = target.next();
    // save the menu
    menus.push(dropdownMenu);
    // detach it and append it to the body
    $('body').append(dropdownMenu.detach());
    // grab the new offset position
    let eOffset = target.offset();
    // make sure to place it where it would normally go (this could be improved)
    dropdownMenu.css({
        'display': 'block','top': eOffset.top + target.outerHeight(),
        'left': eOffset.left});
    dropdownMenu.addClass("tableDropdown");
    });
    $('table').on('hide.bs.dropdown', function (e) {
        menus.forEach(function(element, index) {
            let parent = parents[index];
        let dropdownMenu = element;
        parent.append(dropdownMenu.detach());
        dropdownMenu.hide();
        menus.splice(index, 1);
        parents.splice(index, 1);
        })

    });
});
