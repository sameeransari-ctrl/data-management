let setting = {
    init: function() {

        setting.save();
        setting.runCommand();
    },



    /* Start function save here */
    save:function(){
        $(document).on('click', '#settingSubmitBtn', function(e) {
            e.preventDefault();
            let frm = $('#addForm');
            let btn = $('#settingSubmitBtn');
            let btnName = btn.text();
            let url = frm.attr('action');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                var formData = new FormData(frm[0]);

                if ($('#uploadImageBase64').val().split(',').length > 1) {
                    var file = imageBase64toFile(formData.get('app_logo'), 'logo');
                    formData.delete('app_logo');
                    formData.append("app_logo", file); // remove base64 image content
                }
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                    },
                    success: function(response) {
                        if (response.success) {
                            successToaster(response.message, 'Setting');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function(err) {
                        handleError(err);
                    },
                    complete: function() {
                        showButtonLoader(btn, btnName, false);
                    }
                });
            }
        });
    },
    /* End function saveLogo here */

    /* Start function runCommand here */
    runCommand:function(){
        $(document).on('click', '.run-command', function(e) {
            let id = $(this).attr('rel');
            let url = route('admin.setting.runCommand',{id}) ;
            Swal.fire({
                allowOutsideClick: false,
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        beforeSend: function() {
                            $('#background_loader').removeClass('d-none');
                        },
                        success: function (data) {
                            if (data.success) {
                                Swal.fire(
                                    "Action Performed!",
                                    "Run the command.",
                                    "success"
                                );
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }
                        },
                        error: function (err) {
                            handleError(err);
                        },
                        complete: function() {
                            $('#background_loader').addClass('d-none');
                        }
                    });
                }
                if(result.isDismissed){
                    if ($(this).is(':checkbox')) {
                        $(this).prop("checked", !$(this).prop("checked"));
                    }
                }
            });
        });
    }
    /* End function runCommand here */
};

$(function() {
    setting.init();
});
