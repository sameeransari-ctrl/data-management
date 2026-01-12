let users = {
    init: function () {
        users.list();
        users.changeStatus();
        users.addEditModal();
        users.changeCountry();
        users.saveUser();
        users.resetFilter();
        users.searchFilter();
        users.exportCsv();
        users.dateRange();
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            var search = $('#user-list-table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';

            var searchByRecord = '?fromDate=' + fromDate + '&toDate=' + toDate + '&status=' + searchStatus + '&type=' + searchType + '&search=' + searchData + '&export=export';
            var url = route('admin.user.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

    addEditModal: function () {
        $(document).on('click', '.addEditUser', function () {
            $('#timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
            let btn = $('#addUserBtn');
            let btnName = btn.html();
            let url = route('admin.user.create');
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                },
                success: function (response) {
                    $('#addUser').html(response).modal('show');
                    $(".js-select2").select2();
                    $("#countries").select2({
                        templateResult: function(item) {
                        return format(item, false);
                        },
                        templateSelection: function(item) {
                        return format(item, true);
                        }
                    });
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



    saveUser: function () {
        $(document).on('click', '#userSubmitBtn', function (e) {
            e.preventDefault();
            let userId = $('#user-id').val();
            $('#timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
            let frm = $('#userAddEditForm');
            let btn = $('#userSubmitBtn');
            let cancelBtn = $('#userCancelBtn');
            let btnName = btn.html();
            let url = (userId) ? route('admin.user.update', userId) : frm.attr('action');
            let modal = $("#addUser");
            let method = frm.attr('method');
            let userTable = $('#user-list-table');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                var formData = new FormData(frm[0]);

                if (formData.get('profile_image')) {
                    var file = imageBase64toFile(formData.get('profile_image'), 'user_image');
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
                            successToaster(response.message, 'User Management');
                            setTimeout(function () {
                                userTable.DataTable().ajax.reload();
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

    changeCountry: function () {
        $(document).on('change', '#countryId', function () {
            let countryId = $(this).val();
            cityContainer = $('#cityId');
            if (countryId) {
                setCitybyCountryId(countryId, cityContainer);
            }
        });
    },

    /* Start function users list here */
    list: function () {
        pageLoader("loaderTb");
        let userListTable = $('#user-list-table');
        NioApp.DataTable(userListTable, {
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "bDestroy": true,
            "ajax": {
                url: 'user',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(userListTable.DataTable().page.info().page) + 1;
                    d.search = userListTable.DataTable().search();
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
                $('.edit-user', row).on('click', function () {
                    $.ajax({
                        method: "GET",
                        url: route('admin.user.edit', data),
                        success: function (response) {
                            $('#addUser').html(response).modal('show');
                            $('.form-select').select2();
                            $("#countries").select2({
                                templateResult: function(item) {
                                return format(item, false);
                                },
                                templateSelection: function(item) {
                                return format(item, true);
                                }
                            });
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
                                <span class="lead-text">${_full.name}</span>
                                <span>${_full.email}</span>
                            </div>
                        </div>`;
                        }
                    },

                    {
                        "data": "created_at",
                        "name": "created_at",
                        "targets": "created_at",
                        "render": function (_data, _type, _full, meta) {
                            return getLocalTime(_full.created_at);
                        }

                    },
                    {
                        "data": "address",
                        "name": "address",
                        "targets": "address",
                        "className": "w-300px white_spaceWrap",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.address;
                        }
                    },
                    {
                        "data": "country",
                        "name": "country",
                        "targets": "country",
                        'orderable': false,
                    },
                    {
                        "data": "city",
                        "name": "city",
                        "targets": "city",
                        'orderable': false,
                    },
                    {
                        "data": "user_type",
                        "name": "user_type",
                        "targets": "user_type",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.user_role_type.charAt(0).toUpperCase() + _full.user_role_type.slice(1);
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
                                    actions += `<li><a href="${route('admin.user.show', _full.id)}"><em class="icon ni ni-eye"></em><span>View</span></a></li>`
                                }
                                if (canEdit) {
                                    actions += `<li><a class="edit-user cursor-pointer"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                    <li><a class="changeStatus cursor-pointer" rel="${_full.id}" data-status="${_full.status}"><em class="icon ni ni-${icon}"></em><span>${text}</span></a></li>`
                                }
                                actions += `</ul>
                            </div>`
                            }
                            actions += `</div>`;

                            return actions;
                        }
                    }
                ]
        });
    },
    /* End function users list here */
    changeStatus: function () {
        /* activate and deactivate user starts here */
        $(document).on('click', '.changeStatus', function (e) {
            var status = $(this).attr("data-status");
            let id = $(this).attr('rel');
            if (status == 'active') {
                var status = 'inactive';
                var Text = 'You want to inactive this user ?'
            }
            else {
                var status = 'active';
                var Text = 'You want to activate user ?'

            }
            let url = route('admin.user.changeStatus');
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
                                successToaster('User ' + response.message, 'User Management');
                                setTimeout(function () {
                                    $('#user-list-table').DataTable().ajax.reload();
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
        /* activate and deactivate user ends here */

    },

    resetFilter: function () {
        $('#resetFilter').on('click', function (e) {
            $("#resetFormFilter").trigger("reset");
            $('#user_filter_badge').addClass('d-none');
            $("#searchStatus, #searchType").val('').trigger('change');
            $("#user-list-table").DataTable().draw(true);
        });
    },

    searchFilter: function () {
        $('#searchFilter').on('click', function (e) {
            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if (searchStatus || searchType || fromDate || toDate) {
                $('#user_filter_badge').removeClass('d-none');
            }

            $("#user-list-table").DataTable().draw(true);
        });
    },

    //for date not greater and less than from and end date
    dateRange: function () {
        $("#fromDate").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        }).on('changeDate', function (selected) {
            if (selected.date != undefined) {
                var startDate = new Date(selected.date.valueOf());
            }
            $('#toDate').datepicker('setStartDate', startDate);
        }).on('clearDate', function (selected) {
            $('#toDate').datepicker('setStartDate', null);
        });

        $("#toDate").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
        }).on('changeDate', function (selected) {
            if (selected.date != undefined) {
                var endDate = new Date(selected.date.valueOf());
            }
            $('#fromDate').datepicker('setEndDate', endDate);
        }).on('clearDate', function (selected) {
            $('#fromDate').datepicker('setEndDate', null);
        });
    }
};

$(function () {
    users.init();



    window.setCitybyCountryId = function (countryId, cityContainer) {
        $.ajax({
            type: 'GET',
            url: route('admin.user.cities', countryId),
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
    },

    window.format = function (item) {
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
