$("#updateProfileBtn").on('click', (function (e) {
    e.preventDefault();
    var frm = $('#updateProfileForm');
    var btn = $('#updateProfileBtn');
    var btnName = btn.html();
    if (frm.valid()) {
        showButtonLoader(btn, btnName, 'disabled');
        var formData = new FormData(frm[0]);

        if (formData.get('profile_image')) {
            var file = imageBase64toFile(formData.get('profile_image'), 'user_image');
            formData.delete('profile_image');
            formData.append("profile_image", file); // remove base64 image content
        }
        $.ajax({
            url: frm.attr('action'),
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                showButtonLoader(btn, btnName, 'enable');
                successToaster(response.message);
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function (err) {
                showButtonLoader(btn, btnName, false);
                var obj = JSON.parse(err.responseText);
                errorToaster(obj.message)
                for (var x in obj.errors) {
                    $('#' + x + '-error').html(obj.errors[x][0]);
                    $('#' + x + '-error').parent('.form-group').removeClass('has-success').addClass('has-error');
                }
            },
        });
    }
}));
$(document).on("hidden.bs.modal", '#profile-edit', function() {
    $("#updateProfileForm")[0].reset();
    $('#imagePreview').attr('src',$('#currentProfileImage').attr('src'));
    $("#updateProfileForm").validate().resetForm();
});


$( document ).ready(function() {
    $(".js-select2").select2();
});
