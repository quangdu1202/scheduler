@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2">Manage Classes</h1>
        @if(auth()->user() !== null)
            <div class="user-info">
                <span>Hello Teacher <b>{{Auth::user()->name}}</b>!</span>
                <span>Teacher ID: {{Auth::user()->userable->id}}</span>
            </div>
        @endif
    </div>

    <!-- Schedule table -->
    <div class="table-responsive">
        <table id="register-schedule-table" class="table table-bordered w-100" style="table-layout: fixed">
            <thead class="border-black">
            <tr>
                <th>#</th>
                <th>K</th>
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

    <!-- Class on date modal -->
    <div class="modal modal-xl fade" id="pclass-ondate-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="pclass-ondate-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All class on:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="pclass-ondate-table" class="table table-bordered table-hover w-100">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Class Code</th>
                                <th>Class Name</th>
                                <th>Action</th>
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
    <hr>
    <!-- Registered Classes Table -->
    <div class="table-responsive">
        <table id="registered-pclass-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Info</th>
                <th>Start Date</th>
                <th>Weekday</th>
                <th>Students</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- All schedules Info modal -->
    <div class="modal fade" id="all-schedule-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" id="all-schedule-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column overflow-y-hidden py-0">
                    <div class="pclass-signature-data sticky-top p-2 bg-white">
                        <h4>Signature Data (show on calendar)</h4>
                        <form id="pclass-signature-form"
                              data-action="{{route('schedules.update-signature-schedule')}}"
                              data-action-type="create"
                              data-action-method="post">
                            @csrf
                            <fieldset class="">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-danger">
                                            <label for="weekdaySelect" class="input-group-text">Weekday</label>
                                            <select name="weekday" id="weekdaySelect" class="form-select" disabled>
                                                <option value="">->Select</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                                <option value="7">Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-danger">
                                            <div class="input-group-text">START</div>
                                            <input type="date" name="start_date" class="form-control form-control-sm"
                                                   id="start_date" disabled>
                                            <label for="start_date" class="visually-hidden">START</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="d-flex multi-schedule-session-select border border-danger">
                                            <div class="input-group-text">SESSION</div>

                                            <input type="radio" name="session" value="1"
                                                   class="btn-check signature-session" id="session-1" autocomplete="off"
                                                   disabled>
                                            <label class="btn btn-outline-primary" for="session-1">S</label>

                                            <input type="radio" name="session" value="2"
                                                   class="btn-check signature-session" id="session-2" autocomplete="off"
                                                   disabled>
                                            <label class="btn btn-outline-primary" for="session-2">C</label>

                                            <input type="radio" name="session" value="3"
                                                   class="btn-check signature-session" id="session-3" autocomplete="off"
                                                   disabled>
                                            <label class="btn btn-outline-primary" for="session-3">T</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-success">
                                            <label for="pRoomSelect" class="input-group-text">ROOM</label>
                                            <select name="pRoomId" id="pRoomSelect" class="form-select">
                                                <option value="">->Select (not required)</option>
                                                @foreach($practiceRooms as $practiceRoom)
                                                    <option value="{{$practiceRoom->id}}">{{$practiceRoom->name . ' - ' . $practiceRoom->location}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex multi-schedule-session-select border border-success">
                                            <div class="input-group-text">STUDENT QTY</div>

                                            <label class="btn btn-primary border-primary" for="studentQty1">K1</label>
                                            <input type="number" min="0" max="99" name="studentQty1" value=""
                                                   class="form-control form-control-sm" id="studentQty1" autocomplete="off">

                                            <label class="btn btn-primary border-primary" for="studentQty2">K2</label>
                                            <input type="number" min="0" max="99" name="studentQty2" value=""
                                                   class="form-control form-control-sm" id="studentQty2" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-auto ms-auto">
                                        <button type="submit" class="btn btn-primary" id="create-pclass-btn">Save
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div class="row">
                            <div class="col-auto">
                                <button type="button" id="add-schedule-btn" class="btn btn-success">Add Schedule
                                </button>
                                <div class="vr mx-3"></div>
                                <form data-action=""
                                      data-action-method="post"
                                      id="multi-schedule-form"
                                      class="d-inline-block">
                                    <!-- Add multi schedules form -->
                                    @csrf
                                    <input type="hidden" name="practice_class_id" id="multi-schedule-pclass-id">
                                    <input type="hidden" name="add_mode" value="multi">
                                    <div class="input-group d-inline-flex w-auto">
                                        <div class="input-group-text">QTY</div>
                                        <input type="number" name="multi_schedule_qty"
                                               class="form-control form-control-sm" id="multi-schedule-qty" min="2"
                                               max="10" required>
                                        <label for="multi-schedule-qty" class="visually-hidden">Qty</label>
                                    </div>
                                    <button type="submit" form="multi-schedule-form" id="add-multi-schedule-btn"
                                            class="btn btn-primary rounded-start-0">Add Multi Schedules
                                    </button>
                                </form>
                            </div>
                            <div class="col-auto ms-auto">
                                <button class="btn btn-primary reload-table-btn"><i class="lni lni-reload align-middle"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive p-2">
                        <table id="pclass-all-schedule-table" class="table table-bordered table-hover w-100" style="table-layout: fixed">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Schedule Date</th>
                                <th>Weekday</th>
                                <th>Session</th>
                                <th>Shift</th>
                                <th>Practice Room</th>
                                <th>Action</th>
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

    <!-- Students list modal -->
    <div class="modal fade" id="pclass-student-list-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="pclass-student-list-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Students list for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column overflow-y-hidden py-0">
                    <div class="table-responsive p-2">
                        <table id="pclass-student-list-table" class="table table-bordered table-hover w-100" style="table-layout: fixed">
                            <thead class="thead-light">
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Student Code</th>
                                <th rowspan="2">Student Name</th>
                                <th rowspan="2">Gender</th>
                                <th rowspan="2">Date of birth</th>
                                <th colspan="2" class="text-center">Shift</th>
                            </tr>
                            <tr>
                                <th>K1</th>
                                <th>K2</th>
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

    <!-- Scripts -->
    @include('teacher.scripts')
    <script>
        $(document).ready(function () {
            //Registered classes table
            const registeredPclassTable = $('#registered-pclass-table');
            registeredPclassTable.DataTable({
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
                    {data: 'pclass_info', type: 'html', width: '25%'},
                    {data: 'start_date', type: 'html', width: '10%'},
                    {data: 'weekday', type: 'html', width: '10%'},
                    {data: 'registered_qty', type: 'html', width: '10%'},
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
                        targets: [1,3,4,5,6],
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
                    topStart: {},
                    topEnd: {},
                    bottomStart: {},
                    bottomEnd: {},
                },
                pageLength: -1,
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
                    initComplete: function (settings, json) {
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
                if ($.fn.DataTable.isDataTable(pClassStudentListTable)) {
                    pClassStudentListTable.DataTable().destroy();
                }
                pClassStudentListTable.data('practice_class_id', $(this).data('pclass-id'));

                pClassStudentListTable.DataTable({
                    ajax: {
                        url: $(this).data('get-url'),
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
@endsection
