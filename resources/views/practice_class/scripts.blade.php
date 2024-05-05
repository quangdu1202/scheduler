<script>
    $(document).ready(function () {
        // Select2 initialize
        $('form select').not('#recurringSelect, #statusSelect').select2({
            theme: "bootstrap-5",
            placeholder: "Select an option",
            allowClear: true
        });
        // end

        // Multi switch
        $('#multi-switch').change(function () {
            if ($(this).is(':checked')) {
                $('.hidden-for-multi input').prop('disabled', true);
                $('.hidden-for-multi').hide();
                $('.show-for-multi input').prop('disabled', false);
                $('.show-for-multi').fadeIn('fast');
            } else {
                $('.show-for-multi input').prop('disabled', true);
                $('.show-for-multi').hide();
                $('.hidden-for-multi input').prop('disabled', false);
                $('.hidden-for-multi').fadeIn('fast');
            }
        });
        // end

        //Data table initiate
        const pclassTable = $('#pclass-management-table').DataTable({
            ajax: {
                url: '{{route('practice-classes.get-json-data')}}',
                dataSrc: ''
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", error);
                toastr.error("An error occurred while loading the data", "Error");
            },
            columns: [
                {data: 'index', width: '5%'},
                {data: 'practice_class_code', type: 'html', width: '10%'},
                {data: 'practice_class_name', type: 'html', width: '25%'},
                {data: 'teacher', type: 'html', width: '15%'},
                {data: 'registered_qty', type: 'html', width: '5%'},
                {data: 'shift_qty', type: 'html', width: '10%'},
                {
                    data: 'status', type: 'html', width: '10%',
                    render: function (data, type, row) {
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
                    targets: [0, 4, 5, 6, 7]
                },
                {
                    targets: [1, 2, 3, 6],
                    render: function (data, type, row) {
                        return `<div class="cell-clamp" title="${data}">${data}</div>`;
                    }
                },
                {
                    orderable: false,
                    targets: [1, 2, 3, 5, 7]
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
            pageLength: -1,
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

        // Update the practice class schedule status
        pclassTable.on('change', '.status-change-btn', function (e) {
            // e.preventDefault();
            const status = $(this).is(':checked') ? 1 : 0;
            const pclassId = $(this).data('pclass-id');
            const $row = $(this).closest('tr'); // Get the closest row (<tr>) element
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
                        default:
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

        /*
         let pcQty = 0; // Variable to store pc quantity

         $('#roomSelect').change(function() {
             pcQty = $('option:selected', this).data('pc-qty');
             $('#maxStudentQty').val(pcQty).change().attr('disabled', false); // Set and trigger change to validate immediately
         });

         $('#maxStudentQty').on('input', function() {
             if (parseInt($(this).val()) > pcQty) {
                 $(this).addClass('is-invalid'); // Add Bootstrap's is-invalid class to show tooltip
                 $(this).removeClass('is-valid');
             } else {
                 $(this).removeClass('is-invalid');
                 $(this).addClass('is-valid'); // Optionally add is-valid class to indicate correct input
             }
         });

         $('#recurringSelect').change(function () {
             if ($(this).val() !== '0') {
                 $('#repeatLimit').prop('disabled', false).val(1);
             } else {
                 $('#repeatLimit').prop('disabled', true);
             }
         });
        */

        const newPracticeClassForm = $('#new-pclass-form');
        setupAjaxForm(newPracticeClassForm);
        // end

        // Edit practice class schedule modal
        const editPclassModal = new bootstrap.Modal('#edit-pclass-modal');
        const editPclassForm = $('#edit-pclass-form');

        $(document).on('click', '.pclass-edit-btn', function () {
            const data = pclassTable.row($(this).closest('tr')).data();

            console.log(data);

            $('#editModuleId').val(data.module_id);
            $('#editClassCode').val(data.practice_class_code || '');
            $('#editClassName').val(data.practice_class_name || '');
            $('#editTeacherSelect').val(data.teacher_id || '').change();
            $('#editStudentQty').val(data.max_qty || 0);
            $('#editStatusSelect').val(data.status_raw || 0).change();

            const updateURL = $(this).data('post-url');
            editPclassForm.data('action', updateURL);

            editPclassModal.show();
        });
        setupAjaxForm(editPclassForm);
        // end

        // Delete practice class schedule modal
        const deletePclassModal = new bootstrap.Modal('#delete-pclass-modal')
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
        const infoModal = new bootstrap.Modal('#all-schedule-modal');
        const pClassAllScheduleTable = $('#pclass-all-schedule-table');

        pclassTable.on('click', '.schedule-info-btn', function () {
            if ($.fn.DataTable.isDataTable(pClassAllScheduleTable)) {
                pClassAllScheduleTable.DataTable().destroy();
            }
            pClassAllScheduleTable.data('practice_class_id', $(this).data('pclass-id'));

            pClassAllScheduleTable.DataTable({
                ajax: {
                    url: $(this).data('get-url'),
                    dataSrc: ''
                },
                select: true,
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'teacher', type: 'string', width: '25%'},
                    {data: 'schedule_date', type: 'html', width: '15%'},
                    {data: 'session', type: 'html', width: '5%', orderable: false},
                    {data: 'shifts', type: 'html', width: '15%'},
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
                }
            });

            // Setup for adding multi schedules
            $('#multi-schedule-pclass-id').val(pClassAllScheduleTable.data('practice_class_id'));

            infoModal.show();
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
            const sessionSelect = $(this).closest('tr').find('.session-select');
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
                    }

                    // Reset requested element (mostly input form)
                    if (response.resetTarget) {
                        $(response.resetTarget).trigger('reset');
                    }
                    // Reload requested element (mostly data table)
                    if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                        $(response.reloadTarget).DataTable().ajax.reload();
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
                data: data,
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
                    if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                        $(response.reloadTarget).DataTable().ajax.reload();
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
                    if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                        $(response.reloadTarget).DataTable().ajax.reload();
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