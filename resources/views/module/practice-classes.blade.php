@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <span class="fs-2">{{ $module->module_name }}</span>
            <div class="vr mx-3"></div>
            <span class="fs-4 text-muted">Practice Classes</span>
        </div>

        <div class="top-nav nav mb-3 d-flex align-items-center">
            <!-- Action Buttons (Add new, etc.) -->
            <div class="action-buttons">
                <a href="{{ route('practice-classes.create') }}" id="add-class-new" class="btn btn-primary btn-sm"
                    type="button">
                    <i class="lni lni-circle-plus align-middle"></i> Add new
                </a>
            </div>
        </div>

        <!-- Practice Classes Table -->
        <div class="table-responsive">
            <table id="module-management-table" class="table table-bordered table-hover w-100 text-center">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Module ID</th>
                        <th>Practice Class Name</th>
                        <th>Schedule Date</th>
                        <th>Session</th>
                        <th>Practice Room</th>
                        <th>Teacher</th>
                        <th>Recurring</th>
                        <th>QTY</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practiceClasses as $key => $practiceClass)
                        <tr data-pclass-id="{{ $practiceClass->id }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $practiceClass->module_id }}</td>
                            <td>{{ $practiceClass->practice_class_name }}</td>
                            <td>{{ $practiceClass->schedule_date }}</td>
                            <td>{{ $practiceClass->session }}</td>
                            <td>{{ $practiceClass->practice_room_id }}</td>
                            <td>{{ $practiceClass->teacher_id }}</td>
                            <td>{{ $practiceClass->recurring_id }}</td>
                            <td>{{ $practiceClass->registered_qty }}</td>
                            <td>
                                <button type="button" class="table-row-btn module-btn-info btn btn-success btn-sm"
                                    data-pclass-id="{{ $practiceClass->id }}" title="Module Info">
                                    <i class="fa-solid fa-building-circle-exclamation"></i>
                                </button>
                                <button type="button" class="table-row-btn module-btn-edit btn btn-primary btn-sm"
                                    data-pclass-id="{{ $practiceClass->id }}" title="Edit Module Info"
                                    style="padding-right: 0.45rem">
                                    <i style="padding-left: 0.05rem" class="lni lni-pencil-alt"></i>
                                </button>
                                <button type="button" class="table-row-btn module-btn-delete btn btn-danger btn-sm"
                                    data-pclass-id="{{ $practiceClass->id }}" title="Delete Module">
                                    <i class="lni lni-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#module-management-table').DataTable({
                layout: {
                    topEnd: {
                        search: {
                            placeholder: 'Search'
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
                columnDefs: [{
                        "className": "dt-center",
                        "targets": "_all",
                    },
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ],
                // columns: [{ width: '35px' }, { width: '130px' }, null, { width: '120px' }, { width: '150px' }, { width: '120px' }],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ practice classes",
                    //customize pagination prev and next buttons: use arrows instead of words
                    'paginate': {
                        'previous': '<span class="fa fa-chevron-left"></span>',
                        'next': '<span class="fa fa-chevron-right"></span>'
                    },
                    //customize number of elements to be displayed
                    "lengthMenu": '<select class="form-control input-sm">' +
                        '<option value="-1">All</option>' +
                        '<option value="10">10</option>' +
                        '<option value="20">20</option>' +
                        '<option value="30">30</option>' +
                        '<option value="40">40</option>' +
                        '<option value="50">50</option>' +
                        '</select> practice classes per page'
                }
            });
        });
    </script>
@endsection
