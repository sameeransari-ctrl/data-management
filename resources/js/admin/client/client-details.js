let clients = {
    init: function () {
        clients.list();
    },

    list: function () {
        pageLoader("loaderTb");
        let productListTable = $('#fsn-list-table');
        var id = $(".client-id").attr("data-client-id");
        NioApp.DataTable(productListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: route('admin.client.show', id),
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(productListTable.DataTable().page.info().page) + 1;
                    d.search = productListTable.DataTable().search();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;

                },

            },

            "order": [0, "desc"],
            'createdRow': function (row, data, full) {
                $('.fsn-show', row).on('click', function () {
                    $.ajax({
                        method: "GET",
                        data : data,
                        url: 'fsn-view',
                        success: function (response) {
                            $('#fsn-show').html(response).modal('show');
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
                        "render": function (_data, _type, _full, meta) {
                            return _full.title;
                        }
                    },
                    {
                        "data": "udi_number",
                        "name": "udi_number",
                        "targets": "udi_number",
                        "render": function (_data, _type, _full, meta) {
                            return `<a>`+_full.udi_number+`</a>`;
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
                        "data": "product_name",
                        "name": "product_name",
                        "targets": "product_name",
                        "render": function (_data, _type, _full, meta) {
                            return _full.product_name;
                        }
                    },
                    {
                        "data": "attachment_type",
                        "name": "attachment_type",
                        "targets": "attachment_type",
                        "render": function (_data, _type, _full, meta) {
                            return _full.attachment_type;
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
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                <ul class="link-list-plain">
                                    <li><a class="fsn-show cursor-pointer"><em class="icon ni ni-eye cursor-pointer"></em><span class="cursor-pointer">View</span></a></li>
                                </ul>
                            </div>
                        </div>`;
                        }
                    }
                ]
        });
    },
};

$(function () {
    clients.init();
});
