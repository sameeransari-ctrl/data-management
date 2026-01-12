let products = {
    init: function() {
        products.list();
        products.resetFilter();
        products.searchFilter();
        products.exportCsv();
    },

    /* Start function products list here */
    list: function(){
        pageLoader("loaderTb");
        let productTable = $('#product_table');
        NioApp.DataTable(productTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'product',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['product_name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(productTable.DataTable().page.info().page) + 1;
                    d.search = productTable.DataTable().search();
                    d.classId = $("#class_id").val();
                    d.verificationBy = $("#verification_by").val();
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
                    "render": function (_data, _type, _full, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": "product_image",
                    "name": "product_image",
                    "targets": "product_image",
                    "render": function(data, type, full, meta) {
                        return `<div class="user-card">
                            <div class="user-avatar sq md bg-transparent">
                                <img src="${full.product_image}" class="img-fluid" alt="product-img">
                            </div>
                        </div>`;
                    }
                },
                {
                    "data": "udi_number",
                    "name": "udi_number",
                    "targets": "udi_number",
                    "render": function(data, type, full, meta) {
                        return `<a href="`+route('admin.product.show', full.id)+`">${full.udi_number}</a>`;
                    }
                },
                {
                    "data": "product_name",
                    "name": "product_name",
                    "targets": "product_name",
                },
                {
                    "data": "client_name",
                    "name": "client_name",
                    "targets": "client_name",
                },
                {
                    "data": "class_name",
                    "name": "class_name",
                    "targets": "class_name",
                },
                {
                    "data": "verification_by",
                    "name": "verification_by",
                    "targets": "verification_by",
                },
                {
                    "data": "total_scan_count",
                    "name": "total_scan_count",
                    "targets": "total_scan_count",
                    "className": "text-center",
                },
                {
                    "data": "id",
                    "targets": "actions",
                    'orderable': false,
                    "render": function (_data, _type, full, _meta) {
                        var actions = `<td class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
                                    <em class="icon ni ni-more-h"></em>
                                </a>`
                                if (canView || canEdit) {
                                actions += `<div class="dropdown-menu dropdown-menu-xs dropdown-menu-end">
                                    <ul class="link-list-plain">`
                                    if (canView) {
                                        actions += `<li>
                                            <a href="`+route('admin.product.show', full.id)+`" class="viewProduct" rel="${full.id}">
                                                <em class="icon ni ni-eye"></em><span>View</span>
                                            </a>
                                        </li>`
                                    }
                                    if (canEdit) {
                                        actions +=`<li>
                                            <a href="`+route('admin.product.edit', full.id)+`" class="editProduct" rel="${full.id}">
                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                            </a>
                                        </li>`
                                    }
                                    actions +=`</ul>
                                </div>`
                            }
                        actions +=`</div>
                        </td>`;

                    return actions;

                  },
               }
            ]
        });
    },
    /* End function products list here */

    resetFilter: function () {
        $('#resetFilter').on('click', function (e) {
            $("#resetFormFilter").trigger("reset");
            $('#user_filter_badge').addClass('d-none');
            $("#class_id, #verification_by").val('').trigger('change');
            $("#product_table").DataTable().draw(true);
        });
    },

    searchFilter: function () {
        $('#searchFilter').on('click', function (e) {
            var classId = $('#class_id').val();
            var verificationBy = $('#verification_by').val();
            if(classId || verificationBy){
                $('#user_filter_badge').removeClass('d-none');
            }

            $("#product_table").DataTable().draw(true);
        });
    },

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {
            var classId = $('#class_id').val();
            var verificationBy = $('#verification_by').val();
            var search = $('#product_table_filter input[type="search"]').val();
            var searchData = (search != null) ? search : '';

            var searchByRecord = '?classId=' + classId + '&verificationBy=' + verificationBy + '&search=' + searchData + '&export=export';
            var url = route('admin.product.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },

};

$(function() {
    products.init();

    $('#import-product').on('click', function(){
        let btn = $('#import-product');
        let btnName = btn.html();
        $.ajax({
            type: 'GET',
            url: route('admin.product.import-form'),
            beforeSend: function() {
                showButtonLoader(btn, btnName, true);
            },
            success: function(response) {
                $('#import-product-div .modal-body').html(response);
                $('#import-product-div').modal('show');
            },
            error: function(err) {
                handleError(err);
            },
            complete: function() {
                showButtonLoader(btn, btnName, false);
            }
        });
    });

    $(document).on('click', '#importProductBtn', function(){
        let btn = $('#importProductBtn');
        let btnName = btn.html();
        let form = $('#importProductForm')
        var formData = new FormData(form[0]);
        if (form.valid()) {
            showButtonLoader(btn, btnName, 'disabled');
            $.ajax({
                type: 'POST',
                url: route('admin.product.import'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                },
                success: function(response) {

                    if (response.error == 400) {
                        $('#importProduct, .productError').removeClass('d-none');
                        $("#successVal").text(response.stat.successfull);
                        $("#unsuccessVal").text(response.stat.unsuccessfull);
                        $("#errorVal").text(response.stat.errors);
                        $("#importProduct").attr('href',response.stat.fileName);
                    }

                    if (response.success == 200) {
                        $('#import-product-div').modal('hide');
                        successToaster(response.message);
                        setTimeout(function () {
                          $('#product_table').DataTable().ajax.reload();
                          }, 1000);
                    }

                },
                error: function(err) {
                    $('#import-product-div').modal('hide');
                    handleError(err);
                },
                complete: function() {
                    showButtonLoader(btn, btnName, false);
                }
            });
        }
    });
});
