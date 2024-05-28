<script>
    $(document).ready(function () {
        // Select2 initialize
        $('form select').not('#recurringSelect, #statusSelect, #multi-schedule-date, #multi-schedule-session').select2({
            theme: "bootstrap-5",
            placeholder: "Select an option",
            // allowClear: true
        });
        $('#sessionSelect, #weekdaySelect').select2({
            theme: "bootstrap-5",
            minimumResultsForSearch: -1
        });
        $('#pRoomSelect').select2({
            theme: "bootstrap-5",
            searchable: true,
            dropdownParent: $('#all-schedule-modal-content')
        });
        $('#module-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by module',
            allowClear: true
        })
        $('#teacher-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by teacher',
            allowClear: true
        })

        $('#status-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by status',
            allowClear: true
        })
        // end

        // Multi switch
        $('#multi-switch').change(function () {
            if ($(this).is(':checked')) {
                $('.show-for-multi input').prop('disabled', false);
                $('.show-for-multi').fadeIn('fast');
            } else {
                $('.show-for-multi input').prop('disabled', true);
                $('.show-for-multi').hide();
            }
        });
        // end

        //Data table initiate
        const pclassTable = $('#pclass-management-table').DataTable({
            ajax: {
                url: '{{route('practice-classes.get-json-data')}}',
                dataSrc: ''
            },
            scrollY: '53vh',
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", error);
                toastr.error("An error occurred while loading the data", "Error");
            },
            columns: [
                {data: 'index', width: '4%'},
                {data: 'module_info', type: 'html', width: '15%'},
                {data: 'class_info', type: 'html', width: '25%'},
                {data: 'teacher', type: 'html', width: '10%'},
                {data: 'weekday', type: 'html', width: '7%'},
                {data: 'k1Qty', type: 'html', width: '5%'},
                {data: 'k2Qty', type: 'html', width: '5%'},
                {
                    data: 'status', type: 'html', width: '8%',
                    render: function (data) {
                        return `
                                <div class="cell-clamp" title="${data.title}">
                                    ${data.value}
                                </div>
                            `;
                    }
                },
                {data: 'actions', type: 'html', width: '5%'},
            ],
            columnDefs: [
                {
                    className: "dt-center",
                    targets: [0,4,5,6,7,8]
                },
                {
                    orderable: false,
                    targets: [1,2,3,4,7,8]
                }
            ],
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'Search anything'
                    },
                    buttons: [
                        'length',
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        }
                    ]
                },
            },
            pageLength: 10,
            language: {
                "info": "Showing _START_ to _END_ of _TOTAL_ classes",
                //customize pagination prev and next buttons: use arrows instead of words
                'paginate': {
                    'first': '<span class="fa-solid fa-backward-step"></span>',
                    'previous': '<span class="fa fa-chevron-left"></span>',
                    'next': '<span class="fa fa-chevron-right"></span>',
                    'last': '<span class="fa-solid fa-forward-step"></span>'
                },
                //customize number of elements to be displayed
                "lengthMenu": '<select class="form-control input-sm">' +
                    '<option value="-1">All</option>' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="30">30</option>' +
                    '<option value="40">40</option>' +
                    '<option value="50">50</option>' +
                    '</select> classes per page'
            }
        });
        // end

        // Module filter
        $('#module-filter-select').on('change', function () {
            showOverlay();
            pclassTable.search(this.value).draw();
            hideOverlay();
        });

        $('#teacher-filter-select').on('change', function () {
            showOverlay();
            pclassTable.search(this.value).draw();
            hideOverlay();
        });

        $('#status-filter-select').on('change', function () {
            showOverlay();
            pclassTable.search(this.value).draw();
            hideOverlay();
        });
        // end

        // Update the practice class schedule status
        pclassTable.on('change', '.status-change-btn', function () {
            const $statusChangeBtn = $(this);
            const status = $statusChangeBtn.is(':checked') ? 1 : 0;
            const pclassId = $statusChangeBtn.data('pclass-id');
            const $row = $statusChangeBtn.closest('tr'); // Get the closest row (<tr>) element
            const rowData = pclassTable.row($row).data(); // Get the data for this row

            // Show the loading overlay
            showOverlay();

            $.ajax({
                url: '{{route('practice-classes.update-practice-class-status')}}',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    status: status,
                    pclassId: pclassId
                },
                success: function (response) {
                    // Hide the loading overlay
                    hideOverlay();

                    console.log(response);
                    switch (response.status) {
                        case 200:
                            toastr.success(response.message, response.title || "Success");
                            // Update the row data here if needed
                            rowData.status = response.newStatus; // Assume response contains new status
                            rowData.status_raw = response.newStatusRaw; // Assume response contains new status
                            pclassTable.row($row).data(rowData).invalidate().draw(false); // Invalidate the data cache
                            console.log(rowData);
                            break;
                        case 500:
                            console.log('failed');
                            $statusChangeBtn.prop('checked', false);
                            toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end

        // Create p-class form
        $('#add-pclass-form-toggle').click(function () {
            $('#new-pclass-form-wrapper').slideToggle(400, 'linear');
        });

        const newPracticeClassForm = $('#new-pclass-form');
        setupAjaxForm(newPracticeClassForm);
        // end

        // Edit practice class schedule modal
        const editPclassModal = new bootstrap.Modal('#edit-pclass-modal', {backdrop: true});
        const editPclassForm = $('#edit-pclass-form');

        $(document).on('click', '.pclass-edit-btn', function () {
            const data = pclassTable.row($(this).closest('tr')).data();

            console.log(data);

            $('#editModuleId').val(data.DT_RowData.module_id);
            $('#editClassCode').val(data.DT_RowData.practice_class_code || '');
            $('#editClassName').val(data.DT_RowData.practice_class_name || '');
            $('#editTeacherSelect').val(data.DT_RowData.teacher_id || '').change();
            $('#editStatusSelect').val(data.status_raw || 0).change();

            const updateURL = $(this).data('post-url');
            editPclassForm.data('action', updateURL);

            editPclassModal.show();
        });
        setupAjaxForm(editPclassForm);
        // end

        // Delete practice class schedule modal
        const deletePclassModal = new bootstrap.Modal('#delete-pclass-modal', {backdrop: true})
        const deletePclassForm = $('#delete-pclass-form');
        $(document).on('click', '.pclass-delete-btn', function () {
            const data = $(this).closest('tr').data();

            let deleteURL = "{{ route('practice-classes.destroy', ['practice_class' => ':id'])}}";

            deleteURL = deleteURL.replace(':id', data.id);
            deletePclassForm.data('action', deleteURL);

            $('#delete-mode').val($(this).data('delete-mode'));

            deletePclassModal.show();
        });
        setupAjaxForm(deletePclassForm);
        // end

        // View all schedules of a practice class
        const infoModal = new bootstrap.Modal('#all-schedule-modal', {backdrop: true});
        const pClassAllScheduleTable = $('#pclass-all-schedule-table');

        function initAllScheduleTable($getUrl) {
            const weekdaySignature = $('#pclass-signature-form #weekdaySelect');
            const startDateSignature = $('#pclass-signature-form #start_date');
            const pRoomSignature = $('#pclass-signature-form #pRoomSelect');
            const studentQty1 = $('#pclass-signature-form #studentQty1');
            const studentQty2 = $('#pclass-signature-form #studentQty2');

            pClassAllScheduleTable.DataTable({
                ajax: {
                    url: $getUrl,
                    dataSrc: ''
                },
                select: true,
                scrollCollapse: true,
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'schedule_date', type: 'html', width: '15%'},
                    {data: 'weekday', type: 'string', width: '15%'},
                    {data: 'session', type: 'html', width: '7%', orderable: false},
                    {data: 'shifts', type: 'html', width: '10%'},
                    {data: 'practice_room', type: 'html', width: '25%', orderable: false},
                    {data: 'actions', type: 'html', width: '10%'},
                ],
                autoWidth: false,
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: "_all"
                    },
                    {
                        targets: [5],
                        createdCell: function (td) {
                            $(td).css('padding', '0');
                        }
                    }
                ],
                layout: {
                    topStart: {
                        search: {
                            placeholder: 'Search anything'
                        }
                    },
                    topEnd: {
                        buttons: [
                            'length',
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            }
                        ]
                    },
                    bottomStart: {},
                    bottomEnd: {},
                },
                paging: false,
                initComplete: function () {
                    // console.log(json);

                    // Setup for adding multi schedules
                    $('#multi-schedule-pclass-id').val(pClassAllScheduleTable.data('practice_class_id'));

                    // Update signature schedule info
                    $.ajax({
                        url: '<?= route('practice-classes.get-signature-info') ?>',
                        type: 'get',
                        data: {pClassId: pClassAllScheduleTable.data('practice_class_id')},
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (response) {
                            console.log(response);

                            startDateSignature.val(response.start_date);

                            weekdaySignature.val(response.weekday).trigger('change');

                            $("#pclass-signature-form label").removeClass('btn-outline-danger');
                            if (response.session != null) {
                                const radio = $('.signature-session[value=' + response.session + ']');
                                radio.prop('checked', true);
                                $("label[for='" + radio.attr('id') + "']").addClass('btn-outline-danger');
                            } else {
                                $('.signature-session').prop('checked', false);
                            }

                            pRoomSignature.val(response.pRoomId).change();
                            studentQty1.val(response.studentQty1);
                            studentQty2.val(response.studentQty2);
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                            toastr.error("A server error occurred. Please try again.", "Error");
                        }
                    });

                    // Update rooms selection
                    const $sessionSelects = $('.session-select');
                    const pRoomSelects = $('.practice-room-select');

                    pRoomSelects.each(function () {
                        const selectedValue = $(this).val();
                        $(this).data('current-value', selectedValue);
                        $(this).select2({
                            theme: "bootstrap-5",
                            dropdownParent: $('#all-schedule-modal-content')
                        });
                    });
                    $sessionSelects.each(function () {
                        refreshPracticeRooms($(this));
                    });

                    hideOverlay();
                }
            });
        }

        pclassTable.on('click', '.schedule-info-btn', function () {
            showOverlay();
            if ($.fn.DataTable.isDataTable(pClassAllScheduleTable)) {
                pClassAllScheduleTable.DataTable().destroy();
            }
            pClassAllScheduleTable.data('practice_class_id', $(this).data('pclass-id'));
            pClassAllScheduleTable.data('get-url', $(this).data('get-url'));
            initAllScheduleTable(pClassAllScheduleTable.data('get-url'));

            infoModal.show();
            hideOverlay();
        });
        // end

        // Reload datatable
        $(document).on('click', '.reload-table-btn', function () {
            showOverlay();
            pClassAllScheduleTable.DataTable().destroy();
            initAllScheduleTable(pClassAllScheduleTable.data('get-url'));
            hideOverlay();
        });
        // end

        // Update signature schedule
        const signatureForm = $('#pclass-signature-form');
        signatureForm.on('submit', function (e) {
            e.preventDefault();
            const $pclassId = pClassAllScheduleTable.data('practice_class_id');
            const formData = $(this).serializeObject();

            $.ajax({
                url: '<?= route('schedules.update-signature-schedule') ?>',
                method: 'put',
                data: {
                    pclassId: $pclassId,
                    data: formData
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    hideOverlay();

                    console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        toastr.success(response.message, response.title || "Success");
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function () {
                            if ($.fn.dataTable.isDataTable($(this))) {
                                $(this).DataTable().ajax.reload();
                            }
                        })
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end

        // Get available rooms when .session-select changes
        pClassAllScheduleTable.on('change', '.session-select', function () {
            refreshPracticeRooms($(this));
        });

        function refreshPracticeRooms($sessionSelect) {
            showOverlay();
            const row = $sessionSelect.closest('tr');
            const data = row.data();
            const datePicker = row.find('.schedule-date-select');

            if (datePicker.val() === '') {
                datePicker.addClass('is-invalid');
                datePicker.closest('td').append(`<div class="invalid-feedback text-start">Choose a date</div>`);
                hideOverlay();
                return;
            }

            const pRoomSelects = row.find('.practice-room-select');

            const pRoomIds = [];

            pRoomSelects.each(function () {
                pRoomIds.push($(this).data('current-value'));
            });

            // console.log(pRoomIds);

            $.ajax({
                url: '<?= route('schedules.get-available-rooms') ?>',
                method: 'get',
                data: {
                    'practice_class_id': data[0].practice_class_id,
                    'schedule_date': datePicker.val(),
                    'session': $sessionSelect.val(),
                    'current_practice_class_room_ids': pRoomIds,
                    'practice_class_room_ids': [data[0].practice_room_id, data[1].practice_room_id],
                    'schedule_ids[]': [data[0].id, data[1].id],
                    'session_id': data[0].session_id,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    // Hide the loading overlay
                    hideOverlay();

                    // console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        pRoomSelects.eq(0).html(response.practice_room_options_1);
                        pRoomSelects.eq(1).html(response.practice_room_options_2);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        }

        pClassAllScheduleTable.on('change', '.schedule-date-select', function () {
            $(this).removeClass('is-invalid');
            const $row = $(this).closest('tr');

            const date = new Date($(this).val());
            const options = {weekday: 'long'};
            const weekday = date.toLocaleDateString('en-US', options).toUpperCase();
            const weekdayText = $row.find('.weekday-text');
            weekdayText.text(weekday);

            const sessionSelect = $row.find('.session-select');
            if (sessionSelect.val() !== '') {
                sessionSelect.change();
            }
        });
        // end

        // Add single schedule for practice class
        const addScheduleBtn = $('#add-schedule-btn');
        addScheduleBtn.click(function (event) {
            showOverlay();
            event.preventDefault();
            const formData = {
                'practice_class_id': pClassAllScheduleTable.data('practice_class_id'),
            };

            $.ajax({
                url: '<?= route('schedules.store') ?>',
                method: 'post',
                data: formData,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    // Hide the loading overlay
                    hideOverlay();

                    console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        toastr.success(response.message, response.title || "Success");
                    }

                    // Reset requested element (mostly input form)
                    if (response.resetTarget) {
                        $(response.resetTarget).trigger('reset');
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function () {
                            if ($.fn.dataTable.isDataTable($(this))) {
                                $(this).DataTable().ajax.reload();
                            }
                        })
                    }

                    //Hide requested element (mostly confirm modal)
                    if (response.hideTarget) {
                        $(response.hideTarget).modal('hide');
                        console.log(response.hideTarget);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end

        // Add multi schedules for practice class
        const multiScheduleForm = $('#multi-schedule-form');
        multiScheduleForm.data('action', '<?= route('schedules.store') ?>');
        setupAjaxForm(multiScheduleForm);
        // end

        // Save single schedule info
        pClassAllScheduleTable.on('click', '.schedule-single-save-btn', function () {
            showOverlay();
            const row = $(this).closest('tr');
            const rowData = row.data();
            const datePicker = row.find('.schedule-date-select');

            const pClassId = rowData[0].practice_class_id;
            const pRoomIds = [];
            const pRoomSelects = row.find('.practice-room-select');
            pRoomSelects.each(function () {
                const selectedValue = $(this).val();
                pRoomIds.push(selectedValue);
            });

            const sessionSelect = row.find('.session-select');
            const schedule1id = rowData[0].id;
            const schedule2id = rowData[1].id;

            const data = {
                [schedule1id]: {
                    'schedule_date': datePicker.val(),
                    'practice_room_id': pRoomIds[0],
                    'session': sessionSelect.val(),
                },
                [schedule2id]: {
                    'schedule_date': datePicker.val(),
                    'practice_room_id': pRoomIds[1],
                    'session': sessionSelect.val(),
                }
            };

            $.ajax({
                url: '<?= route('schedules.update-single-schedule') ?>',
                method: 'put',
                data: {
                    pclassId: pClassId,
                    newData: data
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    hideOverlay();

                    console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        if (response.isCaution === true) {
                            toastr.warning(response.message, response.title);
                        } else {
                            toastr.success(response.message, response.title || "Success");
                        }
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function () {
                            if ($.fn.dataTable.isDataTable($(this))) {
                                $(this).DataTable().ajax.reload();
                            }
                        })
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end

        // Delete single schedule info
        pClassAllScheduleTable.on('click', '.schedule-single-delete-confirm', function () {
            showOverlay();

            const sessionId = $(this).data('session-id');

            $.ajax({
                url: '<?= route('schedules.delete-single-schedule') ?>',
                method: 'delete',
                data: {
                    'session_id': sessionId
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    hideOverlay();

                    console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        toastr.success(response.message, response.title || "Success");
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function () {
                            if ($.fn.dataTable.isDataTable($(this))) {
                                $(this).DataTable().ajax.reload();
                            }
                        })
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end
    })
</script>