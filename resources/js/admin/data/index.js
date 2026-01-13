window.companyWebsitesBulk = [];
let datas = {
    init: function () {
        datas.list();
        datas.searchFilter();
        datas.resetFilter();
        datas.changeStatus();
        datas.exportCsv();
        datas.initDesignationSelect();
        datas.initCompaniesDomainSelect();
        datas.dataImportModel();
    },
    

    exportCsv: function () {
        $(document).on('click', '.exportCsv', function () {

            var searchStatus = $('#searchStatus').val();
            var search = $('#data-list-table_filter input[type="search"]').val();
            let designation = $('#designation-title').val() || [];
            let companiesDomain = $('#companies-domain').val() || [];

            // BULK INPUT
            let bulkInput = $('#companies-bulk-input').val();
            let bulkArray = [];
            if (bulkInput) {
                bulkArray = bulkInput
                    .split(',')
                    .map(v => v.trim())
                    .filter(v => v.length > 0);
            }

            let designationQuery = '';
            designation.forEach(val => {
                designationQuery += '&designation[]=' + encodeURIComponent(val);
            });

            let companiesDomainQuery = '';
            companiesDomain.forEach(val => {
                companiesDomainQuery += '&company_websites[]=' + encodeURIComponent(val);
            });

            let bulkQuery = '';
            bulkArray.forEach(val => {
                bulkQuery += '&company_websites_bulk[]=' + encodeURIComponent(val);
            });

            var searchByRecord =
                '?status=' + searchStatus +
                '&search=' + (search ?? '') +
                designationQuery +
                companiesDomainQuery +
                bulkQuery +
                '&export=export';

            var url = route('admin.data.export-csv');
            $(this).attr("href", url + searchByRecord);
        });
    },



    /* Start function datas list here */
    list: function () {
        pageLoader("loaderTb");
        let dataListTable = $('#data-list-table');
        NioApp.DataTable(dataListTable, {
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "bDestroy": true,
            "ajax": {
                url: 'data',
                type: 'GET',
                data: function (d) {
                    d.size = d.length;
                    d.sortColumn = d.columns[d.order[0]['column']]['company_name'];
                    d.sortDirection = d.order[0]['dir'];
                    d.page = parseInt(dataListTable.DataTable().page.info().page) + 1;
                    d.search = dataListTable.DataTable().search();
                    d.name = $("#searchName").val();
                    // d.email = $("#searchEmail").val();
                    d.status = $("#searchStatus").val();
                    // d.type = $("#searchType").val();
                    d.fromDate = $('#fromDate').val();
                    d.toDate = $('#toDate').val();
                    d.designation = $('.designation-title').val() || [];
                    d.company_websites = $('.companies-domain').val() || [];
                    d.company_websites_bulk = window.companyWebsitesBulk || [];
                },
                dataSrc: function (d) {
                    d.recordsTotal = d.meta.total;
                    d.recordsFiltered = d.meta.total;
                    return d.data;

                },

            },

            "order": [0, "desc"],
            'createdRow': function (row, data, full) {
                // $('.edit-user', row).on('click', function () {
                //     $.ajax({
                //         method: "GET",
                //         url: route('admin.data.edit', data),
                //         success: function (response) {
                //             $('#addUser').html(response).modal('show');
                //             $('.form-select').select2();
                //             $("#countries").select2({
                //                 templateResult: function(item) {
                //                 return format(item, false);
                //                 },
                //                 templateSelection: function(item) {
                //                 return format(item, true);
                //                 }
                //             });
                //         },
                //         error: function (response) {
                //             handleError(response);
                //         },
                //     });
                // });
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
                        "data": "company_name",
                        "name": "company_name",
                        "targets": "company_name",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_name;
                        }
                    },
                    {
                        "data": "title",
                        "name": "title",
                        "targets": "title",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.title;
                        }
                    },
                    {
                        "data": "company_website",
                        "name": "company_website",
                        "targets": "company_website",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_website;
                        }
                    },
                    {
                        "data": "company_industries",
                        "name": "company_industries",
                        "targets": "company_industries",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_industries;
                        }
                    },
                    {
                        "data": "num_of_employees",
                        "name": "num_of_employees",
                        "targets": "num_of_employees",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.num_of_employees;
                        }
                    },
                    {
                        "data": "company_size",
                        "name": "company_size",
                        "targets": "company_size",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_size;
                        }
                    },
                    {
                        "data": "company_address",
                        "name": "company_address",
                        "targets": "company_address",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_address;
                        }
                    },
                    {
                        "data": "company_revenue_range",
                        "name": "company_revenue_range",
                        "targets": "company_revenue_range",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_revenue_range;
                        }
                    },
                    {
                        "data": "company_linkedin_url",
                        "name": "company_linkedin_url",
                        "targets": "company_linkedin_url",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_linkedin_url;
                        }
                    },
                    {
                        "data": "company_phone_number",
                        "name": "company_phone_number",
                        "targets": "company_phone_number",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.company_phone_number;
                        }
                    },
                    {
                        "data": "first_name",
                        "name": "first_name",
                        "targets": "first_name",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.first_name;
                        }
                    },
                    {
                        "data": "last_name",
                        "name": "last_name",
                        "targets": "last_name",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.last_name;
                        }
                    },
                    {
                        "data": "email",
                        "name": "email",
                        "targets": "email",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.email;
                        }
                    },
                    {
                        "data": "person_linkedin_url",
                        "name": "person_linkedin_url",
                        "targets": "person_linkedin_url",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.person_linkedin_url;
                        }
                    },
                    {
                        "data": "source_url",
                        "name": "source_url",
                        "targets": "source_url",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.source_url;
                        }
                    },
                    {
                        "data": "person_location",
                        "name": "person_location",
                        "targets": "person_location",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return _full.person_location;
                        }
                    },
                    {
                        "data": "updated_at",
                        "name": "updated_at",
                        "targets": "updated_at",
                        'orderable': false,
                        "render": function (_data, _type, _full, meta) {
                            return getLocalTime(_full.updated_at);
                        }

                    },
                    {
                        "data": "status",
                        "name": "status",
                        "targets": "status",
                        // "className": "text-center w-250px",
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


    // resetFilter: function () {
    //     $('#resetFilter').on('click', function (e) {
    //         $("#resetFormFilter").trigger("reset");
    //         $('.designation-title').val(null).trigger('change');
    //         $('.companies-domain').val(null).trigger('change');
    //         $('#data_filter_badge').addClass('d-none');
    //         $("#searchStatus").val('').trigger('change');
    //         $("#data-list-table").DataTable().draw(true);
    //     });
    // },
    resetFilter: function () {
        $('#resetFilter').on('click', function () {
            $("#resetFormFilter").trigger("reset");
            $('.designation-title').val(null).trigger('change');
            $('.companies-domain').val(null).trigger('change');

            // clear bulk input + global
            $('#companies-bulk-input').val('');
            window.companyWebsitesBulk = [];

            $('#data_filter_badge').addClass('d-none');
            $("#searchStatus").val('').trigger('change');

            $("#data-list-table").DataTable().draw(true);
        });
    },



    // searchFilter: function () {
    //     /*  filter starts here */
    //     $('#searchFilter').on('click', function (e) {
    //         var searchStatus = $('#searchStatus').val();
    //         var searchDesignation = $('.designation-title').val();
    //         var searchDomain = $('.companies-domain').val();
    //         if (searchStatus || (searchDesignation && searchDesignation.length > 0) || (searchDomain && searchDomain.length > 0)) {
    //             $('#data_filter_badge').removeClass('d-none');
    //         } else {
    //             $('#data_filter_badge').addClass('d-none');
    //         }
    //         $("#data-list-table").DataTable().draw(true);
    //     });

    //     /*  filter ends here */
    // },


    searchFilter: function () {
        $('#searchFilter').on('click', function () {

            var searchStatus = $('#searchStatus').val();
            var searchDesignation = $('.designation-title').val() || [];
            var searchDomain = $('.companies-domain').val() || [];
            var bulkInput = $('#companies-bulk-input').val();

            // Convert comma-separated input to array
            var bulkArray = [];
            if (bulkInput) {
                bulkArray = bulkInput
                    .split(',')
                    .map(v => v.trim())
                    .filter(v => v.length > 0);
            }

            // store globally for DataTable ajax
            window.companyWebsitesBulk = bulkArray;

            // badge toggle
            if (
                searchStatus ||
                searchDesignation.length > 0 ||
                searchDomain.length > 0 ||
                bulkArray.length > 0
            ) {
                $('#data_filter_badge').removeClass('d-none');
            } else {
                $('#data_filter_badge').addClass('d-none');
            }

            $("#data-list-table").DataTable().draw(true);
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
                var Text = 'You want to inactive this data ?'
            }
            else {
                var status = 'active';
                var Text = 'You want to activate data ?'

            }
            let url = route('admin.data.changeStatus');
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
                                successToaster('Data ' + response.message, 'Data Management');
                                setTimeout(function () {
                                    $('#data-list-table').DataTable().ajax.reload();
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

    // initDesignationSelect: function () {
    //     const $designationSelect = $('.designation-title');

    //     $designationSelect.select2({
    //         placeholder: function () {
    //             return $(this).data('placeholder');
    //         },
    //         allowClear: false,
    //         maximumSelectionLength: 20,
    //         width: '100%',
    //         ajax: {
    //             url: route('admin.designation.list'),
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 return {
    //                     search: params.term || '', // search term
    //                     size: 10                  // limit results to 10
    //                 };
    //             },
    //             processResults: function (data) {
    //                 return { results: data };
    //             },
    //             cache: false
    //         },
    //         minimumInputLength: 0
    //     });
    // },


 
    // initDesignationSelect: function () {
    //     const $designationSelect = $('.designation-title');

    //     $designationSelect.select2({
    //         placeholder: function () {
    //             return $(this).data('placeholder');
    //         },
    //         width: '100%',
    //         multiple: true,
    //         tags: true,
    //         maximumSelectionLength: 20,
    //         minimumInputLength: 0,
    //         tokenSeparators: [','],

    //         ajax: {
    //             url: route('admin.designation.list'),
    //             dataType: 'json',
    //             delay: 250,

    //             data: function (params) {
    //                 return {
    //                     search: params.term || '',
    //                     size: 20
    //                 };
    //             },

    //             processResults: function (data, params) {
    //                 data = Array.isArray(data) ? data : [];

    //                 const termRaw = params.term ? params.term.trim() : '';
    //                 const termLower = termRaw.toLowerCase();

    //                 // No search term → return backend results as-is
    //                 if (!termRaw) {
    //                     return { results: data };
    //                 }

    //                 // Case-insensitive check if backend already has this value
    //                 const existsInBackend = data.some(item =>
    //                     item.text.toLowerCase() === termLower
    //                 );

    //                 // If backend already contains it → DO NOT add typed value
    //                 if (existsInBackend) {
    //                     return { results: data };
    //                 }

    //                 // Otherwise → show typed value FIRST
    //                 return {
    //                     results: [
    //                         {
    //                             id: termLower,   // normalize ID
    //                             text: termRaw,
    //                             isNew: true
    //                         },
    //                         ...data
    //                     ]
    //                 };
    //             },

    //             cache: false
    //         },

    //         createTag: function (params) {
    //             const term = $.trim(params.term);
    //             if (!term) return null;

    //             const termLower = term.toLowerCase();
    //             const selectedValues = $designationSelect.val() || [];

    //             // Prevent duplicate selection (case-insensitive)
    //             const exists = selectedValues.some(
    //                 v => v.toLowerCase() === termLower
    //             );

    //             if (exists) return null;

    //             return {
    //                 id: termLower,   // normalize ID
    //                 text: term,
    //                 isNew: true
    //             };
    //         }
    //     });
    // },


    // initDesignationSelect: function () {
    //     const $designationSelect = $('.designation-title');

    //     $designationSelect.select2({
    //         placeholder: function () {
    //             return $(this).data('placeholder');
    //         },
    //         width: '100%',
    //         multiple: true,
    //         tags: true,
    //         maximumSelectionLength: 20,
    //         minimumInputLength: 0,
    //         tokenSeparators: [','],

    //         ajax: {
    //             url: route('admin.designation.list'),
    //             dataType: 'json',
    //             delay: 250,

    //             data: function (params) {
    //                 return {
    //                     search: params.term || '',
    //                     size: 20
    //                 };
    //             },

    //             processResults: function (data, params) {
    //                 data = Array.isArray(data) ? data : [];

    //                 const termRaw = params.term ? params.term.trim() : '';
    //                 const termLower = termRaw.toLowerCase();

    //                 if (!termRaw) {
    //                     return { results: data };
    //                 }

    //                 const existsInBackend = data.some(item =>
    //                     item.text.toLowerCase() === termLower
    //                 );

    //                 if (existsInBackend) {
    //                     return { results: data };
    //                 }

    //                 return {
    //                     results: [
    //                         {
    //                             id: termLower,
    //                             text: termRaw,
    //                             isNew: true
    //                         },
    //                         ...data
    //                     ]
    //                 };
    //             },

    //             cache: false
    //         },

    //         createTag: function (params) {
    //             const term = $.trim(params.term);
    //             if (!term) return null;

    //             // block comma-based creation here
    //             if (term.includes(',')) return null;

    //             const termLower = term.toLowerCase();
    //             const selectedValues = $designationSelect.val() || [];

    //             const exists = selectedValues.some(
    //                 v => v.toLowerCase() === termLower
    //             );

    //             if (exists) return null;

    //             return {
    //                 id: termLower,
    //                 text: term,
    //                 isNew: true
    //             };
    //         }
    //     });

    //     /* ----------------------------------------
    //     COMMA SPLIT HANDLER (ENTER / TAB / PASTE)
    //     ----------------------------------------- */

    //     function splitAndCreateTags(value) {
    //         if (!value) return;

    //         const parts = value
    //             .split(',')
    //             .map(v => v.trim())
    //             .filter(Boolean);

    //         let selected = $designationSelect.val() || [];

    //         parts.forEach(part => {
    //             const lower = part.toLowerCase();

    //             if (!selected.some(v => v.toLowerCase() === lower)) {
    //                 const option = new Option(part, lower, true, true);
    //                 $designationSelect.append(option);
    //                 selected.push(lower);
    //             }
    //         });

    //         $designationSelect.trigger('change');
    //     }

    //     $designationSelect.on('select2:opening', function () {
    //         const input = $('.select2-search__field');

    //         // ENTER / TAB
    //         input.off('keydown.split').on('keydown.split', function (e) {
    //             if (e.key === 'Enter' || e.key === 'Tab') {
    //                 const value = input.val();

    //                 if (value) {
    //                     e.preventDefault();
    //                     splitAndCreateTags(value);
    //                     input.val('');
    //                 }
    //             }
    //         });

    //         // PASTE
    //         input.off('paste.split').on('paste.split', function () {
    //             setTimeout(() => {
    //                 const value = input.val();
    //                 if (value) {
    //                     splitAndCreateTags(value);
    //                     input.val('');
    //                 }
    //             }, 0);
    //         });
    //     });
    // },


    initDesignationSelect: function () {
        const $designationSelect = $('.designation-title');

        $designationSelect.select2({
            placeholder: function () {
                return $(this).data('placeholder');
            },
            width: '100%',
            multiple: true,
            tags: true,
            maximumSelectionLength: 20,
            minimumInputLength: 0,
            tokenSeparators: [','],

            ajax: {
                url: route('admin.designation.list'),
                dataType: 'json',
                delay: 250,

                data: function (params) {
                    return {
                        search: params.term || '',
                        size: 20
                    };
                },

                processResults: function (data, params) {
                    data = Array.isArray(data) ? data : [];

                    const termRaw = params.term ? params.term.trim() : '';
                    const termLower = termRaw.toLowerCase();

                    if (!termRaw) {
                        return { results: data };
                    }

                    // If backend already has this term → DO NOT add as tag
                    const existsInBackend = data.some(item =>
                        item.text.toLowerCase() === termLower
                    );

                    if (existsInBackend) {
                        return { results: data };
                    }

                    // Show typed value as option (but real creation is controlled later)
                    return {
                        results: [
                            {
                                id: termLower,
                                text: termRaw,
                                isNew: true
                            },
                            ...data
                        ]
                    };
                },

                cache: false
            },

            createTag: function (params) {
                const term = $.trim(params.term);
                if (!term) return null;

                // Block comma-based creation (handled manually)
                if (term.includes(',')) return null;

                const termLower = term.toLowerCase();

                // Prevent duplicate selection (case-insensitive)
                const selectedValues = $designationSelect.val() || [];
                if (selectedValues.some(v => v.toLowerCase() === termLower)) {
                    return null;
                }

                // Prevent creating tag if option already exists
                let existsInOptions = false;
                $designationSelect.find('option').each(function () {
                    if ($(this).text().toLowerCase() === termLower) {
                        existsInOptions = true;
                        return false;
                    }
                });

                if (existsInOptions) return null;

                return {
                    id: termLower,
                    text: term,
                    isNew: true
                };
            }
        });

        /* ----------------------------------------
        COMMA SPLIT HANDLER (TAB / ENTER / PASTE)
        ----------------------------------------- */

        function splitAndCreateTags(value) {
            if (!value) return;

            const parts = value
                .split(',')
                .map(v => v.trim())
                .filter(Boolean);

            let selected = $designationSelect.val() || [];

            parts.forEach(part => {
                const lower = part.toLowerCase();

                if (!selected.some(v => v.toLowerCase() === lower)) {
                    const option = new Option(part, lower, true, true);
                    $designationSelect.append(option);
                    selected.push(lower);
                }
            });

            $designationSelect.trigger('change');
        }

        $designationSelect.on('select2:opening', function () {
            const input = $('.select2-search__field');

            // ENTER / TAB
            input.off('keydown.split').on('keydown.split', function (e) {

                // 🔑 If Select2 has highlighted option → let Select2 select it
                const highlighted = $('.select2-results__option--highlighted');

                if (e.key === 'Enter' && highlighted.length > 0) {
                    return; // let Select2 handle existing option
                }

                if (e.key === 'Enter' || e.key === 'Tab') {
                    const value = input.val();
                    if (value) {
                        e.preventDefault();
                        splitAndCreateTags(value);
                        input.val('');
                    }
                }
            });

            // PASTE
            input.off('paste.split').on('paste.split', function () {
                setTimeout(() => {
                    const value = input.val();
                    if (value) {
                        splitAndCreateTags(value);
                        input.val('');
                    }
                }, 0);
            });
        });
    },



    initCompaniesDomainSelect: function () {
        const $companiesSelect = $('.companies-domain');

        $companiesSelect.select2({
            placeholder: function () {
                return $(this).data('placeholder');
            },
            allowClear: false,
            maximumSelectionLength: 20,
            width: '100%',
            ajax: {
                url: route('admin.companywebsites.list'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '', // search term
                        size: 10                  // limit results to 10
                    };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: false
            },
            minimumInputLength: 0
        });
    },

    dataImportModel: function () {
        $('#import-data').on('click', function(){
            let btn = $('#import-data');
            let btnName = btn.html();
            $.ajax({
                type: 'GET',
                url: route('admin.data.import-form'),
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                },
                success: function(response) {
                    $('#import-data-div .modal-body').html(response);
                    $('#import-data-div').modal('show');
                },
                error: function(err) {
                    handleError(err);
                },
                complete: function() {
                    showButtonLoader(btn, btnName, false);
                }
            });
        });
    },



    


  

};

$(function () {
    datas.init();

    $(document).on('click', '#importDataBtn', function(){
        let btn = $('#importDataBtn');
        let btnName = btn.html();
        let form = $('#importDataForm')
        var formData = new FormData(form[0]);
        if (form.valid()) {
            showButtonLoader(btn, btnName, 'disabled');
            $.ajax({
                type: 'POST',
                url: route('admin.data.import'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    showButtonLoader(btn, btnName, true);
                },
                success: function(response) {

                    if (response.error == 400) {
                        $('#importData, .dataError').removeClass('d-none');
                        $("#successVal").text(response.stat.successfull);
                        $("#unsuccessVal").text(response.stat.unsuccessfull);
                        $("#errorVal").text(response.stat.errors);
                        $("#importData").attr('href',response.stat.fileName);
                        setTimeout(function () {
                            $('#data-list-table').DataTable().ajax.reload();
                        }, 1000);
                    }

                    if (response.success == 200) {
                        $('#import-data-div').modal('hide');
                        successToaster(response.message);
                        setTimeout(function () {
                          $('#data-list-table').DataTable().ajax.reload();
                          }, 1000);
                    }

                },
                error: function(err) {
                    $('#import-data-div').modal('hide');
                    handleError(err);
                },
                complete: function() {
                    showButtonLoader(btn, btnName, false);
                }
            });
        }
    });

});
