@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Component Points by Practice Class</h1>
        </div>

        <div class="top-nav nav mb-3 d-flex justify-content-between">
            <form action="#" class="d-flex align-items-center">
                <label for="module-select" class="me-2 text-nowrap fw-bold">Module Class:</label>
                <select name="module-select" id="module-select" class="form-select">
                    <option value="1">202320503196001</option>
                    <option value="2">202320503197001</option>
                    <option value="3">20224IT6030002</option>
                    <option value="4">20231LP6013037</option>
                </select>
            </form>
            <!-- Button Group for Export and Import -->
            <div class="nav-buttons">
{{--                <button type="button" class="btn btn-danger">Export PDF</button>--}}
                <button type="button" class="btn btn-primary">Import CSV</button>
            </div>
        </div>

        <!-- Points Table -->
        <div class="table-responsive">
            <table id="student-mark-practice-table" class="table table-bordered table-hover w-100 text-center">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>MSV</th>
                    <th>Full Name</th>
                    <th>TX1</th>
                    <th>TX2</th>
                    <th>GK</th>
                    <th>CK</th>
                </tr>
                </thead>
                <tbody>
                @for($i = 1; $i <= 33; $i++)
                    <tr data-student-id="{{$i}}">
                        <td>{{$i}}</td>
                        <td>20206045{{$i}}</td>
                        <td class="text-start">FUll Name {{$i}}</td>
                        <td>{{ number_format(lcg_value() * 10, 2) }}</td>
                        <td>{{ number_format(lcg_value() * 10, 2) }}</td>
                        <td>{{ number_format(lcg_value() * 10, 2) }}</td>
                        <td>{{ number_format(lcg_value() * 10, 2) }}</td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#student-mark-practice-table').DataTable({
                layout: {
                    topEnd: {
                        search: {placeholder: 'Search'},
                        buttons: ['length', 'csv', 'excel', 'print']
                    },
                },
                "pageLength": -1,
                "columnDefs": [
                    {"className": "dt-center", "targets": "_all"}
                ],
                columns: [{ width: '35px' }, { width: '130px' }, null, { width: '120px' }, { width: '150px' }, { width: '120px' }, { width: '160px' }],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ students",
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
                        '</select> students per page'
                }
            });
        });
    </script>
@endsection
