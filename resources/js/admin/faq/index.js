let faqs = {
    init: function() {
        faqs.list();
        faqs.addAndEdit();
        faqs.saveFaq();
        faqs.deleteFaq();
    },

    /* Start function faq's list here */
    list: function(){
        pageLoader("loaderTb");
        let faqTable = $('#faq_table');
        NioApp.DataTable(faqTable, {
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "responsive": false,
            "ajax": {
                url: 'faq',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(faqTable.DataTable().page.info().page) + 1;
                    d.search = faqTable.DataTable().search();
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
                    "data": "question",
                    "name": "question",
                    "targets": "question",
                    "className": "w-400px text-break",
                    "render": function (data, _type, _full, _meta) {
                        let cleanText = data.replace(/<\/?[^>]+(>|$)/g, "");
                        if(cleanText.length > 20){
                            data = cleanText.slice(0, 20);
                            let readMore = ` <a class="readMoreFaq cursor-pointer" data-description="${cleanText}" data-heading="Question"><strong>Read More</strong></a>`;
                            return data + ' ... ' + readMore;
                        }
                        return data;
                    }
                },
                {
                    "data": "answer",
                    "name": "answer",
                    "targets": "answer",
                    "render": function (data, _type, _full, _meta) {
                        let cleanText = data.replace(/<\/?[^>]+(>|$)/g, "");
                        if(cleanText.length > 20){
                            data = cleanText.slice(0, 20);
                            let readMore = ` <a class="readMoreFaq cursor-pointer" data-description="${cleanText}" data-heading="Answer"><strong>Read More</strong></a>`;
                            return data + ' ... ' + readMore;
                        }
                        return data;
                    }
                },
                {
                    "data": "id",
                    "targets": "actions",
                    'orderable': false,
                    "render": function (_data, _type, full, _meta) {
                        return `<td class="nk-tb-col nk-tb-col-tools">
                            <div class="drodown">
                                <a class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
                                    <em class="icon ni ni-more-h"></em>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr p-0">
                                        <li>
                                            <a class="cursor-pointer addEditFaq" rel="${full.id}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                        </li>
                                        <li>
                                            <a class="cursor-pointer deleteFaq" rel="${full.id}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>`;
                    }
                }
            ]
        });
    },
    /* End function faq's list here */

    /* Start function faq's addAndEdit here */
    addAndEdit: function(){
        $(document).on('click', '.addEditFaq', function () {
            let id = $(this).attr('rel');
            let btn = $('#addFaqBtn');
            let btnName = btn.text();
            let url =  (id != '' && id != undefined) ? route('admin.faq.edit', {id}) : route('admin.faq.create');
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
                        $("#addEditFAQModal").html(response.data);
                        $("#addEditFAQModal").modal("show");
                        loadEditor('faq-description');
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

    /* Start function saveFaq here */
    saveFaq:function(){
        $(document).on('click', '#faqSubmitBtn', function(e) {
            e.preventDefault();
            let frm = $('#faqAddEditForm');
            let btn = $('#faqSubmitBtn');
            let cancelBtn = $('#faqCancelBtn');
            let btnName = btn.text();
            let url = frm.attr('action');
            let faqTable = $('#faq_table');

            if (frm.valid()) {
                showButtonLoader(btn, btnName, 'disabled');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: frm.serialize(),
                    beforeSend: function() {
                        showButtonLoader(btn, btnName, true);
                        cancelBtn.prop('disabled',true);
                    },
                    success: function(response) {
                        if (response.success) {
                            successToaster(response.message, 'CMS');
                            setTimeout(function() {
                                faqTable.DataTable().ajax.reload();
                                $("#addEditFAQModal").modal("hide");
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
    /* End function saveFaq here */

    /* Start function deleteFaq here */
    deleteFaq:function(){
        $(document).on('click', '.deleteFaq', function(e) {
            let id = $(this).attr('rel');
            let url = route('admin.faq.destroy',{id}) ;
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
                                    "Faq's has been deleted.",
                                    "success"
                                )
                                setTimeout(function () {
                                    $('#faq_table').DataTable().ajax.reload();
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
    }
    /* End function deleteFaq here */
};

$(function() {
    faqs.init();
});

/* for readmore faq answer */
$(document).on('click', '.readMoreFaq', function(e) {
    e.preventDefault();
    var data = $(this).data('description');
    $('#faqReadMoreData').html('').html(data);
    $('#heading').text($(this).data('heading'));
    $('#readMoreModal').modal('show');
});
