let clients = {
    init: function () {
        clients.list();
        clients.changeStatus();
        clients.changeCountry();
        clients.saveClient();
        clients.resetFilter();
        clients.searchFilter();
        clients.exportCsv();
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            var search = $('#client-list-table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';

            var searchByRecord = '?fromDate=' + fromDate + '&toDate=' + toDate + '&status=' + searchStatus +'&type=' + searchType + '&search=' + searchData;
            var url = route('admin.client.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

    saveClient: function () {
        $(document).on('click', '#clientSubmitBtn', function (e) {
            e.preventDefault();
            let clientId = $('#client-id').val();
            let frm = $('#clientAddEditForm');
            let btn = $('#clientSubmitBtn');
            let cancelBtn = $('#clientCancelBtn');
            let btnName = btn.html();
            let url = (clientId) ? route('admin.client.update', clientId) : frm.attr('action');
            let modal = $("#addClient");
            let method = frm.attr('method');
            let clientTable = $('#client-list-table');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                var formData = new FormData(frm[0]);

                if (formData.get('profile_image')) {
                    var file = imageBase64toFile(formData.get('profile_image'), 'client_image');
                    formData.delete('profile_image');
                    formData.append("profile_image", file); // remove base64 image content
                }
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
                            successToaster(response.message, 'Client Management');
                            setTimeout(function () {
                                window.location.href = route('admin.client.index');
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

    changeCountry: function () {
        $(document).on('change', '#countryId', function () {
            let countryId = $(this).val();
            cityContainer = $('#cityId');
            if (countryId) {
                setCitybyCountryId(countryId, cityContainer);
            }
        });
    },

    /* Start function clients list here */
    list: function () {
        pageLoader("loaderTb");
        let clientListTable = $('#client-list-table');
        NioApp.DataTable(clientListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'client',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(clientListTable.DataTable().page.info().page) + 1;
                    d.search = clientListTable.DataTable().search();
                    d.name = $("#searchName").val();
                    d.email = $("#searchEmail").val();
                    d.status = $("#searchStatus").val();
                    d.type = $("#searchType").val();
                    d.fromDate = $('#fromDate').val();
                    d.toDate = $('#toDate').val();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;

                },

            },

            "order": [0, "desc"],
            'createdRow': function (row, data, full) {
                $('.edit-client', row).on('click', function () {
                    $.ajax({
                        method: "GET",
                        url: route('admin.client.edit', data),
                        success: function (response) {
                            $('#addClient').html(response).modal('show');
                            $('.form-select').select2();
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
                            return `<div class="user-card">
                            <div class="user-avatar bg-transparent">
                            <img src="${_full.profile_image}" alt="user-img">
                            </div>
                            <div class="user-info">
                                <span class="lead-text">${_full.name.charAt(0).toUpperCase() + _full.name.slice(1)}</span>
                                <span>${_full.email}</span>
                            </div>
                        </div>`;
                        }
                    },
                    {
                        "data": "phone_number",
                        "phone_number": "phone_number",
                        "targets": "phone_number",
                        'orderable': false,
                        render: function (_data, _type, row, _meta) {
                            if (row.phone_code !== null && row.phone_code !== undefined) {
                               return '+' + row.phone_code + ' ' + row.phone_number;
                            } else {
                                return row.phone_number;
                            }
                        }
                    },
                    {
                        "data": "role",
                        "name": "role",
                        "targets": "role",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.role;
                        }
                    },
                    {
                        "data": "product_count",
                        "name": "product_count",
                        "targets": "product_count",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.product_count;
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
                        "data": "status",
                        "name": "status",
                        "targets": "status",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            var bgColor = "bg-danger";
                            if (_full.status == 'active') {
                                var bgColor = "bg-success";
                            }
                            var status = _full.status.charAt(0).toUpperCase() + _full.status.slice(1);
                            return `<span class="badge badge-sm badge-dot has-bg ${bgColor}">${status}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "targets": "actions",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, _meta) {
                            var text = 'Active';
                            var url = route('admin.client.edit',_full.id);
                            var icon = 'check-circle';
                            if (_full.status == 'active') {
                                var text = 'Inactive';
                                var icon = 'cross-circle';
                            }
                            var actions = `<div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>`
                            if (canView || canEdit) {
                            actions += `<div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                                <ul class="link-list-plain">`
                                if (canView) {
                                    actions +=`<li><a href="${route('admin.client.show', _full.id)}"><em class="icon ni ni-eye"></em><span>View</span></a></li>`
                                }
                                if (canEdit) {
                                    actions +=`<li><a href="${url}" class="edit-client cursor-pointer"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                    <li><a class="changeStatus cursor-pointer" rel="${_full.id}" data-status="${_full.status}"><em class="icon ni ni-${icon}"></em><span>${text}</span></a></li>`
                                }
                                actions +=`</ul>
                            </div>`
                            }
                            actions +=`</div>`;

                        return actions;
                        }
                    }
                ]
        });
    },

    changeStatus: function () {
        /* activate and deactivate client starts here */
        $(document).on('click', '.changeStatus', function (e) {
            var status = $(this).attr("data-status");
            let id = $(this).attr('rel');
            if (status == 'active') {
                var status = 'inactive';
                var Text = 'You want to inactive this client ?'
            }
            else {
                var status = 'active';
                var Text = 'You want to activate client ?'

            }
            let url = route('admin.client.changeStatus');
            Swal.fire({
                allowOutsideClick: false,
                title: 'Are you sure?',
                text: Text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, want  to !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id,
                            status: status,
                        },
                        success: function (response) {
                            if (response.success) {
                                successToaster(response.message, 'Client Management');
                                setTimeout(function () {
                                    $('#client-list-table').DataTable().ajax.reload();
                                }, 10);
                            }
                        },
                        error: function (err) {
                            handleError(err);
                        },
                        complete: function () {
                            $('#background_loader').addClass('d-none');
                        }
                    });
                }
                if (result.isDismissed) {
                    $(this).prop("checked", !$(this).prop("checked"));
                }
            });
        });
        /* activate and deactivate client ends here */

    },

    resetFilter: function () {
        $('#resetFilter').on('click', function (e) {
            $("#resetFormFilter").trigger("reset");
            $('#client_filter_badge').addClass('d-none');
            $("#searchStatus, #searchType").val('').trigger('change');
            $("#client-list-table").DataTable().draw(true);
        });
    },

    searchFilter: function () {
        $('#searchFilter').on('click', function (e) {
            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if (searchStatus || searchType || fromDate || toDate) {
                $('#client_filter_badge').removeClass('d-none');
            }
            $("#client-list-table").DataTable().draw(true);
        });
    },
};

$(function () {
    clients.init();

    window.setCitybyCountryId = function (countryId, cityContainer) {
        $.ajax({
            type: 'GET',
            url: route('admin.client.cities', countryId),
            success: function (response) {
                let cityList = response.data;
                if (cityList && cityList.length > 0) {
                    cityContainer.empty();
                    $.each(cityList, function (key, value) {
                        var newState = new Option(value.name, value.id, false, false);
                        cityContainer.append(newState);
                    });
                    cityContainer.trigger('change');
                } else {
                    var newState = new Option("Select City", "", false, false);
                    cityContainer.empty();
                    cityContainer.append(newState);
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
