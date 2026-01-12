let users = {
    init: function () {
        users.list();
    },

    list: function () {
        pageLoader("loaderTb");
        let productListTable = $('#scannedProduct-list-table');
        var id = $(".user-id").attr("data-user-id");
        NioApp.DataTable(productListTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: route('client.user.show', id),
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
                        "data": "product",
                        "name": "product_name",
                        "targets": "image",
                        "render": function (_data, _type, _full, meta) {
                            return `<div class="user-card">
                            <div class="user-avatar bg-transparent md sq">
                            <img src="${_full.product.image_url}" class="img-fluid w-100 h-100" alt="products1">
                        </div>
                        </div>`;
                        }
                    },

                    {
                        "data": "product",
                        "name": "udi_number",
                        "targets": "udi_number",
                        "render": function (_data, _type, _full, meta) {
                            return _full.product.udi_number;
                        }

                    },
                    {
                        "data": "product",
                        "name": "product_name",
                        "targets": "product_name",
                        "render": function (_data, _type, _full, meta) {
                            return _full.product.product_name;
                        }
                    },
                    {
                        "data": "product",
                        "name": "product_name",
                        "targets": "date_time",
                        "render": function (_data, _type, _full, meta) {
                            return getLocalTime(_full.created_at);
                        }
                    },
                    {
                        "data": "countryName",
                        "name": "countryName",
                        "targets": "country",
                        'orderable': false,
                    },
                    {
                        "data": "cityName",
                        "name": "cityName",
                        "targets": "city",
                        'orderable': false,
                    },
                ]
        });
    },
};

$(function () {
    users.init();
});
