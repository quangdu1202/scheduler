<script>
    $(document).ready(function () {
        $('#module-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by module',
            allowClear: true
        })

        $('#status-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by status',
            allowClear: true
        })

        // end

        //Registered classes table
        const registeredPclassTable = $('#registered-pclass-table');
        registeredPclassTable.DataTable({
            ajax: {
                url: '{{route('teacher.get-registered-classes')}}',
                dataSrc: ''
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", error);
                toastr.error("An error occurred while loading the data", "Error");
            },
            columns: [
                {data: 'index', width: '5%'},
                {data: 'module_info', type: 'html', width: '20%'},
                {data: 'pclass_info', type: 'html', width: '25%'},
                {data: 'start_date', type: 'html', width: '10%'},
                {data: 'weekday', type: 'html', width: '10%'},
                {data: 'k1Qty', type: 'html', width: '5%'},
                {data: 'k2Qty', type: 'html', width: '5%'},
                {
                    data: 'status', type: 'html', width: '10%',
                    render: function (data) {
                        return `
                                <div class="cell-clamp" title="${data.title}">
                                    ${data.value}
                                </div>
                            `;
                    }
                },
                {data: 'actions', type: 'html', width: '10%'},
            ],
            columnDefs: [
                {
                    className: "dt-center",
                    targets: [0,3,4,5,6,7,8]
                },
                {
                    orderable: false,
                    targets: "_all"
                }
            ],
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {},
                bottomEnd: {},
            },
            pageLength: -1,
        });
        // end

        // Module filter
        $('#module-filter-select').on('change', function () {
            showOverlay();
            registeredPclassTable.DataTable().search(this.value).draw();
            hideOverlay();
        });

        $('#status-filter-select').on('change', function () {
            showOverlay();
            registeredPclassTable.DataTable().search(this.value).draw();
            hideOverlay();
        });
        // end

        // View all schedules of a practice class
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
                    {data: 'session', type: 'html', width: '5%', orderable: false},
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

        const allScheduleModal = new bootstrap.Modal('#all-schedule-modal', {backdrop: true});
        const pClassAllScheduleTable = $('#pclass-all-schedule-table');
        const pClassStudentListTable = $('#pclass-student-list-table');
        const pClassStudentListModal = new bootstrap.Modal('#pclass-student-list-modal', {backdrop: true});
        registeredPclassTable.on('click', '.schedule-info-btn', function () {
            showOverlay();
            if ($.fn.DataTable.isDataTable(pClassAllScheduleTable)) {
                pClassAllScheduleTable.DataTable().destroy();
            }
            pClassAllScheduleTable.data('practice_class_id', $(this).data('pclass-id'));
            pClassAllScheduleTable.data('get-url', $(this).data('get-url'));

            initAllScheduleTable(pClassAllScheduleTable.data('get-url'));

            allScheduleModal.show();
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
                        reloadTarget.each(function (){
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
                        if (response.isCaution === true){
                            toastr.warning(response.message, response.title);
                        }else {
                            toastr.success(response.message, response.title || "Success");
                        }
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function (){
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
                        reloadTarget.each(function (){
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

        // Update signature schedule
        const signatureForm = $('#pclass-signature-form');
        signatureForm.on('submit', function (e) {
            e.preventDefault();
            showOverlay();
            const $pclassId = pClassAllScheduleTable.data('practice_class_id');
            const formData = $(this).serializeObject();

            console.log(formData);

            $.ajax({
                url: '<?= route('schedules.update-signature-schedule') ?>',
                method: 'put',
                data: {
                    pclassId: $pclassId,
                    data: formData
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    console.log(response);
                    if (response.success === false) {
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                    } else {
                        toastr.success(response.message, response.title || "Success");
                    }

                    // Reload requested element (mostly data table)
                    const reloadTarget = $(`${response.reloadTarget}`);
                    if (reloadTarget) {
                        reloadTarget.each(function (){
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
            hideOverlay();
        });
        // end

        // Get students list
        registeredPclassTable.on('click', '.pclass-student-list-btn', function () {
            showOverlay();
            $('#k1qty').text('K1' + ` (${$(this).data('k1qty')} SV)`);
            $('#k2qty').text('K2' + ` (${$(this).data('k2qty')} SV)`);
            if ($.fn.DataTable.isDataTable(pClassStudentListTable)) {
                pClassStudentListTable.DataTable().destroy();
            }

            const $pClassId = $(this).data('pclass-id');
            pClassStudentListTable.data('practice_class_id', $pClassId);

            pClassStudentListTable.DataTable({
                ajax: {
                    url: $(this).data('get-url'),
                    data: {
                        pClassId: $pClassId
                    },
                    dataSrc: ''
                },
                scrollCollapse: true,
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'student_code', type: 'html'},
                    {data: 'student_name', type: 'string'},
                    {data: 'gender', type: 'html'},
                    {data: 'dob', type: 'html'},
                    {data: 'k1Shift', type: 'html'},
                    {data: 'k2Shift', type: 'html'},
                ],
                autoWidth: false,
                columnDefs: [
                    {
                        className: "dt-center align-middle",
                        targets: "_all"
                    },
                    {
                        orderable: false,
                        targets: [1,2,3,4,5,6]
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
                                    columns: [0,1,2,3,4,5,6]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5,6]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0,1,2,3,4,5,6]
                                }
                            }
                        ]
                    },
                    bottomStart: {},
                    bottomEnd: {},
                },
                paging: false,
                initComplete: function () {
                    hideOverlay();
                }
            });

            pClassStudentListModal.show();
        });
        // end
    });
</script>