@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body">
        <h1 class="h2">Practice Classes Management</h1>
    </div>

    <div class="top-nav nav mb-3 d-flex align-items-center">
        <!-- Action Buttons (Add new, etc.) -->
        <div class="action-buttons">
            <button id="add-pclass-form-toggle" class="btn btn-primary btn-sm" type="button">
                <i class="lni lni-circle-plus align-middle"></i> Add new
            </button>
        </div>
        <div class="vr mx-5"></div>
        <form id="practice-class-filter" action="#" class="d-flex align-items-center">
            <label for="practice-room-select" class="me-2 text-nowrap fw-bold">Module:</label>
            <select name="practice-room" id="practice-room-select" class="form-select">
                <option value="-1" selected>--- Showing All Modules ---</option>
                <option value="1">Nhập môn lập trình máy tính</option>
                <option value="2">Kỹ thuật lập trình</option>
                <option value="3">Cơ sở dữ liệu</option>
                <option value="4">Kiến trúc máy tính</option>
            </select>
        </form>
    </div>

    <!-- Create form -->
    <div id="new-pclass-form-wrapper" class="border border-primary col-12">
        <form id="new-pclass-form"
              class="p-3"
              data-action="{{route('practice-classes.store')}}"
              data-action-type="create"
              data-action-method="post">
            @csrf
            <fieldset class="">
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <select name="module_id" id="moduleSelect" class="form-select" required>
                                <option></option>
                                <option value="1">Nhập môn lập trình máy tính (202320503197)</option>
                                <option value="2">Kỹ thuật lập trình (202320595295)</option>
                            </select>
                            <label for="moduleSelect" class="form-label">Module</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <select name="teacher_id" id="teacherSelect" class="form-select">
                                <option></option>
                                <option value="1">Nguyen Van A</option>
                                <option value="2">Tran Thi B</option>
                                <option value="3">Ngo Trong C</option>
                            </select>
                            <label for="teacherSelect" class="form-label">Teacher</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-3">
                            <input type="text" name="practice_class_name" class="form-control" id="className" placeholder="Class Name" required>
                            <label for="className" class="form-label">Class Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <select name="practice_room_id" id="roomSelect" class="form-select" required>
                                <option></option>
                                <option value="1">601 A1nd kjs k skd ksd k dsk dksj kdj dkjd s</option>
                                <option value="2">702 A1</option>
                                <option value="3">802 A1</option>
                            </select>
                            <label for="roomSelect" class="form-label">Practice Room</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="schedule_date" id="startDate" min="{{ date('Y-m-d') }}" required>
                            <label for="startDate" class="form-label">Start Date</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <div class="form-control" id="sessionSelect">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="session" id="session-1" value="1" required>
                                    <label class="form-check-label" for="session-1"><span class="badge rounded-pill text-bg-success">S</span></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="session" id="session-2" value="2" required>
                                    <label class="form-check-label" for="session-2"><span class="badge rounded-pill text-bg-primary">C</span></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="session" id="session-3" value="3" required>
                                    <label class="form-check-label" for="session-3"><span class="badge rounded-pill text-bg-danger">T</span></label>
                                </div>
                            </div>
                            <label for="sessionSelect" class="form-label">Session</label>
                        </div>
                        <style>
                            #sessionSelect .form-check-inline {
                                margin-right: 0.6rem;
                            }
                        </style>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <select name="recurring_interval" id="recurringSelect" class="form-select" required>
                                <option></option>
                                <option value="0">Once (No repeat)</option>
                                <option value="604800">Weekly</option>
                                <option value="1209600">Biweekly</option>
                            </select>
                            <label for="recurringSelect" class="form-label">Recurring</label>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-floating mb-3">
                            <input type="number" id="repeatLimit" class="form-control" name="repeat_limit" min="1" max="20" disabled>
                            <label for="repeatLimit" class="form-label">Repeat</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-dark" id="create-pclass-btn">Create</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- Practice Classes Table -->
    <div class="table-responsive">
        <table id="pclass-management-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Class Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Session</th>
                <th>Room</th>
                <th>Teacher</th>
                <th>Recurring</th>
                <th>Student QTY</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- All schedules Info modal -->
    <div class="modal fade" id="all-schedule-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="pclass-all-schedule-table" class="table table-bordered table-hover w-100">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Schedule Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal -->
    <div class="modal fade" id="edit-single-pclass-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Edit Practice Class Schedule:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-single-pclass-form"
                          data-action=""
                          data-action-type="update"
                          data-action-method="post">
                        @csrf
                        @method('PUT')
                        <fieldset class="">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="teacher_id" id="edit-teacherSelect" class="form-select">
                                            <option></option>
                                            <option value="1">Nguyen Van A</option>
                                            <option value="2">Tran Thi B</option>
                                            <option value="3">Ngo Trong C</option>
                                        </select>
                                        <label for="edit-teacherSelect" class="form-label">Teacher</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="practice_room_id" id="edit-roomSelect" class="form-select" required>
                                            <option></option>
                                            <option value="1">601 A1nd kjs k skd ksd k dsk dksj kdj dkjd s</option>
                                            <option value="2">702 A1</option>
                                            <option value="3">802 A1</option>
                                        </select>
                                        <label for="edit-roomSelect" class="form-label">Practice Room</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" name="schedule_date" id="edit-startDate" min="{{ date('Y-m-d') }}" required>
                                        <label for="edit-startDate" class="form-label">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-floating mb-3">
                                        <div class="form-control d-flex justify-content-between" id="edit-sessionSelect">
                                            <div class="form-check form-check-inline me-1">
                                                <input class="form-check-input" type="radio" name="session" id="edit-session-1" value="1" required>
                                                <label class="form-check-label" for="edit-session-1"><span class="badge rounded-pill text-bg-success">S</span></label>
                                            </div>
                                            <div class="form-check form-check-inline me-1">
                                                <input class="form-check-input" type="radio" name="session" id="edit-session-2" value="2" required>
                                                <label class="form-check-label" for="edit-session-2"><span class="badge rounded-pill text-bg-primary">C</span></label>
                                            </div>
                                            <div class="form-check form-check-inline me-1">
                                                <input class="form-check-input" type="radio" name="session" id="edit-session-3" value="3" required>
                                                <label class="form-check-label" for="edit-session-3"><span class="badge rounded-pill text-bg-danger">T</span></label>
                                            </div>
                                        </div>
                                        <label for="edit-sessionSelect" class="form-label">Session</label>
                                    </div>
                                    <style>
                                        #sessionSelect .form-check-inline {
                                            margin-right: 0.6rem;
                                        }
                                    </style>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating mb-3">
                                        <input type="number" id="edit-repeatLimit" class="form-control" name="repeat_limit" min="1" max="20" disabled>
                                        <label for="edit-repeatLimit" class="form-label">Repeat</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="edit-single-pclass-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-pclass-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delete-modal-title">
                        Delete practice class: <span id="delete-modal-pclass-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delete-pclass-form"
                          data-action=""
                          data-action-type="delete"
                          data-action-method="post">
                        @csrf
                        <fieldset class="">
                            <input type="hidden" name="_method" value="delete">
                            <div class="row">
                                <p>Any associated schedules and student registrations will also be deleted.</p>
                                <div class="mb-3">
                                    <label for="pclass-confirm-delete">
                                        Enter "<b><i>delete</i></b>" in the field below to proceed
                                    </label>
                                    <input type="text" name="confirmDelete" class="form-control" id="pclass-confirm-delete">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-pclass-form" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        $(document).ready(function () {
            //Data table initiate
            const pclassTable = $('#pclass-management-table').DataTable({
                ajax: {
                    url: '{{route('practice-classes.get-json-data')}}',
                    dataSrc: ''
                },
                columns: [
                    { data: 'index',  width: '5%'},
                    { data: 'practice_class_name', type: 'string', width: '15%', orderable: false },
                    { data: 'start_date', type: 'string', width: '10%' },
                    { data: 'end_date', type: 'string', width: '10%' },
                    { data: 'session', width: '5%', orderable: false },
                    { data: 'practice_room.room_info',
                        type: 'html', width: '10%', orderable: false
                    },
                    { data: 'teacher', type: 'html', width: '15%', orderable: false },
                    { data: 'recurring_interval', type: 'html', width: '10%', orderable: false },
                    { data: 'registered_qty', type: 'html', width: '10%' },
                    { data: 'actions', type: 'html', width: '5%', orderable: false },
                ],
                columnDefs: [{
                    "className": "dt-center",
                    "targets": [0, 2, 3, 4, 5, 6, 7, 8, 9]
                }],
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
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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

            // Create p-class form
            $('#add-pclass-form-toggle').click(function() {
                $('#new-pclass-form-wrapper').slideToggle(400, 'linear');
            });

            $('#recurringSelect').change(function () {
                if ($(this).val() !== '0') {
                    $('#repeatLimit').prop('disabled', false).val(1);
                } else {
                    $('#repeatLimit').prop('disabled', true);
                }
            });

            const newPracticeClassForm = $('#new-pclass-form');
            setupAjaxForm(newPracticeClassForm);

            const infoModal = new bootstrap.Modal('#all-schedule-modal');
            const pClassAllScheduleTable = $('#pclass-all-schedule-table');

            pclassTable.on('click', '.schedule-info-btn', function () {
                if ($.fn.DataTable.isDataTable(pClassAllScheduleTable)) {
                    pClassAllScheduleTable.DataTable().destroy();
                }
                pClassAllScheduleTable.DataTable({
                    ajax: {
                        url: $(this).data('get-url'),
                        dataSrc: ''
                    },
                    columns: [
                        {data: 'index', width: '5%'},
                        {data: 'schedule_date', type: 'string', width: '15%'},
                        {data: 'actions', type: 'html', width: '10%'},
                    ],
                    columnDefs: [{
                        "className": "dt-center",
                        "targets": "_all"
                    }],
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
                                        columns: [0, 1]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    exportOptions: {
                                        columns: [0, 1]
                                    }
                                },
                                {
                                    extend: 'print',
                                    exportOptions: {
                                        columns: [0, 1]
                                    }
                                }
                            ]
                        },
                        bottomStart: {},
                        bottomEnd: {}
                    }
                });
                infoModal.show();
            });

            // Edit practice class schedule modal
            const editSinglePclassModal = new bootstrap.Modal('#edit-single-pclass-modal');
            const editSinglePclassForm = $('#edit-single-pclass-form');

            $(document).on('click', '.pclass-single-edit-btn', function () {
                const data = pClassAllScheduleTable.DataTable().row($(this).parents('tr')).data();

                let updateURL = "{{ route('practice-classes.update', ['practice_class' => ':id'])}}";
                updateURL = updateURL.replace(':id', data.DT_RowId);
                editSinglePclassForm.data('action', updateURL);

                editSinglePclassModal.show();
            });
            setupAjaxForm(editSinglePclassForm);
            //

            // Delete practice class schedule modal
            const deletePclassModal = new bootstrap.Modal('#delete-pclass-modal')
            const deletePclassForm = $('#delete-pclass-form');
            pclassTable.on('click', '.pclass-delete-btn', function () {
                const data = pclassTable.row($(this).parents('tr')).data();

                let deleteURL = "{{ route('practice-classes.destroy', ['practice_class' => ':id'])}}";
                deleteURL = deleteURL.replace(':id', data.DT_RowId);
                deletePclassForm.data('action', deleteURL);

                deletePclassModal.show();
            });
            setupAjaxForm(deletePclassForm);
            //
        })
    </script>
@endsection
