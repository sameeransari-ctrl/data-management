let productId = '';
let questionId = '';

let products = {
    init: function() {
        products.clientOtherShowHide();
        products.addRemoveFileURL();
        products.questionAnswerType();
        products.formStepSection();
        products.saveProduct();
        //add question modal & store
        products.addQuestionModal();
        products.addQuestionBtn();
        //edit question modal & update
        products.editQuestionModal();
        products.updateQuestionBtn();
        //remove question
        products.questionRemove();
        products.getProductQuestions();
        products.updateProductStatus();
        products.getClientBasicUdids();
    },

    /* Start function products - clientOtherShowHide here */
    clientOtherShowHide: function(){
        $(document).on('change', '#client_id', function() {
            $('#otherClientNameSection').addClass('d-none');
            if($(this).val()==='other'){
                $('#client_name').val('');
                $('#otherClientNameSection').removeClass('d-none');
            }
        });
    },
    /* End function products - clientOtherShowHide here */

/* Start function products - addRemoveFileURL here */
    addRemoveFileURL: function(){
        var maxField = 10; // Input fields increment limitation
        var wrapper = $('.field_wrapper'); // Input field wrapper

        // Function to re-index elements
        function reIndexElements() {
            var x = 0;
            $(wrapper).find('.copy-link').each(function(index, div) {
                $(div).find('.copy-link-input').attr('aria-describedby', `file_url-${x}-error`);
                $(div).find('.help-block.error-help-block').attr('id', `file_url-${x}-error`);
                x++;
            });

            // Re-index span elements
            x = 0;
            $(wrapper).find('.help-block.error-help-block').each(function(index, span) {
                $(span).attr('id', `file_url-${x}-error`);
                x++;
            });
        }

        // Once add button is clicked
        $(document).on('click', '.add_button', function() {
            var x = $('.field_wrapper .copy-link').length; // Get the current number of fields
            if(x < maxField){
                var fieldHTML = `<div class="mt-2"><div class="input-group copy-link"><input type="text" class="form-control shadow-none copy-link-input" name="file_url[]" placeholder="Add File URL" value="" aria-describedby="file_url-${x}-error"> <div class="input-group-append"><span class="input-group-text cursor-pointer copy-link-button" id="copyUrlText2"><em class="icon ni ni-link fs-14px"></em></span></div><a class="remove_button addMoreBtn" title="Add field"><em class="icon ni ni-minus-sm"></em></a></div><span id="file_url-${x}-error" class="help-block error-help-block"></span></div>`; // New input field html
                $(wrapper).append(fieldHTML); // Add field html
                reIndexElements(); // Re-index elements after adding
            }
        });

        // Once remove button is clicked
        $(document).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).closest('.mt-2').remove(); // Remove the closest parent with class 'mt-2'
            reIndexElements(); // Re-index elements after removal
        });
    },
    /* End function products - addRemoveFileURL here */

    /* Start function products - questionAnswerType here */
    questionAnswerType: function(){
        $(document).on('change', '#answer_type', function() {
            var myID = $(this).val();
            if(myID == 'check_box') {
                myID1 = 1;
            } else if(myID == 'radio_button') {
                myID1 = 2;
            } else {
                myID1 = 3;
            }

            $('#answer_type_value1').val(myID1);
            $('#answer_type_value').val(myID);

            $("input[name='answer_options_check_box_default_answer[]']").prop('checked', false);
            $(`#${myID} input`).val('');
            $('#answer_type_value').val(myID);
            /* if(myID=='check_box'){
                //checkbox section
                $("input[name='answer_options_check_box_default_answer[]']").prop('checked', false);
                //$("input[name='answer_options_check_box[]']").val('');
                $(`#${myID} input`).val('');
            } else if(myID=='radio_button'){
                //radio button section
                //$("input[name='answer_options_radio_button[]']").val('');
                $(`#${myID} input`).val('');
            } else {
                //checkbox section
                $("input[name='answer_options_check_box_default_answer[]']").prop('checked', false);
                $("input[name='answer_options_check_box[]']").val('');
                //radio button section
                $("input[name='answer_options_radio_button[]']").val('');
            } */
            $('.panel, .select-panel').each(function(){
               myID === $(this).attr('id') ? $(this).removeClass('d-none') : $(this).addClass('d-none');
            });
        });
    },
    /* End function products - questionAnswerType here */

    /* Start function products - formStepSection here */
    formStepSection: function(){
        //Fieldsets Step Form Start
        var current_fs, next_fs, previous_fs;
        var opacity;
        $(document).on('click', '.next, .skip', function() {

            current_fs = $(this).closest('fieldset');
            next_fs = $(this).closest('fieldset').next();
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });

            /* $('.form-select').select2({
                minimumResultsForSearch: -1
            }); */
            commanLoadSelect2();

        });
        $(document).on('click', '.previous', function() {
            current_fs = $(this).closest('fieldset');
            previous_fs = $(this).closest('fieldset').prev();
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
            /* $('.form-select').select2({
                minimumResultsForSearch: -1
            }); */
            commanLoadSelect2();
        });
        //Fieldsets Step Form End
    },
    /* End function products - formStepSection here */

    /* Start function saveProduct here */
    saveProduct:function(){
        $(document).on('click', '#productSubmitBtn', function(e) {
            e.preventDefault();
            let frm = $('#editProductForm');
            let btn = $('#productSubmitBtn');
            let btnHidden = $("#productSubmitBtn_hidden");
            let cancelBtn = $('#productCancelBtn');
            let btnName = btn.text();

            url = frm.attr('action');
            methodType = 'PUT';
            formData = frm.serialize();

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                $.ajax({
                    type: methodType,
                    url: url,
                    data: formData,
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled',true);
                    },
                    success: function(response) {
                        if (response.success) {
                            //successToaster(response.message);

                            productId = response.data.productId;
                            btnHidden.trigger("click");

                            $('#questionForReviewListing .col-md-12').remove();
                            $('#questionForProductListing .col-md-12').remove();
                            $("#questionForReviewListing").append(response.data.html);
                            $("#questionForProductListing").append(response.data.html1);
                        }
                    },
                    error: function(err) {
                        var obj = jQuery.parseJSON(err.responseText);
                        for (var x in obj.errors) {
                            var ids = x;
                            if(obj.errors[x][0] == 'The file url must be a valid url.'){
                                idsArr =  x.split(".");
                                id = idsArr[1];
                                idsArr[1] = parseInt(id);
                                ids =  idsArr.join("-");
                            }
                            if(obj.errors[x][0] == 'The file url not allowed.'){
                                ids =  x.split(".").join("-");
                            }
                            $('#' + ids + '-error').css('display', 'block');
                            $('#' + ids + '-error').html(obj.errors[x][0]);
                            $('#' + ids + '-error').parent('.form-group').removeClass('has-success').addClass('has-error');
                        }
                    },
                    complete: function() {
                        showButtonLoader(btn, btnName, false);
                        cancelBtn.prop('disabled',false);
                    }
                });
            }
        });
    },
    /* End function saveProduct here */

    /* Start function products - addQuestionModal here */
    addQuestionModal: function(){
        $(document).on('click', '.addQuestionModal', function(e) {
            e.preventDefault();
            let btn = $(this);
            let btnName = btn.text();

            let questionType = btn.attr('data-question_type');

            $('#addQuestion').modal('show');
            $('#addQuestionModalContent').html(getLoader());
            $.ajax({
                type: "GET",
                url: route('admin.product.add-question', questionType),
                beforeSend: function() {
                    //showButtonLoader(btn, btnName, true);
                    btn.prop('disabled', true);
                },
                success: function(response) {
                    if(response.success){
                        $("#addQuestionModalContent").html(response.data);

                        commanLoadSelect2();
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    //showButtonLoader(btn, btnName, false);
                    btn.prop('disabled', false);
                }
            });
        });
    },
    /* End function products - addQuestionModal here */

    /* Start function products - addQuestionBtn here */
    addQuestionBtn: function(){
        $(document).on('click', '#addQuestionBtn', function(e) {
            e.preventDefault();
            let frm = $('#addQuestionForm');
            let btn = $('#addQuestionBtn');
            let cancelBtn = $('#questionCancelBtn');
            let btnName = btn.text();
            let productId = $("#questionForReviewSaveNContinueBtn").attr("data-product_id");
            if (frm.valid()) {
                var questionError = true;
                var allQuestions = [];
                var answer_type = $('#answer_type_value').val();
                if(answer_type == '') {
                    var answer_type = $('#answer_type').val();
                }

                if(answer_type == "radio_button" || answer_type == "check_box") {
                    if(answer_type == "radio_button"){
                        var questionType = "radio-value";
                    }else{
                        var questionType = "checkbox-value";
                    }
                    $('.'+questionType).each(function () {
                        var questionVal = $(this).val();
                        if (allQuestions.indexOf(questionVal) !== -1) {
                            errorToaster();
                            errorToaster("Answer Options should be unique", "Invalid");
                            $(this).val("");
                            questionError = false;
                        } else {
                            allQuestions.push(questionVal);
                            questionError = true;
                        }
                    });
                }
                if (questionError) {
                    showButtonLoader(btn, btnName, 'disabled');
                    $.ajax({
                        type: 'POST',
                        url: frm.attr('action'),
                        data: frm.serialize() + "&product_id="+productId,
                        beforeSend: function() {
                            showButtonLoader(btn, btnName, true);
                            cancelBtn.prop('disabled',true);
                        },
                        success: function(response) {
                            if (response.success) {
                                successToaster(response.message);

                                $("#addQuestion").modal("hide");

                                let questionType = response.data.questionType;
                                //1: question for review, 2: question for product
                                if (questionType==1) {
                                    $("#questionForReviewListing").append(response.data.html);
                                } else if (questionType==2) {
                                    $("#questionForProductListing").append(response.data.html);
                                }

                                $(".js-select2").select2();
                            }
                        },
                        error: function(err) {
                            handleError(err);
                        },
                        complete: function() {
                            showButtonLoader(btn, btnName, false);
                            cancelBtn.prop('disabled',false);
                        }
                    });
                }
            }
        });
    },
    /* End function products - addQuestionBtn here */

    /* Start function products - editQuestionModal here */
    editQuestionModal: function(){
        $(document).on('click', '.editQuestionModal', function(e) {
            e.preventDefault();
            let btn = $(this);
            let btnName = btn.text();
            let removeBtn = $('.removeData');
            //let questionType = btn.attr('data-question_type');
            let questionId = btn.attr('data-question_id');

            $('#editQuestion').modal('show');
            $('#editQuestionModalContent').html(getLoader());
            $.ajax({
                type: "GET",
                url: route('admin.product.edit-question', questionId),
                beforeSend: function() {
                    //showButtonLoader(btn, btnName, true);
                    removeBtn.prop('disabled',true);
                },
                success: function(response) {
                    if(response.success){
                        $("#editQuestionModalContent").html(response.data);

                        $(".js-select2").select2();
                        //commanLoadSelect2();
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    //showButtonLoader(btn, btnName, false);
                    removeBtn.prop('disabled',false);
                }
            });
        });
    },
    /* End function products - editQuestionModal here */

    /* Start function products - updateQuestionBtn here */
    updateQuestionBtn: function(){
        $(document).on('click', '#updateQuestionBtn', function(e) {
            e.preventDefault();
            let frm = $('#editQuestionForm');
            let btn = $('#updateQuestionBtn');
            let cancelBtn = $('#questionCancelBtn');
            let btnName = btn.text();
            if (frm.valid()) {
                var questionError = true;
                var allQuestions = [];
                var answer_type = $('#answer_type_value1').val();
                if(answer_type == 1 || answer_type == 2) {
                    if(answer_type == 2){
                        var questionType = "radio-value-edit";
                    }else{
                        var questionType = "checkbox-value-edit";
                    }
                    $('.'+questionType).each(function () {
                        var questionVal = $(this).val();
                        if (allQuestions.indexOf(questionVal) !== -1) {
                            errorToaster();
                            errorToaster("Answer Options should be unique", "Invalid");
                            $(this).val("");
                            questionError = false;
                        } else {
                            allQuestions.push(questionVal);
                            questionError = true;
                        }
                    });
                }
                if (questionError) {
                    showButtonLoader(btn, btnName, 'disabled');
                    $.ajax({
                        type: 'POST',
                        url: frm.attr('action'),
                        data: frm.serialize(),
                        beforeSend: function() {
                            showButtonLoader(btn, btnName, true);
                            cancelBtn.prop('disabled',true);
                        },
                        success: function(response) {
                            if (response.success) {
                                successToaster(response.message);
                                $("#editQuestion").modal("hide");

                                let questionType = response.data.questionType;
                                let questionId = response.data.questionId;
                                //1: question for review, 2: question for product
                                if (questionType==1) {
                                    $(`#questionForReviewListing #questionId_${questionId}`).replaceWith(response.data.html);
                                } else if (questionType==2) {
                                    $(`#questionForProductListing #questionId_${questionId}`).replaceWith(response.data.html);
                                }
                                //$(".js-select2").select2();
                            }
                        },
                        error: function(err) {
                            handleError(err);
                        },
                        complete: function() {
                            showButtonLoader(btn, btnName, false);
                            cancelBtn.prop('disabled',false);
                        }
                    });
                }
            }
        });
    },
    /* End function products - addQuestionBtn here */

    /* Start function products - questionRemove here */
    questionRemove: function(){
        $(document).on('click', '.removeData', function(e) {
            e.preventDefault();
            let id = $(this).attr('data-question_id');
            let url = route('admin.product.destroy-question',{id});
            Swal.fire({
                allowOutsideClick: false,
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "Delete",
                        url: url,
                        beforeSend: function() {
                            $('#background_loader').removeClass('d-none');
                        },
                        success: function (data) {
                            if (data.success) {
                                Swal.fire(
                                    "Deleted!",
                                    "Question has been deleted.",
                                    "success"
                                )
                                $(`#questionForReviewListing #questionId_${id}`).remove();
                                $(`#questionForProductListing #questionId_${id}`).remove();
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
            });
        });
    },
    /* End function products - questionRemove here */

    /* Start function products - getQuestion here */
    getProductQuestions: function() {
        $(document).on('click', '#questionForReviewSaveNContinueBtn', function(e) {
            e.preventDefault();
            let productId = $(this).attr('data-product_id');
            let questionType = 2;
            $.ajax({
                type: "GET",
                url: route('admin.product.get-questions'),
                data: {product_id:productId, question_type:questionType},
                beforeSend: function() {
                    //showButtonLoader(btn, btnName, true);
                    //removeBtn.prop('disabled',true);
                },
                success: function(response) {
                    if(response.success){
                        if (questionType==1) {
                            $("#questionForReviewListing .col-md-12").remove();
                            $("#questionForReviewListing").append(response.data.html);
                        } else if (questionType==2) {
                            $("#questionForProductListing .col-md-12").remove();
                            $("#questionForProductListing").append(response.data.html);
                        }
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    //showButtonLoader(btn, btnName, false);
                    //removeBtn.prop('disabled',false);
                }
            });
        });
    },
    /* End function products - getQuestion here */

    /* Start function products - updateProductStatus here */

    updateProductStatus: function() {
        $(document).on('click', '#productFinalSubmitBtn', function(e) {
            e.preventDefault();
            let productId = $(this).attr('data-product_id');
            let status = $(this).attr('data-status');
            let btn = $('#productFinalSubmitBtn');
            let removeBtn = $('#productCancelBtn');
            let btnName = btn.text();
            $.ajax({
                type: "PUT",
                url: route('admin.product.update-product-status'),
                data: {product_id:productId, status:status},
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                    removeBtn.prop('disabled',true);
                },
                success: function(response) {
                    if(response.success){
                        successToaster(response.message);
                        window.setTimeout(function() {
                            window.location.href = route('admin.product.index');
                        }, 2000);
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    //showButtonLoader(btn, btnName, false);
                    //removeBtn.prop('disabled',false);
                }
            });
        });
    },
    /* End function products - updateProductStatus here */

    /* Start function products - getClientBasicUdids here */
    getClientBasicUdids: function() {
        $(document).on('change', '#client_id', function(e) {
            e.preventDefault();
            let clientId = $(this).val();
            $.ajax({
                type: "GET",
                url: route('admin.product.basic-udids', clientId),
                success: function(response) {
                    let basicUdidsList = response.data;
                    if (basicUdidsList && basicUdidsList.length > 0) {
                        $('#basic_udid_id').empty();
                        $.each(basicUdidsList, function (key, value) {
                            var newState = new Option(value.name, value.id, false, false);
                            $('#basic_udid_id').append(newState);
                        });
                        $('#basic_udid_id').trigger('change');
                    } else {
                        var newState = new Option("Select Basic Udid No.", "", false, false);
                        $('#basic_udid_id').empty();
                        $('#basic_udid_id').append(newState);
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    //showButtonLoader(btn, btnName, false);
                    //removeBtn.prop('disabled',false);
                }
            });
        });
    },
    /* End function products - getClientBasicUdids here */

};

$(function() {
    products.init();
    $(".client-js-select2").select2({
        placeholder: "Search by client name or actor id"
    });
});
