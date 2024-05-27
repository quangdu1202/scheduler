<script>
    $(document).ready(function () {
        // Select2 initialize
        $('form select').not('#recurringSelect, #statusSelect, #multi-schedule-date, #multi-schedule-session, #weekdaySelect, #pRoomSelect').select2({
            theme: "bootstrap-5",
            placeholder: "Select an option",
            allowClear: true
        });

        $('#module-filter-select').select2({
            theme: "bootstrap-5",
            searchable: true,
            placeholder: 'Filter by module',
            allowClear: true
        })
        // end

        // Schedule table initiate
        const registerScheduleTable = $('#register-schedule-table');
        let scheduleTableGetUrl = '';
        @if(Route::currentRouteName() == 'teacher.register-classes')
        scheduleTableGetUrl = '{{route('teacher.get-schedule-table')}}';
        @else
        scheduleTableGetUrl = '{{route('teacher.get-registered-schedule-table')}}';
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
                    width: (95 / 7) + "%", "targets": [2, 3, 4, 5, 6, 7, 8] },
                {
                    visible: false,
                    orderable: false,
                    targets: 0
                },
                {
                    className: "dt-center position-relative",
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

        // show registered class schedule
        registerScheduleTable.on('mouseenter', '.registered-class', function() {
            $(this).addClass('text-bg-secondary');
        }).on('mouseleave', '.registered-class', function() {
            $(this).removeClass('text-bg-secondary');
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
                    url: '{{route('teacher.get-available-classes')}}',
                    dataSrc: ''
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    toastr.error("An error occurred while loading the data", "Error");
                },
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'module_info', type: 'html', width: '20%'},
                    {data: 'practice_class_code', type: 'html', width: '10%'},
                    {data: 'practice_class_name', type: 'html', width: '20%'},
                    {data: 'start_date', type: 'html', width: '10%'},
                    {data: 'schedule_text', type: 'html', width: '10%'},
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
                        targets: [0, 4, 5, 6, 7]
                    },
                    {
                        targets: [1, 2, 3, 5],
                        render: function (data) {
                            return `<div class="cell-clamp" title="${data}">${data}</div>`;
                        }
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

        // Module filter
        $('#module-filter-select').on('change', function () {
            showOverlay();
            pclassRegisterTable.DataTable().search(this.value).draw();
            hideOverlay();
        });

        // View schedule info
        $(document).on('click', '.schedule-info-btn', function () {
            showScheduleInfo($(this));
        });
        // end

        // View all schedules of a practice class (on date)
        const pClassSchedulesTable = $('#pclass-schedules-table');
        pClassSchedulesTable.on('click', '.schedule-info-btn', function () {
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
            const teacherId = '{{Auth::user()->userable->id}}';
            const pclassId = $(this).data('pclass-id');

            $.ajax({
                url: '{{route('teacher.register-class')}}',
                type: 'post',
                data: {
                    teacherId: teacherId,
                    pclassId: pclassId
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
                        reloadTarget.each(function (){
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
            const pclassId = $(this).data('pclass-id');

            $.ajax({
                url: '{{route('teacher.cancel-registered-class')}}',
                type: 'post',
                data: {
                    pclassId: pclassId
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
                        reloadTarget.each(function (){
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

        // show class on date
        const classOnDateModal = new bootstrap.Modal('#pclass-ondate-modal', {backdrop: true});
        const pClassOndateTable = $('#pclass-ondate-table');
        registerScheduleTable.on('click', '.schedule-table-add-btn', function () {
            showClassesOnDate($(this));
        });

        function showClassesOnDate($addBtn) {
            showOverlay();
            const $weekDay = $addBtn.data('weekday');
            const $session = $addBtn.data('session');

            if ($.fn.DataTable.isDataTable(pClassOndateTable)) {
                pClassOndateTable.DataTable().destroy();
            }

            pClassOndateTable.DataTable({
                ajax: {
                    url: $($addBtn).data('get-url'),
                    data: {
                        weekDay: $weekDay,
                        session: $session
                    },
                    dataSrc: ''
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    toastr.error("An error occurred while loading the data", "Error");
                    hideOverlay();
                },
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'module_info', type: 'html', width: '20%'},
                    {data: 'practice_class_code', type: 'html', width: '10%'},
                    {data: 'practice_class_name', type: 'html', width: '20%'},
                    {data: 'actions', type: 'html', width: '10%'},
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
                    classOnDateModal.show();
                }
            });
        }
        // end
    });
</script>