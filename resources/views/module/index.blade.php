@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Practice Class Management</h1>
        </div>

{{--        @if ($success == true)--}}
{{--            <div class="alert alert-success" role="alert">--}}
{{--                {{ $message }}--}}
{{--            </div>--}}
{{--        @elseif ($success == false)--}}
{{--            <div class="alert alert-danger" role="alert">--}}
{{--                {{ $message }}--}}
{{--            </div>--}}
{{--        @endif--}}

        <div class="top-nav nav mb-3 d-flex align-items-center">
            <!-- Action Buttons (Add new, etc.) -->
            <div class="action-buttons">
                <button href="{{ route('modules.create') }}" id="add-module-new" class="btn btn-primary btn-sm" type="button">
                    <i class="lni lni-circle-plus align-middle"></i> Add new
                </button>
            </div>
            <div class="vr mx-5"></div>
            <form id="practice-class-filter" action="#" class="d-flex align-items-center">
                <label for="module-select" class="me-2 text-nowrap fw-bold">Module:</label>
                <select name="module" id="module-select" class="form-select">
                    <option value="-1" selected>--- Select Module ---</option>
                    <option value="1">Nhập môn lập trình máy tính</option>
                    <option value="2">Kỹ thuật lập trình</option>
                    <option value="3">Cơ sở dữ liệu</option>
                    <option value="4">Kiến trúc máy tính</option>
                </select>
            </form>
        </div>

        <!-- Create form -->
        <div id="new-module-form" class="new-form border border-primary">
            <form action="{{ route('modules.store') }}" method="post" class="p-3">
                @csrf
                <fieldset class="">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="module_code" id="moduleCode"
                                       value="{{ isset($oldData) ? $oldData['module_code'] : '' }}"
                                       placeholder="Module Code" required>
                                <label for="moduleCode" class="form-label">Module Code</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="module_name" id="moduleName"
                                       value="{{ isset($oldData) ? $oldData['module_name'] : '' }}"
                                       placeholder="Module Name" required>
                                <label for="moduleName" class="form-label">Module Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-sm btn-dark" type="submit">Create</button>
                            <a href="{{ route('modules.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <!-- Module Table -->
        <div class="table-responsive">
            <table id="module-management-table" class="table table-bordered table-hover w-100">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-start ps-3">Module Code</th>
                        <th class="text-start ps-3">Module Name</th>
                        <th class="text-center">Practice Class QTY</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $key => $module)
                        <tr data-pclass-id="{{ $module->id }}">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-start ps-3">{{ $module->module_code }}</td>
                            <td class="text-start ps-3">{{ $module->module_name }}</td>
                            <td class="text-center">{{ count($module->practiceClasses) }}</td>
                            <td class="text-center">
                                <a href="{{ route('modules.show-practice-classes', $module) }}"
                                    class="table-row-btn module-btn-info btn btn-success btn-sm" title="Module Info">
                                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                                </a>
                                <a href="{{ route('modules.edit', $module) }}"
                                    class="table-row-btn module-btn-edit btn btn-primary btn-sm" title="Edit Module Info">
                                    <i class="lni lni-pencil-alt align-middle"></i>
                                </a>
                                <form action="{{ route('modules.destroy', $module) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-row-btn module-btn-delete btn btn-danger btn-sm"
                                        title="Delete Module"
                                        onclick="return confirm('Are you sure you want to delete this module?')">
                                        <i class="lni lni-trash-can align-middle"></i>
                                    </button>
                                </form>
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
                                    columns: [0, 1, 2, 3]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                }
                            }
                        ]
                    },
                },
                pageLength: -1,
                columnDefs: [{
                    "className": "dt-center",
                    "targets": 0
                }],
                columns: [{
                    width: '5%'
                }, {
                    width: '15%',
                }, {
                    width: '50%'
                }, {
                    width: '15%'
                }, {
                    width: '15%'
                }],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ modules",
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
                        '</select> modules per page'
                }
            });

            $('#add-module-new').click(function () {
                $('#new-module-form').slideToggle(400, 'linear');
            })
        });
    </script>
@endsection
