@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="py-3 mb-3 border-bottom">
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
                                <select name="module_id" id="moduleSelect" class="form-select" data-place required>
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
                        <div class="col-1">
                            <div class="form-floating mb-3">
                                <input name="pclass_qty" id="pclass-qty" class="form-control" type="number" min="1">
                                <label for="pclass-qty" class="form-label">Multiple</label>
                            </div>
                        </div>
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
                    <th>Schedule Date</th>
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

    </div>

    <script>
        $(document).ready(function () {
            //Data table initiate
            const roomTable = $('#pclass-management-table').DataTable({
                ajax: {
                    url: '{{route('practice-classes.get-json-data')}}',
                    dataSrc: ''
                },
                columns: [
                    { data: 'index',  width: '5%'},
                    { data: 'practice_class_name', type: 'string', width: '20%' },
                    { data: 'schedule_date', type: 'string', width: '15%' },
                    { data: 'session', width: '10%' },
                    { data: 'practice_room.room_info',
                        type: 'html', width: '10%'
                    },
                    { data: 'teacher', type: 'html', width: '10%' },
                    { data: 'recurring_interval', type: 'html', width: '10%' },
                    { data: 'registered_qty', type: 'html', width: '10%' },
                    { data: 'actions', type: 'html', width: '10%' },
                ],
                columnDefs: [{
                    "className": "dt-center",
                    "targets": [0, 2, 3, 4, 5, 6, 7, 8]
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

            $('#moduleSelect').select2({
                theme: "bootstrap-5",
                placeholder: "Select a module",
            });
            $('#teacherSelect').select2({
                theme: "bootstrap-5",
                placeholder: "Select a teacher (not required)",
            });
            $('#roomSelect').select2({
                theme: "bootstrap-5",
                placeholder: "Select a practice room",
            });
            $('#recurringSelect').select2({
                theme: "bootstrap-5",
                placeholder: "Repeat interval",
            });

            $('#recurringSelect').change(function () {
                if ($(this).val() !== '0') {
                    $('#repeatLimit').prop('disabled', false);
                } else {
                    $('#repeatLimit').prop('disabled', true);
                }
            });

            const newPracticeClassForm = $('#new-pclass-form');
            setupAjaxForm(newPracticeClassForm);
        })
    </script>
@endsection
