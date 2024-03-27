@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Modules Management</h1>
        </div>

        <div class="top-nav nav mb-3 d-flex justify-content-between">
            <form action="#" class="d-flex align-items-center">
                <label for="module-select" class="me-2 text-nowrap fw-bold">Module:</label>
                <select name="module-select" id="module-select" class="form-select">
                    <option value="1">Nhập môn lập trình máy tính</option>
                    <option value="2">Kỹ thuật lập trình</option>
                    <option value="3">Cơ sở dữ liệu</option>
                    <option value="4">Kiến trúc máy tính</option>
                    <option value="-1" selected>All</option>
                </select>
            </form>
            <!-- Button Group for Export and Import -->
            <div class="nav-buttons">
                {{--<button type="button" class="btn btn-danger">Export PDF</button>--}}
                <button type="button" class="btn btn-primary">Import CSV</button>
            </div>
        </div>

        <!-- Points Table -->
        <div class="table-responsive">
            <table id="module-management-table" class="table table-bordered table-hover w-100 text-center">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Module ID</th>
                    <th>Module Name</th>
                    <th>Type</th>
                    <th>Teacher</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @for($i = 1; $i <= 33; $i++)
                    <tr data-module-id="{{$i}}">
                        <td>{{$i}}</td>
                        <td>20206045{{$i}}</td>
                        <td class="text-start">FUll Name {{$i}}</td>
                        <td>{{ 'abc' }}</td>
                        <td>{{ 'abc' }}</td>
                        <td>
                            <button type="button" class="table-row-btn module-btn-info btn btn-success btn-sm" data-module-id="{{$i}}" title="Module Info">
                                <i class="fa-solid fa-building-circle-exclamation"></i>
                            </button>
                            <button type="button" class="table-row-btn module-btn-edit btn btn-primary btn-sm" data-module-id="{{$i}}" title="Edit Module Info" style="padding-right: 0.45rem">
                                <i style="padding-left: 0.05rem" class="lni lni-pencil-alt"></i>
                            </button>
                            <button type="button" class="table-row-btn module-btn-delete btn btn-danger btn-sm" data-module-id="{{$i}}" title="Delete Module">
                                <i class="lni lni-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#module-management-table').DataTable({
                layout: {
                    topEnd: {
                        search: {placeholder: 'Search'},
                        buttons: ['length', 'csv', 'excel', 'print']
                    },
                },
                pageLength: -1,
                columnDefs: [
                    {"className": "dt-center", "targets": "_all"}
                ],
                columns: [{ width: '35px' }, { width: '130px' }, null, { width: '120px' }, { width: '150px' }, { width: '120px' }],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ modules",
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
                        '</select> modules per page'
                }
            });
        });
    </script>
@endsection
