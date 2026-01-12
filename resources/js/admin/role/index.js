let roles = {
    init: function() {
        roles.list();
        roles.addAndEdit();
        roles.saveRole();
        roles.updateRole();
        roles.deleteRole();
        roles.changeStatus();
        roles.searchFilter();
        roles.resetFilter();
    },

    /* Start function roles list here */
    list: function(){
        pageLoader("loaderTb");
        let roleTable = $('#role-list-table');
        NioApp.DataTable(roleTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'role',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(roleTable.DataTable().page.info().page) + 1;
                    d.search = roleTable.DataTable().search();
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
            "order": [0, "desc"],
            'createdRow': function(row, data, full) {
                $('.edit-role', row).on('click', function() {
                    $.ajax({
                        method: "GET",
                        url: route('admin.role.edit', data),
                        success: function(response) {
                            $('#addEditRoleModal').html(response).modal('show');


                            $( "#addEditRoleModal .side-menu" ).each(function( menuId ) {
                                $(`#allCheck_${menuId} input[type="checkbox"]:checked`).each(function() {
                                    var roleIds = [];
                                    $(`#allCheck_${menuId} input[type="checkbox"]:not(:checked)`).each(function() {
                                        roleIds.push($(this).val());
                                    });

                                    if (roleIds.length > 0) {
                                        $(`#customCheckAll_${menuId}`).prop('checked', false);
                                        $(`#customCheckAll_${menuId}`).prop('indeterminate', true);
                                    } else {
                                        $(`#customCheckAll_${menuId}`).prop('indeterminate', false);
                                        $(`#customCheckAll_${menuId}`).prop('checked', true);
                                    }
                              });

                            });
                        },
                        error: function(response) {
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
                    "render": function (_data, _type, _full, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": "name",
                    "name": "name",
                    "targets": "name",
                    'orderable': false,
                },
                {
                    "data": "status",
                    "name": "status",
                    "targets": "status",
                    "className": "text-center w-250px",
                    'orderable': false,
                    "render": function (_data, _type, row, _meta) {
                            var status = 'Inactive';
                            var color = 'danger';
                        if (row.status == 'active') {
                            var status = 'Active';
                            var color = 'success';
                        }
                        return `<span class="badge badge-sm badge-dot has-bg bg-${color}">${status}</span>`;
                    },
                },
                {
                    "data": "id",
                    "targets": "actions",
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
                        if (canEdit) {
                            actions +=`<div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                            <ul class="link-list-plain">
                                <li><a class="edit-role cursor-pointer"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                <li><a class="changeStatus cursor-pointer" rel="${_full.id}" data-status="${_full.status}"><em class="icon ni ni-${icon}"></em><span>${text}</span></a></li>

                            </ul>
                        </div>`
                        }
                    actions +=`</div>`;

                    return actions;
                    }
                }
            ]
        });
    },
    /* End function roles list here */


    /* End function users list here */
    changeStatus: function () {
        /* activate and deactivate user starts here */
        $(document).on('click', '.changeStatus', function (e) {
            var status = $(this).attr("data-status");
            roleStatus = 1;
            if(status == 'active') {
                roleStatus = 0;
            }
            let id = $(this).attr('rel');
            if (status == 'active') {
                var status = 'inactive';
                var Text = 'You want to inactive this role ?'
            }
            else {
                var status = 'active';
                var Text = 'You want to activate role ?'

            }
            let url = route('admin.role.changeStatus');
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
                            status: roleStatus,
                        },
                        success: function (response) {
                            if (response.success) {
                                successToaster('Role '+response.message, 'Role Management');
                                setTimeout(function () {
                                    $('#role-list-table').DataTable().ajax.reload();
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

    /* Start function roles addAndEdit here */
    addAndEdit: function(){
        $(document).on('click', '.addEditRole', function () {
            let btn = $('#addRoleBtn');
            let btnName = btn.html();
            let url = route('admin.role.create');
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                },
                success: function (response) {
                    $('#addEditRoleModal').html(response).modal('show');
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
    /* End function addAndEdit here */

    /* Start function saveRole here */
    saveRole:function(){
        $(document).on('click', '#roleSubmitBtn', function(e) {
            e.preventDefault();
            let roleId = $('#user-id').val();
            let frm = $('#roleAddEditForm');
            let btn = $('#roleSubmitBtn');
            let cancelBtn = $('#roleCancelBtn');
            let btnName = btn.html();
            let url = (roleId) ? route('admin.role.update', roleId) : frm.attr('action');
            let modal = $("#addEditRoleModal");
            let method = frm.attr('method');
            let roleTable = $('#role-list-table');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                $.ajax({
                    type: method,
                    url: url,
                    data: frm.serialize(),
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled',true);
                    },
                    success: function(response) {
                        if (response.success) {
                            successToaster(response.message, 'Role Management');
                            setTimeout(function() {
                                roleTable.DataTable().ajax.reload();
                                modal.modal("hide");
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
    /* End function saveRole here */

    updateRole:function(){
        $(document).on('click', '#roleUpdateBtn', function(e) {
            e.preventDefault();
            let frm = $('#roleEditForm');
            let btn = $('#roleUpdateBtn');
            let cancelBtn = $('#roleCancelBtn');
            let btnName = btn.html();
            let url = frm.attr('action');
            let modal = $("#addEditRoleModal");
            let method = frm.attr('method');
            let roleTable = $('#role-list-table');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                $.ajax({
                    type: method,
                    url: url,
                    data: frm.serialize(),
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled',true);
                    },
                    success: function(response) {
                        if (response.success) {
                            successToaster(response.message, 'Role Management');
                            setTimeout(function() {
                                roleTable.DataTable().ajax.reload();
                                modal.modal("hide");
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

    /* Start function deleteRole here */
    deleteRole:function(){
        $(document).on('click', '.deleteRole', function(e) {
            let id = $(this).attr('rel');
            let url = route('admin.role.destroy',{id}) ;
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
                                    "Role's has been deleted.",
                                    "success"
                                )
                                setTimeout(function () {
                                    $('#role_table').DataTable().ajax.reload();
                                }, 1000);
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
    /* End function deleteRole here */

    searchFilter: function () {
        $('#searchFilter').on('click', function (e) {
            var searchStatus = $('#searchStatus').val();
            if(searchStatus){
                $('#role_filter_badge').removeClass('d-none');
            }
            $("#role-list-table").DataTable().draw(true);
        });
    },

    resetFilter: function () {
        $('#resetFilter').on('click', function (e) {
            $("#resetFormFilter").trigger("reset");
            $('#role_filter_badge').addClass('d-none');
            $("#searchStatus").val('').trigger('change');
            $("#role-list-table").DataTable().draw(true);
        });
    },

};

$(function() {
    roles.init();
});

// select checkbox
$(document).on('click', '.permission-menu', function(e) {
    let id = $(this).data('id');
    $(`#allCheck_${id} input:checkbox`).not(this).prop('checked', this.checked);
});

$(document).on('click', '.selectInput', function(e) {
    let menuId = $(this).attr('data-menuId');
    if ($(this).is(':checked')) {
        var roleIds = [];
        $(`#allCheck_${menuId} input[type="checkbox"]:not(:checked)`).each(function() {
            roleIds.push($(this).val());
        });
        if (roleIds.length > 0) {
            $(`#customCheckAll_${menuId}`).prop('checked', false);
            $(`#customCheckAll_${menuId}`).prop('indeterminate', true);
        } else {
            $(`#customCheckAll_${menuId}`).prop('indeterminate', false);
            $(`#customCheckAll_${menuId}`).prop('checked', true);
        }
    } else {
        var roleIds = [];
        $(`#allCheck_${menuId} input[type="checkbox"]:checked`).each(function() {
            roleIds.push($(this).val());
        });

        if (roleIds.length > 0) {
            $(`#customCheckAll_${menuId}`).prop('checked', false);
            $(`#customCheckAll_${menuId}`).prop('indeterminate', true);
        } else {
            $(`#customCheckAll_${menuId}`).prop('indeterminate', false);
            $(`#customCheckAll_${menuId}`).prop('checked', false);
        }
    }
});
