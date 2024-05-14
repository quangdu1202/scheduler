@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2">Register Classes</h1>
        @if(auth()->user() !== null)
            <div class="user-info">
                <span>Hello Teacher <b>{{auth()->user()->name}}</b>!</span>
                <span>Teacher ID: {{auth()->user()->userable->id}}</span>
            </div>
        @endif
    </div>

    <!-- Action Buttons (Add new, etc.) -->
    <div class="top-nav nav mb-3 d-flex align-items-center">
        <!-- Filters -->
        <form id="module-filter" action="#" class="d-flex align-items-center col-3">
            <label for="module-filter-select" class="me-2 text-nowrap fw-bold">Module:</label>
            <select name="module" id="module-filter-select" class="form-select">
                <option></option>
                @foreach($modules as $module)
                    <option value="{{$module->id}}">{{'(' . $module->module_code . ') ' . $module->module_name}}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table id="register-schedule-table" class="table table-bordered w-100">
            <thead class="border-black">
                <tr>
                    <th>MON</th>
                    <th>TUE</th>
                    <th>WED</th>
                    <th>THU</th>
                    <th>FRI</th>
                    <th>SAT</th>
                    <th>SUN</th>
                </tr>
            </thead>
        </table>
    </div>
    <hr>
    <!-- Practice Classes Table -->
    <button class="btn btn-outline-primary mx-auto d-block" id="toggle-register-table">
        <i class="lni lni-chevron-up align-middle"></i>
    </button>
    <div class="table-responsive" id="toggle-register-table-target">
        <table id="pclass-register-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Start Date</th>
                <th>Shift Qty</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <hr>
    <!-- Registered Classes Table -->
    {{--<div class="table-responsive">
        <table id="registered-pclass-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Students</th>
                <th>Shift Qty</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>--}}

    <!-- Class schedules Info modal -->
    <div class="modal modal-xl fade" id="pclass-schedules-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="pclass-schedules-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="pclass-schedules-table" class="table table-bordered table-hover w-100">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Schedule Date</th>
                                <th>Session</th>
                                <th>Shift</th>
                                <th>Practice Room</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Select2 initialize
            $('form select').not('#recurringSelect, #statusSelect, #multi-schedule-date, #multi-schedule-session').select2({
                theme: "bootstrap-5",
                placeholder: "Select an option",
                allowClear: true
            });
            // end

            // Schedule table initiate
            const scheduleTable = $('#register-schedule-table').DataTable({
                ajax: {
                    url: '{{route('teacher.get-schedule-table')}}',
                    dataSrc: ''
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    toastr.error("An error occurred while loading the data", "Error");
                },
                columns: [
                    {data: 'mon'},
                    {data: 'tue'},
                    {data: 'wed'},
                    {data: 'thu'},
                    {data: 'fri'},
                    {data: 'sat'},
                    {data: 'sun'},
                ],
                columnDefs: [
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
                        "createdCell": function (td, cellData, rowData, row, col) {
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

            //Data table initiate
            const pclassRegisterTable = $('#pclass-register-table').DataTable({
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
                    {data: 'actions', type: 'html', width: '10%'},
                ],
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: [0, 4, 5, 6, 7]
                    },
                    {
                        targets: [1, 2, 3, 5],
                        render: function (data, type, row) {
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
                        },
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

            $('#toggle-register-table').click(function() {
                const $icon = $(this).find('i');
                $('#toggle-register-table-target').slideToggle();
                if ($icon.css('transform') === 'none') {
                    $icon.css('transform', 'rotate(180deg)');
                } else {
                    $icon.css('transform', '');
                }
            });

            //Registered classes table
            const registeredClassesTable = $('#registered-pclass-table').DataTable({
                ajax: {
                    url: '{{route('teacher.get-registered-class')}}',
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
                    {data: 'registered_qty', type: 'html', width: '10%'},
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
                    {data: 'actions', type: 'html', width: '10%'},
                ],
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: [0, 4, 5, 6, 7]
                    },
                    {
                        targets: [1, 2, 3, 5],
                        render: function (data, type, row) {
                            return `<div class="cell-clamp" title="${data}">${data}</div>`;
                        }
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

            registeredClassesTable.on('click', '.schedule-info-btn', function () {
                showScheduleInfo($(this));
            });

            registeredClassesTable.on('click', '.cancel-class-btn', function () {
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
                    success: function(response) {
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
                        if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                            $(response.reloadTarget).DataTable().ajax.reload();
                        }
                        //Hide requested element (mostly confirm modal)
                        if (response.hideTarget) {
                            $(response.hideTarget).modal('hide');
                        }
                    },
                    error: function(xhr) {
                        hideOverlay();
                        console.log(xhr.responseText);
                        toastr.error("A server error occurred. Please try again.", "Error");
                    }
                });
            });

            // View all schedules of a practice class
            const infoModal = new bootstrap.Modal('#pclass-schedules-modal');
            const pClassSchedulesTable = $('#pclass-schedules-table');

            pclassRegisterTable.on('click', '.schedule-info-btn', function () {
                showScheduleInfo($(this));
            });
            // end

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
                    }
                });

                infoModal.show();
            }

            pclassRegisterTable.on('click', '.register-class-btn', function () {
                if (!confirm("Confirm to register for this class?")) {
                    return;
                }
                showOverlay();
                const teacherId = '{{auth()->user()->userable->id}}';
                const pclassId = $(this).data('pclass-id');

                $.ajax({
                    url: '{{route('teacher.register')}}',
                    type: 'post',
                    data: {
                        teacherId: teacherId,
                        pclassId: pclassId
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
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
                        if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                            $(response.reloadTarget).DataTable().ajax.reload();
                        }
                        //Hide requested element (mostly confirm modal)
                        if (response.hideTarget) {
                            $(response.hideTarget).modal('hide');
                        }
                    },
                    error: function(xhr) {
                        hideOverlay();
                        console.log(xhr.responseText);
                        toastr.error("A server error occurred. Please try again.", "Error");
                    }
                });
            });
        });
    </script>

@endsection
