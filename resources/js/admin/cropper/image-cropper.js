$('body').on('change','.customCropperImage',function(){
    setImage(this, $(this), $(this).data('path'));
});
window.fileExtension = 'jpeg';

/* image cropper functions*/
window.setImage = function (input, obj, img_type = '') {

    // Get ids for show image after crop
    var previewId = obj.attr("data-preview-id");
    var base64InputId = obj.attr("data-base64-id");
    var fileInputId = obj.attr("id");

    // get image height , width from attribute
    var imageWidthHeight = obj.attr("data-width-height");
    var width, height;
    if (imageWidthHeight) {
        imageWidthHeight = imageWidthHeight.split('X');
        width = imageWidthHeight[0];
        height = imageWidthHeight[1];
    } else {
        width = 200; //set default width
        height = 200; //set default height
    }

    var zoomable = obj.data("zoomable");  //get accepted file from attr
    var zoomOnWheel = obj.data("zoomonwheel");  //get accepted file from attr
    var acceptedFiles = obj.attr("data-accept-file");  //get accepted file from attr
    acceptedFiles = acceptedFiles.split('.').join('');
    var fileTypes = acceptedFiles.split(','); // convert string to array


    let aspectRatio = obj.data("aspect-ratio");
    let cropBoxResizable = obj.data("crop-box-resizable");

    if (fileTypes.length <= 0) {
        fileTypes = ['jpg', 'jpeg', 'png']; // Set default accepted files
    }

    $('#crop_image').attr('src', ''); // Remove croper old image

    if (input.files && input.files[0]) {

        var extension = input.files[0].name.split('.').pop().toLowerCase(), //file extension from input file
            isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

        if (isSuccess) {
            var max_size = obj.attr("data-max-size"); //get image size from attr
            if (!max_size) {
                max_size = 5000000; // Set default size 5MB
            }
            if (input.files[0].size >= (max_size * 1000000)) {
                if($('#__section').val() == 'frontend'){
                    frontendToaster('error','Image may not be greater than ' + max_size + 'MB.');
                }else{
                    toastr.error('Image may not be greater than ' + max_size + 'MB.', '', { timeOut: 8000 });
                }

            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    //Initiate the JavaScript Image object.
                    var image = new Image();
                    //Set the Base64 string return from FileReader as source.
                    image.src = e.target.result;

                    //Validate the File Height and Width.
                    image.onload = function () {

                        $("#imageCropperModal").modal("show");
                        $('#crop_image').attr('src', e.target.result); // set image in cropper
                        $('#imageBaseCode').val(e.target.result);
                        $('#image_type').val(img_type);

                        $('#__previewId').val(previewId);
                        $('#__base64InputId').val(base64InputId);
                        $('#__fileInputId').val(fileInputId);

                        $('#__imageHeight').val(height);
                        $('#__imageWidth').val(width);

                        $('#__aspectRatio').val(aspectRatio);
                        $('#__cropBoxResizable').val(cropBoxResizable);


                        $('#__cropperZoomable').val(zoomable);
                        $('#__cropperZoomOnWheel').val(zoomOnWheel);

                        fileExtension = extension;

                        setTimeout(function () {
                            loadCropper(obj);
                        }, 150);
                    };
                };
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            if ($('#__section').val() == 'frontend') {
                frontendToaster('error','Please select ' + acceptedFiles.split('.').join('') + ' file only.');
            } else {
                toastr.error('Please select ' + acceptedFiles.split('.').join('') + ' file only.', '', { timeOut: 8000 });
            }
        }

    }
}

$('#imageCropperModal').on('hidden.bs.modal', function (e) {
    $('#crop_image').attr('src', '');
    var $image = $("#crop_image");
    var input = $("#cropImageInput");
    input.replaceWith(input.val('').clone(true));
    $image.cropper("destroy");
    var fileInput = $('#__fileInputId').val();
    $("#" + fileInput).val(null);
});

/* image cropper */
window.loadCropper = function (obj) {
    var ratio = getImageRatio($('#__imageWidth').val(), $('#__imageHeight').val());
    var aspectRatio = $("#__aspectRatio").val();
    var cropBoxResizable = $("#__cropBoxResizable").val();
    if ("undefined" == typeof aspectRatio || aspectRatio == '') {
        aspectRatio = ratio.w / ratio.h;
    }
    if ("undefined" == typeof cropBoxResizable || cropBoxResizable == '') {
        cropBoxResizable = false;
    }
    var zoomable = ($('#__cropperZoomable').val() == 1) ? true : false;
    var zoomOnWheel = ($('#__cropperZoomOnWheel').val() == 1) ? true : false;
    var $image = $("#crop_image");
    $image.cropper({
        aspectRatio: aspectRatio,
        cropBoxResizable: cropBoxResizable,
        autoCropArea: 0,
        resize: false,
        strict: false,
        highlight: false,
        dragCrop: false,
        zoomable: zoomable,
        zoomOnTouch: false,
        zoomOnWheel: zoomOnWheel,
        dragMode: 3,
        minContainerWidth:300,
        minContainerHeight:300,
        minCanvasWidth: 300,
        minCanvasHeight: 300


    });
}

function getImageRatio(w, h) {
    var r = gcd(w, h);
    return { 'w': w / r, "h": h / r };
}
function gcd(a, b) {
    return (b == 0) ? a : gcd(b, a % b);
}

$("#cropButton").on('click', (function (e) {
    if($('#__section').val() == 'frontend'){
        $('#background_loader').removeClass('d-none').addClass('d-flex');
    }
    var $imageCover = $("#crop_image");
    if (typeof $imageCover.val() !== "undefined") {
        var imageData = $imageCover.cropper('getCroppedCanvas', { 'width': $('#__imageWidth').val(), 'height': $('#__imageHeight').val(), 'imageSmoothingQuality': 'medium' }).toDataURL('image/'+fileExtension);

        var previewId = $('#__previewId').val();
        var base64InputId = $('#__base64InputId').val();
        var fileInputId = $('#__fileInputId').val();

        $('#' + fileInputId).val('');
        $('#' + base64InputId).val("");
        $('#' + base64InputId).val(imageData);
        $('#' + previewId).attr("src", imageData);
    }
    $("#imageCropperModal").modal("hide");
    $('body').removeClass('modal-open');
    return true;
}));

// convert base64 image to file object
window.imageBase64toFile = function imageBase64toFile(base64Image, filename) {
    var arr = base64Image.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

    var ext = mime.split('/')[1]; // get image extension
    if (!ext) {
        ext = 'png'; // set default extension
    }

    if (!mime) {
        mime = 'image/png'; //set default image type
    }

    filename = filename + '.' + ext;

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, { type: mime });
}

$(function(){
    $('body').on('click','.close-modal',function(){
        $('#uploadImage').val("");
        $("#"+$(this).data('close_id')).modal("hide");
        $('body').removeClass('modal-open');
    });
});
