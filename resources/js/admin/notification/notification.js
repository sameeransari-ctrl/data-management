$(function () {
    var notificationTable = $('#notification-table');
    pageLoader("loaderTb");
    NioApp.DataTable(notificationTable, {
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "oLanguage": {
            "sEmptyTable": "No record found"
        },
        "ajax": {
            url: route('admin.notification.index'),
            type: 'GET',
            data: function (d) {
                d.size = d.length;
                d.sortColumn = d.columns[d.order[0]['column']]['name'];
                d.sortDirection = d.order[0]['dir'];
                d.page = parseInt(notificationTable.DataTable().page.info().page) + 1;
                d.search = notificationTable.DataTable().search();
                d.fromDate = $('#fromDate').val();
                d.toDate = $('#toDate').val();
            },
            dataSrc: function (d) {
                d.recordsTotal = d.meta.total;
                d.recordsFiltered = d.meta.total;

                return d.data;
            },
            error: function (xhr, error, code) {
                handleError(xhr);
            }
        },
        "stateSave": false,
        "order": [2, "desc"],
        "columnDefs": [{
            "data": 'id',
            "name": "id",
            "targets": 'id',
            'orderable': false,
            "render": function (data, type, full, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        {
            "data": 'message',
            "name": "message",
            "targets": 'message',
            'orderable': false,
            'defaultContent': '--',
            "render": function (data, _type, _full, _meta) {
                let cleanText = data;
                if (cleanText.length > 90) {
                    data = cleanText.slice(0, 90);
                    let readMore = `<button class="readMoreNotification cursor-pointer" data-description="${cleanText}" data-heading="Notification"><strong>Read More</strong></button>`;
                    return `<a href="${_full.redirectTo}" class="notification-link cursor-pointer">`+data+`</a>` + ' ... ' + readMore;
                }
                return `<a href="${_full.redirectTo}" class="notification-link cursor-pointer">`+data+`</a>`;
            },
        },
        {
            "data": 'created_at',
            "name": "created_at",
            "targets": 'datetime',
            'defaultContent': '--',
            "render": function (data, type, full, meta) {
                return getLocalTime(data, 'DD MMM YYYY, hh:mm A')
            },
        },

        ]
    });

    $(document).on('click', '.readMoreNotification', function(e) {
        e.preventDefault();
        var data = $(this).data('description');
        $('#notificationReadMoreData').html('').html(data);
        $('#heading').text($(this).data('heading'));
        $('#readMoreModal').modal('show');
    });

});
