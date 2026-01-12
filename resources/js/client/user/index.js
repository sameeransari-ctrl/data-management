let users = {
    init: function () {
        users.list();
        users.exportCsv();
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var search = $('#user-list-table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';
            var searchByRecord = '?search=' + searchData + '&export=export';
            var url = route('client.user.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

    list: function () {
        pageLoader("loaderTb");
        let userListTable = $('#user-list-table');
        NioApp.DataTable(userListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'user',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(userListTable.DataTable().page.info().page) + 1;
                    d.search = userListTable.DataTable().search();
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;
                },
            },

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
                                <img src="${_full.profile_image}" alt="a-sm">
                            </div>
                            <div class="user-info">
                                <span class="lead-text">${_full.name}</span></span>
                                <span>${_full.email}</span>
                            </div>
                        </div>`;
                        }
                    },

                    {
                        "data": "phone_number",
                        "name": "phone_number",
                        "targets": "phone_number",
                        "render": function (_data, _type, row, meta) {
                            if (row.phone_code !== null && row.phone_code !== undefined) {
                                return '+' + row.phone_code + ' ' + row.phone_number;
                             } else {
                                 return row.phone_number;
                             }
                        }

                    },
                    {
                        "data": "address",
                        "name": "address",
                        "targets": "address",
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
                        "data": "id",
                        "targets": "actions",
                        "className": "text-center",
                        'orderable': false,
                        "render": function (_data, _type, _full, _meta) {
                            return `<div class="dropdown">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn btn-icon btn-trigger"
                                data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                            <div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                                <ul class="link-list-plain">
                                    <li><a href="${route('client.user.show', _full.id)}"><em
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
};

$(function () {
    users.init();
});
