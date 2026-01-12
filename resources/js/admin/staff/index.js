let staffs = {
    init: function() {
        staffs.list();
        staffs.changeStatus();
        staffs.addAndEdit();
        staffs.saveStaff();
        staffs.resetFilter();
        staffs.searchFilter();
        staffs.exportCsv();

    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {

            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            var search = $('#staff_table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';

            var searchByRecord = '?status=' + searchStatus +'&type=' + searchType + '&search=' + searchData + '&export=export';
            var url = route('admin.staff.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },


    /* Start function staff's list here */
    list: function(){
        pageLoader("loaderTb");
        let staffTable = $('#staff_table');
        NioApp.DataTable(staffTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'staff',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(staffTable.DataTable().page.info().page) + 1;
                    d.search = staffTable.DataTable().search();
                    d.type = $("#searchType").val();
                    d.status = $("#searchStatus").val();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;
                },
                error: function (xhr, _error, _code) {
                    handleError(xhr);
                }
            },
            "stateSave": false,
            "order": [0, "desc"],
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
                    "name": "phone_number",
                    "targets": "phone_number",
                    render: function (_data, _type, row, _meta) {
                        if (row.phone_code !== null && row.phone_code !== undefined) {
                           return '+' + row.phone_code + ' ' + row.phone_number;
                        } else {
                            return row.phone_number;
                        }
                    }
                },
                {
                    "data": "user_type",
                    "name": "user_type",
                    "targets": "user_type",
                    'orderable': false,
                    "render": function (_data, _type, _full, meta) {
                        return _full.user_type.charAt(0).toUpperCase() + _full.user_type.slice(1);
                    }
                },

                {
                    "data": "status",
                    "name": "status",
                    "targets": "status",
                    "className": "text-center",
                    'orderable': false,
                    render: function (_data, _type, row, _meta) {
                        if (row.status == 'active') {
                            var activeStatus = 'Active';
                            return `<td align="center"><span class="badge badge-sm badge-dot has-bg bg-success">${activeStatus}</span></td>`;
                        }
                        else {
                            var activeStatus = 'Inactive';
                            return `<td align="center"><span class="badge badge-sm badge-dot has-bg bg-danger">${activeStatus}</span></td>`;
                        }
                    },
                },
                {
                    "data": "id",
                    "targets": "actions",
                    'orderable': false,
                    "render": function (_data, _type,_full, _meta) {
                        var text = 'Active';
                            var icon = 'check-circle';
                            if (_full.status == 'active') {
                                var text = 'Inactive';
                                var icon = 'cross-circle';
                            }

                            var actions = `<td class="text-center">
                                           <div class="dropdown">
                                           <a class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>`
                            if (canEdit) {
                                actions +=`<div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                                           <ul class="link-list-plain">
                                           <li><a class="addEditStaff cursor-pointer" rel="${_full.id}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                           <li><a class="changeStatus cursor-pointer" rel="${_full.id}" data-status="${_full.status}"><em class="icon ni ni-${icon}"></em><span>${text}</span></a></li>
                                           </ul>
                                           </div>`
                                    }
                                actions +=`</div>
                                           </td>`;

                            return actions;
                    }
                }
            ]
        });
    },
    /* End function staff's list here */

    changeStatus: function () {
        /* activate and deactivate staff starts here */
        $(document).on('click', '.changeStatus', function (e) {
            var status = $(this).attr("data-status");
            let id = $(this).attr('rel');
            if (status == 'active') {
                var status = 'inactive';
                var Text = 'You want to inactive this staff ?'
            }
            else {
                var status = 'active';
                var Text = 'You want to activate staff ?'

            }
            let url = route('admin.staff.changeStatus');
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
                                successToaster('Staff '+response.message, 'Staff Management');
                                setTimeout(function () {
                                    $('#staff_table').DataTable().ajax.reload();
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
        /* activate and deactivate staff ends here */

    },

    /* Start function staff's addAndEdit here */
    addAndEdit: function(){
        $(document).on('click', '.addEditStaff', function () {
            let id = $(this).attr('rel');
            let btn = $('#addStaffBtn');
            let btnName = btn.html();
            let url =  (id != '' && id != undefined) ? route('admin.staff.edit', {id}) : route('admin.staff.create');
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function() {
                    if(id == '' || id == undefined){
                        showButtonLoader(btn, btnName, true);
                    }else{
                        $('#background_loader').removeClass('d-none');
                    }
                },
                success: function(response) {
                    if(response.success){
                        $("#addEditStaffModal").html(response.data);
                        $("#addEditStaffModal").modal("show");
                        //password eye show/hide js
                        NioApp.PassSwitch();
                        //dropdown select js
                        $(".js-select2").select2();
                        $("#countries").select2({
                            templateResult: function(item) {
                            return format(item, false);
                            },
                            templateSelection: function(item) {
                            return format(item, true);
                            }
                        });
                    }
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    if(id == '' || id == undefined){
                        showButtonLoader(btn, btnName, false);
                    }else{
                        $('#background_loader').addClass('d-none');
                    }
                }
            });
        });
    },
    /* End function addAndEdit here */

    /* Start function saveStaff here */
    saveStaff:function(){
        $(document).on('click', '#staffSubmitBtn', function(e) {
            e.preventDefault();
            let frm = $('#staffAddEditForm');
            let btn = $('#staffSubmitBtn');
            let cancelBtn = $('#staffCancelBtn');
            let btnName = btn.text();
            let url = frm.attr('action');
            let method = frm.attr('method');
            let staffTable = $('#staff_table');

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
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled',true);
                    },
                    success: function(response) {
                        if (response.success) {
                            successToaster(response.message, 'Staff Managemant');
                            setTimeout(function() {
                                staffTable.DataTable().ajax.reload();
                                $("#addEditStaffModal").modal("hide");
                            }, 1000);
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
        });
    },
    /* End function saveStaff here */

    resetFilter: function () {
        /* reset filter starts here */

        $('#resetFilter').on('click', function (e) {
            $("#resetFormFilter").trigger("reset");
            $('#staff_filter_badge').addClass('d-none');
            $("#searchStatus, #searchType").val('').trigger('change');
            $("#staff_table").DataTable().draw(true);
        });

        /* reset filter ends here */
    },

    searchFilter: function () {
        /*  filter starts here */
        $('#searchFilter').on('click', function (e) {
            var searchStatus = $('#searchStatus').val();
            var searchType = $('#searchType').val();
            if(searchStatus || searchType){
                $('#staff_filter_badge').removeClass('d-none');
            }
            $("#staff_table").DataTable().draw(true);
        });

        /*  filter ends here */
    },

};

$(function() {
    staffs.init();

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

