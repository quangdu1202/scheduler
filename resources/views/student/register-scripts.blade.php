<script>
    $(document).ready(function () {
        // Select2 initialize
        $('form select').not('#recurringSelect, #statusSelect, #multi-schedule-date, #multi-schedule-session, #weekdaySelect, #pRoomSelect').select2({
            theme: "bootstrap-5",
            placeholder: "Select an option",
            allowClear: true
        });
        // end

        // Schedule table initiate
        const registerScheduleTable = $('#register-schedule-table');
        let scheduleTableGetUrl = '';
        @if(Route::currentRouteName() == 'student.register-classes')
            scheduleTableGetUrl = '{{route('student.get-schedule-table')}}';
        @else
            scheduleTableGetUrl = '{{route('student.get-registered-schedule-table')}}';
        @endif
        registerScheduleTable.DataTable({
            ajax: {
                url: scheduleTableGetUrl,
                dataSrc: ''
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data: ", error);
                toastr.error("An error occurred while loading the data", "Error");
            },
            columns: [
                {data: 'index', width: '0'},
                {data: 'row_session', width: '3%'},
                {data: 'mon'},
                {data: 'tue'},
                {data: 'wed'},
                {data: 'thu'},
                {data: 'fri'},
                {data: 'sat'},
                {data: 'sun'},
            ],
            autoWidth: false,
            columnDefs: [
                {
                    width: (95 / 7) + "%", "targets": [2, 3, 4, 5, 6, 7, 8]
                },
                {
                    visible: false,
                    orderable: false,
                    targets: 0
                },
                {
                    className: "dt-center",
                    targets: "_all"
                },
                {
                    orderable: false,
                    targets: "_all"
                },
                {
                    "targets": '_all',
                    "createdCell": function (td) {
                        $(td).css('padding', '0')
                    }
                }
            ],
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {},
                bottomEnd: {}
            },
        });
        // end

        // Available Schedules for register initiate
        const pclassRegisterTable = $('#pclass-register-table');
        $('#toggle-register-table').click(function () {
            const $button = $(this);
            if (!$button.hasClass('loaded')) {
                if ($.fn.DataTable.isDataTable(pclassRegisterTable)) {
                    pclassRegisterTable.DataTable().destroy();
                }
                loadAvailableClasses();
            }
            const $icon = $button.find('i');

            // Toggle icon rotation and button state
            if ($button.hasClass('expanded')) {
                $icon.css('transform', '');
                $button.removeClass('expanded');
                $button.html('Show available classes <i class="lni lni-chevron-down align-middle"></i>');
            } else {
                $icon.css('transform', 'rotate(180deg)');
                $button.addClass('expanded');
                $button.html('Hide <i class="lni lni-chevron-up align-middle"></i>');
            }

            $button.addClass('loaded');
            $('#toggle-register-table-target').slideToggle();
        });

        function loadAvailableClasses() {
            showOverlay();
            pclassRegisterTable.DataTable({
                ajax: {
                    url: '{{route('student.get-available-classes')}}',
                    dataSrc: ''
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    toastr.error("An error occurred while loading the data", "Error");
                },
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'module_info', type: 'html', width: '20%'},
                    {data: 'classInfo', type: 'html', width: '25%'},
                    {data: 'teacher_name', type: 'html', width: '10%'},
                    {data: 'start_date', type: 'html', width: '10%'},
                    {data: 'schedule_text', type: 'html', width: '7%'},
                    {data: 'k1Qty', type: 'html', width: '7%'},
                    {data: 'k2Qty', type: 'html', width: '7%'},
                    {data: 'actions', type: 'html', width: '10%'},
                ],
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: [0, 4, 5, 6, 7, 8]
                    },
                    {
                        orderable: false,
                        targets: "_all"
                    }
                ],
                layout: {
                    topEnd: {
                        search: {
                            placeholder: 'Search anything'
                        }
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
                },
                initComplete: function () {
                    hideOverlay();
                }
            });
        }

        // end

        // View schedule info
        $(document).on('click', '.schedule-info-btn', function () {
            showScheduleInfo($(this));
        });
        // end

        // View all schedules of a practice class (on date)
        const pClassSchedulesTable = $('#pclass-schedules-table');
        pClassSchedulesTable.on('click', '.schedule-info-btn', function () {
            console.log('clcoc');
            showScheduleInfo($(this));
        });
        // end

        // show schedule info function
        function showScheduleInfo($selector) {
            showOverlay();
            if ($.fn.DataTable.isDataTable(pClassSchedulesTable)) {
                pClassSchedulesTable.DataTable().destroy();
            }
            pClassSchedulesTable.data('practice_class_id', $selector.data('pclass-id'));

            pClassSchedulesTable.DataTable({
                ajax: {
                    url: $($selector).data('get-url'),
                    dataSrc: ''
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    toastr.error("An error occurred while loading the data", "Error");
                    hideOverlay();
                },
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'schedule_date', type: 'html', width: '15%'},
                    {data: 'session', type: 'html', width: '10%', orderable: false},
                    {data: 'shifts', type: 'html', width: '15%'},
                    {data: 'practice_room', type: 'html', width: '35%', orderable: false},
                ],
                autoWidth: false,
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: "_all"
                    }
                ],
                layout: {
                    topStart: {},
                    topEnd: {},
                    bottomStart: {},
                    bottomEnd: {},
                },
                paging: false,
                initComplete: function () {
                    hideOverlay();
                    const infoModal = new bootstrap.Modal('#pclass-schedules-modal', {backdrop: true});
                    infoModal.show();
                }
            });

        }

        // end

        // Register class
        $(document).on('click', '.register-class-btn', function () {
            if (!confirm("Confirm to register for this class?")) {
                return;
            }
            showOverlay();
            const studentId = '{{Auth::user()->userable->id}}';
            const pclassId = $(this).data('pclass-id');
            const session = $(this).data('session');
            const shift = $(this).data('shift');

            $.ajax({
                url: '{{route('student.register-class')}}',
                type: 'post',
                data: {
                    studentId: studentId,
                    pclassId: pclassId,
                    session: session,
                    shift: shift,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    // Hide the loading overlay
                    hideOverlay();

                    console.log(response);
                    switch (response.status) {
                        case 200:
                            toastr.success(response.message, response.title || "Success");
                            break;
                        case 422:
                            toastr.error(response.message, response.title || "Validation Error");
                            break;
                        default:
                            toastr.error(response.message || "Unknown error occurred", response.title || "Error");
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
                    }
                },
                error: function (xhr) {
                    hideOverlay();
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end

        // Cancel Register class
        $(document).on('click', '.cancel-class-btn', function () {
            if (!confirm("Cancel your registration for this class?")) {
                return;
            }
            showOverlay();
            const registrationId = $(this).data('registration-id');

            $.ajax({
                url: '{{route('student.cancel-registered-class')}}',
                type: 'post',
                data: {
                    registrationId: registrationId
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    // Hide the loading overlay
                    hideOverlay();

                    console.log(response);
                    switch (response.status) {
                        case 200:
                            toastr.success(response.message, response.title || "Success");
                            break;
                        case 422:
                            toastr.error(response.message, response.title || "Validation Error");
                            break;
                        default:
                            toastr.error(response.message || "Unknown error occurred", response.title || "Error");
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
                    }
                },
                error: function (xhr) {
                    hideOverlay();
                    console.log(xhr.responseText);
                    toastr.error("A server error occurred. Please try again.", "Error");
                }
            });
        });
        // end
    });
</script>