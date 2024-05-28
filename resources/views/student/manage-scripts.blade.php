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
                url: '{{route('student.get-registered-class')}}',
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
                    targets: [0,3,4,5,6,7]
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

        // View all schedules of a practice class
        function initAllScheduleTable($getUrl) {
            const weekdaySignature = $('#pclass-signature-form #weekdaySelect');
            const startDateSignature = $('#pclass-signature-form #start_date');
            const pRoomSignature = $('#pclass-signature-form #pRoomSelect');
            pClassAllScheduleTable.DataTable({
                ajax: {
                    url: $getUrl,
                    dataSrc: ''
                },
                scrollCollapse: true,
                columns: [
                    {data: 'index', width: '5%'},
                    {data: 'schedule_date', type: 'html', width: '15%'},
                    {data: 'weekday', type: 'string', width: '15%'},
                    {data: 'session', type: 'html', width: '5%', orderable: false},
                    {data: 'shift', type: 'html', width: '10%'},
                    {data: 'practice_room', type: 'html', width: '25%', orderable: false},
                ],
                autoWidth: false,
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: "_all"
                    }, {
                        orderable: false,
                        targets: "_all"
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
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            }
                        ]
                    },
                    bottomStart: {},
                    bottomEnd: {},
                },
                paging: false,
                initComplete: function () {
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
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                            toastr.error("A server error occurred. Please try again.", "Error");
                        }
                    });

                    hideOverlay();
                }
            });
        }

        const allScheduleModal = new bootstrap.Modal('#all-schedule-modal', {backdrop: true});
        const pClassAllScheduleTable = $('#pclass-all-schedule-table');
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
    });
</script>