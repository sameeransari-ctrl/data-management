let clients = {
    init: function () {
        clients.register();
        clients.changeCountry();
    },

    register:function(){
        $(document).on('click', '#registerBtn', function(e) {
            e.preventDefault();
            let frm = $('#client-register-form');
            let btn = $('#registerBtn');
            let btnName = btn.text();
            let url = frm.attr('action');
            let method = frm.attr('method');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                var formData = new FormData(frm[0]);
                $.ajax({
                    type: method,
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        $("#cancelBtn").addClass("disabledSubmitAnchorBtn");
                    },
                    success: function(response) {
                        if(response.success){
                            successToaster(response.message);
                            setTimeout(function () {
                                window.location.href = response.redirectionUrl;
                            }, 1000);
                        }else{
                            errorToaster(response.message,'Register');
                        }
                    },
                    error: function(err) {
                        handleError(err);
                    },
                    complete: function() {
                        showButtonLoader(btn, btnName, false);
                        $("#cancelBtn").removeClass("disabledSubmitAnchorBtn");
                    }
                });
            }
        });
    },

    changeCountry: function () {
        $(document).on('change', '#countryId', function () {
            let countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    type: 'GET',
                    url: route('client.register.cities', countryId),
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
            }
        });
    },

};

$(function () {
    clients.init();

    $("#countries").select2({
        templateResult: function(item) {
        return format(item, false);
        },
        templateSelection: function(item) {
        return format(item, true);
        }
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
});
