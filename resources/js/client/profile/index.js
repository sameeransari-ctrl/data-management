$("#updateProfileBtn").on('click', (function (e) {
    e.preventDefault();
    var frm = $('#updateProfileForm');
    var btn = $('#updateProfileBtn');
    var btnName = btn.html();
    let cancelBtn = $('#cancelBtn');
    if (frm.valid()) {
        showButtonLoader(btn, btnName, 'disabled');
        cancelBtn.prop('disabled', true);
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
                cancelBtn.prop('disabled', false);
                successToaster(response.message);
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function (err) {
                showButtonLoader(btn, btnName, false);
                cancelBtn.prop('disabled', false);
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


$(document).on('change', '#countryId', function () {
    let countryId = $(this).val();
    $.ajax({
        type: 'GET',
        url: route('client.profile.cities', countryId),
        success: function (response) {
            let cityList = response.data;
            if (cityList && cityList.length > 0) {
                $('#cityId').empty();
                $.each(cityList, function (key, value) {
                    var newState = new Option(value.name, value.id, false, false);
                    $('#cityId').append(newState);
                });
                $('#cityId').trigger('change');
            } else {
                var newState = new Option("Select City", "", false, false);
                $('#cityId').empty();
                $('#cityId').append(newState);
            }
        },
        error: function (err) {
            //handleError(err);
            return optionData;
        },
        complete: function () {
            $('#background_loader').addClass('d-none');
        }
    });
});

$(document).on("hidden.bs.modal", '#profile-edit', function() {
    $("#updateProfileForm")[0].reset();
    $('#imagePreview').attr('src',$('#currentProfileImage').attr('src'));
    $("#updateProfileForm").validate().resetForm();
});

$(document).ready(function () {
$("#countries").select2({
    templateResult: function(item) {
    return format(item, false);
    },
    templateSelection: function(item) {
    return format(item, true);
    }
});
});

function format(item) {
    if (!item.id) {
      return item.text;
    }
    var img = $("<img>", {
      class: "img-flag",
      width: 21,
      src: $(item.element).attr('data-flag')
    });
    var span = $("<span>", {
      text: " " + item.text
    });
    span.prepend(img);
    return span;
}
