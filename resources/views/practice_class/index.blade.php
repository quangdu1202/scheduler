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
                <button id="add-room-form-toggle" class="btn btn-primary btn-sm" type="button">
                    <i class="lni lni-circle-plus align-middle"></i> Add new
                </button>
            </div>
            <div class="vr mx-5"></div>
            <form id="practice-class-filter" action="#" class="d-flex align-items-center">
                <label for="practice-room-select" class="me-2 text-nowrap fw-bold">Module:</label>
                <select name="practice-room" id="practice-room-select" class="form-select">
                    <option value="-1" selected>--- Select Module ---</option>
                    <option value="1">Nhập môn lập trình máy tính</option>
                    <option value="2">Kỹ thuật lập trình</option>
                    <option value="3">Cơ sở dữ liệu</option>
                    <option value="4">Kiến trúc máy tính</option>
                </select>
            </form>
        </div>

        <!-- Practice Classes Table -->
        <div class="table-responsive">
            <table id="pclass-management-table" class="table table-bordered table-hover w-100 text-center">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Class Name</th>
                    <th>Start Date</th>
                    <th>Session</th>
                    <th>Room</th>
                    <th>Teacher</th>
                    <th>Recurring</th>
                    <th>QTY</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        //Data table initiate
        const roomTable = $('#room-management-table').DataTable({
            ajax: {
                url: '{{route('practice-rooms.get-json-data')}}',
                dataSrc: ''
            },
            columns: [
                { data: 'index',  width: '5%'},
                { data: 'name', type: 'string', width: '30%' },
                { data: 'location', type: 'string', width: '20%' },
                { data: 'pc_qty', width: '15%' },
                { data: 'status', type: 'html', width: '15%' },
                { data: 'actions', type: 'html', width: '15%' },
            ],
            columnDefs: [{
                "className": "dt-center",
                "targets": [0, 2, 3, 4, 5]
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
                "info": "Showing _START_ to _END_ of _TOTAL_ rooms",
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
                    '</select> rooms per page'
            }
        });
    </script>
@endsection
