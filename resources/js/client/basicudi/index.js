let udi = {
    init: function () {
        udi.addEditModal();
        udi.save();
        udi.list();
        udi.exportCsv();
        udi.basicUdiModel();
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var search = $('#udi-list-table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';

            var searchByRecord = '?search=' + searchData + '&export=export';
            var url = route('client.basicudi.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

    addEditModal: function () {
        $(document).on('click', '.addEditUdi', function () {
            let btn = $('#addUdiBtn');
            let btnName = btn.html();
            let url = route('client.basicudi.create');
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                },
                success: function (response) {
                    $('#addUdi').html(response).modal('show');
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

    save: function () {
        $(document).on('click', '#udiSubmitBtn', function (e) {
            e.preventDefault();
            let frm = $('#udiAddEditForm');
            let btn = $('#udiSubmitBtn');
            let cancelBtn = $('#udiCancelBtn');
            let btnName = btn.html();
            let url = frm.attr('action');
            let modal = $("#addUdi");
            let method = frm.attr('method');
            let udiTable = $('#udi-list-table');

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
                    beforeSend: function () {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success) {
                            successToaster(response.message, 'Basic UDI');
                            setTimeout(function () {
                                udiTable.DataTable().ajax.reload();
                                modal.modal("hide");
                            }, 1000);
                        }
                    },
                    error: function (err) {
                        handleError(err);
                    },
                    complete: function () {
                        showButtonLoader(btn, btnName, false);
                        cancelBtn.prop('disabled', false);
                    }
                });
            }
        });
    },

    list: function () {
        pageLoader("loaderTb");
        let userListTable = $('#udi-list-table');
        NioApp.DataTable(userListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'basicudi',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(userListTable.DataTable().page.info().page) + 1;
                    d.search = userListTable.DataTable().search();
                    d.name = $("#searchName").val();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;

                },

            },

            "order": [0, "desc"],
            'createdRow': function (row, data, full) {
                $('.edit-udi', row).on('click', function () {
                    $.ajax({
                        method: "GET",
                        url: route('client.basicudi.edit', data),
                        success: function (response) {
                            $('#addUdi').html(response).modal('show');
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
                        "data": "name",
                        "name": "name",
                        "targets": "name",
                        "render": function (_data, _type, _full, meta) {
                            return _full.name;
                        }
                    },
                    {
                        "data": "id",
                        "targets": "actions",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, _meta) {
                            return `<div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                             <div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                                <ul class="link-list-plain">
                               <li><a class="edit-udi cursor-pointer"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                           </ul>
                            </div></div>`;
                        }
                    },

                ]
        });
    },

    basicUdiModel: function () {
        $('#import-basic-udi').on('click', function(){
            let btn = $('#import-basic-udi');
            let btnName = btn.html();
            $.ajax({
                type: 'GET',
                url: route('client.basicudi.import-form'),
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                },
                success: function(response) {
                    $('#import-basic-udi-div .modal-body').html(response);
                    $('#import-basic-udi-div').modal('show');
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    showButtonLoader(btn, btnName, false);
                }
            });
        });
    }

};

$(function () {
    udi.init();

    $(document).on('click', '#importBasicUdiBtn', function(){
        let btn = $('#importBasicUdiBtn');
        let btnName = btn.html();
        let form = $('#importBasicUdiForm')
        var formData = new FormData(form[0]);
        if (form.valid()) {
            showButtonLoader(btn, btnName, 'disabled');
            $.ajax({
                type: 'POST',
                url: route('client.basicudi.import'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                },
                success: function(response) {

                    if (response.error == 400) {
                        $('#importBasicUdi, .basicUdiError').removeClass('d-none');
                        $("#successVal").text(response.stat.successfull);
                        $("#unsuccessVal").text(response.stat.unsuccessfull);
                        $("#errorVal").text(response.stat.errors);
                        $("#importBasicUdi").attr('href',response.stat.fileName);
                        setTimeout(function () {
                            $('#udi-list-table').DataTable().ajax.reload();
                        }, 1000);
                    }

                    if (response.success == 200) {
                        $('#import-basic-udi-div').modal('hide');
                        successToaster(response.message);
                        setTimeout(function () {
                          $('#udi-list-table').DataTable().ajax.reload();
                          }, 1000);
                    }

                },
                error: function(err) {
                    $('#import-basic-udi-div').modal('hide');
                    handleError(err);
                },
                complete: function() {
                    showButtonLoader(btn, btnName, false);
                }
            });
        }
    });

});
