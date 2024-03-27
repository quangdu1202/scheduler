@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Computer Rooms Management</h1>
        </div>

        <!-- Action Buttons (Add new, etc.) -->
        <button id="add-room-new" class="btn btn-primary btn-sm mb-3" type="button">
            <i style="vertical-align: middle; padding-bottom: 2px" class="lni lni-circle-plus"></i> Add new
        </button>

        <!-- Computer Room Table -->
        <div class="table-responsive">
            <table id="rooms-table" class="table table-bordered table-hover w-100 text-center">
                <thead class="thead-light">
                <tr>
                    <th style="width: 35px">#</th>
                    <th style="width: 100px">Room ID</th>
                    <th>Room Name</th>
                    <th style="width: 95px;">Comps Qty</th>
                    <th style="width: 120px">Location</th>
                    <th style="width: 120px">Status</th>
                    <th style="width: 160px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @for($i = 1; $i <= 50; $i++)
                    <tr data-room-id="{{$i}}">
                        <td>{{$i}}</td>
                        <td>PM-{{$i}}</td>
                        <td class="text-start">Thực hành công nghệ thông tin {{$i}}</td>
                        <td>{{random_int(25,35)}}</td>
                        <td>{{array_rand(['701', '702', '703', '801', '802', '803', '901', '902', '903'])}} - A1</td>
                        <td>
                            <span class="badge rounded-pill bg-primary">Active</span>
                        </td>
                        <td>
                            <button type="button" class="table-row-btn row-btn-info btn btn-success btn-sm" data-room-id="{{$i}}" title="Room Info">
                                <i class="fa-solid fa-building-circle-exclamation"></i>
                            </button>
                            <button type="button" class="table-row-btn row-btn-edit btn btn-primary btn-sm" data-room-id="{{$i}}" title="Edit Room Info" style="padding-right: 0.45rem">
                                <i style="padding-left: 0.05rem" class="lni lni-pencil-alt"></i>
                            </button>
                            <button type="button" class="table-row-btn row-btn-delete btn btn-danger btn-sm" data-room-id="{{$i}}" title="Delete Room">
                                <i class="lni lni-trash-can"></i>
                            </button>
                        </td>
                        <style>
                            .table-row-btn i{
                                vertical-align: middle;
                            }
                        </style>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <!-- Room Info Modal -->
        <div id="room-info-modal" class="popup-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thông tin đăng kí phòng máy</h5>
                </div>
                <div id="room-info-body" class="modal-body">
                    <!-- Add form content here -->
                </div>
                <div class="modal-footer">
                    <button id="room-info-close" class="btn btn-secondary">Cancel</button>
                    <button id="room-info-save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>

        <!-- Add Room Modal -->
        <div id="add-room-modal" class="popup-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm mới phòng máy</h5>
                </div>
                <div class="modal-body">
                    <!-- Add form content here -->
                </div>
                <div class="modal-footer">
                    <button id="add-room-close" class="btn btn-secondary">Cancel</button>
                    <button id="add-room-save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>

        <!-- Room Edit Modal -->
        <div id="edit-room-modal" class="popup-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Sửa thông tin phòng máy</h5>
                </div>
                <div id="edit-room-body" class="modal-body">
                    <!-- Add form content here -->
                </div>
                <div class="modal-footer">
                    <button id="edit-room-close" class="btn btn-secondary">Cancel</button>
                    <button id="edit-room-save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            $('#rooms-table').DataTable({
                layout: {
                    topEnd: {
                        search: {placeholder: 'Search'},
                        buttons: ['length', 'csv', 'excel', 'print']
                    },
                },
                columnDefs: [
                    { "orderable": false, "targets": [6] },
                    {"className": "dt-center", "targets": "_all"}
                ],
                columns: [{ width: '35px' }, { width: '130px' }, null, { width: '120px' }, { width: '150px' }, { width: '120px' }, { width: '160px' }],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ rooms",
                    //customize pagination prev and next buttons: use arrows instead of words
                    'paginate': {
                        'previous': '<span class="fa fa-chevron-left"></span>',
                        'next': '<span class="fa fa-chevron-right"></span>'
                    },
                    //customize number of elements to be displayed
                    "lengthMenu": '<select class="form-control input-sm">'+
                        '<option value="10">10</option>'+
                        '<option value="20">20</option>'+
                        '<option value="30">30</option>'+
                        '<option value="40">40</option>'+
                        '<option value="50">50</option>'+
                        '<option value="-1">All</option>'+
                        '</select> rooms per page'
                }
            });
        });
    </script>
@endsection
