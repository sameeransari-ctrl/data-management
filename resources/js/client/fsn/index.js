let fsn = {
    init: function () {
        fsn.addModal();
        fsn.changeAttachType();
        fsn.changeUdi();
        fsn.firstModalSubmit();
        fsn.editFsn();
        fsn.save();
        fsn.list();
        fsn.exportCsv();
        fsn.selectFile();
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var search = $('#fsn-list-table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';
            var searchByRecord = '?search=' + searchData + '&export=export';
            var url = route('client.fsn.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

    addModal: function () {
        $(document).on('click', '.addEditPreviewFsn', function () {
            let btn = $('#addFsnBtn');
            let btnName = btn.html();
            let url = route('client.fsn.create');
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                },
                success: function (response) {
                    $('#addFsn').html(response).modal('show');
                    $("#previewSection").hide();
                    $("#addEditPreview").text("Add FSN");
                    $(".js-select2").select2();
                },
                error: function (err) {
                    handleError(err);
                },
                complete: function () {
                    showButtonLoader(btn, btnName, false);
                }
            });
        });
    },

    changeAttachType: function () {
        $(document).on('change', '#selectType08, #selectType09', function () {
            var myID = $(this).val();
            $('.panel, .select-panel').each(function () {
                myID === $(this).attr('id') ? $(this).removeClass('d-none') : $(this).addClass('d-none');
            });
        });
    },

    changeUdi: function () {
        $(document).on('change', '#productId', function () {
            let productId = $(this).val();
            $.ajax({
                type: 'GET',
                url: route('client.fsn.getSingleProduct', productId),
                success: function (response) {
                    $("#productName").val(response.product_name);
                    $("#udiNumber").val(response.udi_number);
                },
                error: function (err) {
                    handleError(err);
                },
                complete: function () {
                    $('#background_loader').addClass('d-none');
                }
            });
        });
    },

    firstModalSubmit: function () {
        $(document).on('click', '#submitFsn', function (e) {
            e.preventDefault();
            let frm = $('#fsnAddEditPreviewForm');
            if (frm.valid()) {
                if($("#selectType08").val() == 'url') {
                    url = $("#input-url").val();
                    var regex = /(?:https?):\/\/(\w+:?\w*)?(\S+)(:\d+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
                    if(!regex .test(url)) {
                        $('#input-url-error').removeClass('d-none');
                        $("#input-url-error").css('display','block').text('Please enter valid url');
                        $("#previewSection").hide();
                        $("#addEditSection").show();
                    } else {
                        $("#addEditSection").hide();
                        $("#addEditPreview").text("Preview");
                        $("#previewSection").show();

                        $("#title1").text($("#title").val());
                        $("#udiNumber1").text($("#udiNumber").val());
                        $("#productName1").text($("#productName").val());
                        $("#description1").text($("#description").val());

                        $("#url1").show();
                        $("#video1").hide();
                        $("#xlsx1").hide();
                        $("#urltitle").prop('title', $(".url").val());
                    }
                } else {
                    $("#addEditSection").hide();
                    $("#addEditPreview").text("Preview");
                    $("#previewSection").show();

                    $("#title1").text($("#title").val());
                    $("#udiNumber1").text($("#udiNumber").val());
                    $("#productName1").text($("#productName").val());
                    $("#description1").text($("#description").val());

                    if($("#selectType08").val() == 'xlsx'){
                        $("#xlsx1").show();
                        $("#video1").hide();
                        $("#url1").hide();
                        $("#xlsxtitle").prop('title', $(".xlsx_file").val());
                    } else if($("#selectType08").val() == 'video'){
                        $("#video1").show();
                        $("#xlsx1").hide();
                        $("#url1").hide();
                        $("#videotitle").prop('title', $(".video_file").val());
                    }
                }
            }
        });
    },

    editFsn: function () {
        $(document).on('click', '#editBtn', function () {
            $("#addEditPreview").text("Edit FSN");
            $("#previewSection").hide();
            $("#addEditSection").show();
        });
    },

    save: function () {
        $(document).on('click', '#confirmBtn', function () {
            let frm = $('#fsnAddEditPreviewForm');
            let btn = $('#confirmBtn');
            let editBtn = $('#editBtn');
            let btnName = btn.html();
            let fsnListTable = $('#fsn-list-table');
            let modal = $("#addFsn");
            var formData = new FormData(frm[0]);

            $.ajax({
                type: 'POST',
                url: route('client.fsn.store'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                    editBtn.prop('disabled', true);
                },
                success: function (response) {
                    if (response.success) {
                        successToaster(response.message, 'Sent details successfully');
                        setTimeout(function () {
                            fsnListTable.DataTable().ajax.reload();
                            modal.modal("hide");
                        }, 1000);
                    }
                },
                error: function (err) {
                    var obj = jQuery.parseJSON(err.responseText);

                    /* if(obj.message == "The url must be a valid URL."){
                        //errorToaster("The url must not be a valid URL.", 'Invalid');
                        $("#previewSection").hide();
                        $("#addEditSection").show();
                    } */
                },
                complete: function () {
                    showButtonLoader(btn, btnName, false);
                    editBtn.prop('disabled', false);
                }
            });
        });
    },

    list: function () {
        pageLoader("loaderTb");
        let fsnListTable = $('#fsn-list-table');
        NioApp.DataTable(fsnListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'fsn',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(fsnListTable.DataTable().page.info().page) + 1;
                    d.search = fsnListTable.DataTable().search();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;

                },
            },
            "order": [0, "desc"],
            'createdRow': function (row, data, full) {
                $('.viewFsn', row).on('click', function () {
                    $.ajax({
                        method: "GET",
                        data: data,
                        url: route('client.fsn.show', data),
                        success: function (response) {
                            $('#viewFsn').html(response).modal('show');
                        },
                        error: function (response) {
                            handleError(response);
                        },
                    });
                });
            },
            "columnDefs":
                [
                    {
                        "data": "id",
                        "name": "id",
                        "targets": "id",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "title",
                        "name": "title",
                        "targets": "title",
                    },
                    {
                        "data": "udi_number",
                        "name": "udi_number",
                        "targets": "udi_number",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return `<a href="`+route('client.product.show', _full.product_id)+`">`+_full.udi_number+`</a>`;
                        }
                    },
                    {
                        "data": "created_at",
                        "name": "created_at",
                        "targets": "created_at",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return getLocalTime(_full.created_at);
                        }
                    },
                    {
                        "data": "product_name",
                        "name": "product_name",
                        "targets": "product_name",
                        'orderable': false,
                    },
                    {
                        "data": "attachment_type",
                        "name": "attachment_type",
                        "targets": "attachment_type",
                        'orderable': false,
                    },
                    {
                        "data": "id",
                        "targets": "actions",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, _meta) {
                            return `<div class="dropdown">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn btn-icon btn-trigger"
                                data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                <ul class="link-list-plain">
                                    <li><a class="viewFsn cursor-pointer" rel="${_full.id}"><em
                                                class="icon ni ni-eye"></em><span>View</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>`;
                        }
                    }
                ]
        });
    },

    selectFile: function () {
        $(document).on('change', '#uploadFile', function () {
            var fileName = $(this).val().replace(/^.*[\\\/]/, '');
            if(fileName != '') {
                $('.form-file-label').text(fileName);
            } else {
                $('.form-file-label').text('Choose File');
            }
      });
    }

};

$(function () {
    fsn.init();
});
