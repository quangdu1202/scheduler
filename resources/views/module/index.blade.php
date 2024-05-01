@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom">
        <h1 class="h2">Modules Management</h1>
    </div>

    <div class="top-nav nav mb-3 d-flex align-items-center">
        <!-- Action Buttons (Add new, etc.) -->
        <div class="action-buttons">
            <button id="add-module-form-toggle" class="btn btn-primary btn-sm" type="button">
                <i class="lni lni-circle-plus align-middle"></i> Add new
            </button>
        </div>
        <div class="vr mx-5"></div>
    </div>

    <!-- Create form -->
    <div id="new-module-form-wrapper" class="new-form-hidden border border-primary col-6">
        <form id="new-module-form"
              class="p-3"
              data-action="{{route('modules.store')}}"
              data-action-type="create"
              data-action-method="post">
            @csrf
            <fieldset class="">
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="module_code" id="moduleCode" placeholder="Module Code" required>
                            <label for="moduleCode" class="form-label">Module Code</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="module_name" id="moduleName" placeholder="Module Name" required>
                            <label for="moduleName" class="form-label">Module Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-dark" id="create-module-btn">Create</button>
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
                    <th class="text-center">Module Class QTY</th>
                    <th class="text-center">Practice Class QTY</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Edit modal -->
    <div class="modal fade" id="edit-module-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Edit module: <span id="edit-modal-module-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-module-form"
                          class="p-3"
                          data-action=""
                          data-action-type="update"
                          data-action-method="post">
                        @csrf
                        @method('PUT')
                        <fieldset class="">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="module_code" id="editModuleCode" placeholder="Module Code" required>
                                        <label for="editModuleCode" class="form-label">Module Code</label>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="module_name" id="editModuleName" placeholder="Module Name" required>
                                        <label for="editModuleName" class="form-label">Module Name</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="edit-module-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-module-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Delete module: <span id="delete-modal-module-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delete-module-form"
                          class="p-3"
                          data-action=""
                          data-action-type="delete"
                          data-action-method="post">
                        @csrf
                        <fieldset class="">
                            <input type="hidden" name="_method" value="delete">
                            <div class="row">
                                <p>Delete this module?</p>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-module-form" class="btn btn-primary">Delete</button>
                    <span class="mx-2">or</span>
                    <button type="button" class="btn btn-dark">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        $(document).ready(function() {
            //Data table initiate
            const moduleTable = $('#module-management-table').DataTable({
                ajax: {
                    url: '{{route('modules.get-json-data')}}',
                    dataSrc: ''
                },
                columns: [
                    { data: 'index',  width: '5%'},
                    { data: 'module_code', type: 'string', width: '15%' },
                    { data: 'module_name', type: 'string', width: '40%' },
                    { data: 'module_class_qty', width: '15%' },
                    { data: 'practice_class_qty', width: '15%' },
                    { data: 'actions', type: 'html', width: '15%' },
                ],
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: [0, 3, 4, 5]
                    },
                    {
                        orderable: false,
                        targets: [1, 2, 5]
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

            // Create module form
            $('#add-module-form-toggle').click(function() {
                $('#new-module-form-wrapper').slideToggle(400, 'linear');
            });
            const newModuleForm = $('#new-module-form');
            setupAjaxForm(newModuleForm);

            // Edit module modal
            const editModuleModal = new bootstrap.Modal('#edit-module-modal')
            const editModuleForm = $('#edit-module-form');
            moduleTable.on('click', '.module-edit-btn', function () {
                const data = moduleTable.row($(this).parents('tr')).data();
                $('#editModuleCode').val(data.module_code);
                $('#editModuleName').val(data.module_name);

                let updateURL = "{{ route('modules.update', ['module' => ':id'])}}";
                updateURL = updateURL.replace(':id', data.DT_RowId);
                editModuleForm.data('action', updateURL);

                editModuleModal.show();
            });
            setupAjaxForm(editModuleForm);
            //

            // Delete module modal
            const deleteModuleModal = new bootstrap.Modal('#delete-module-modal')
            const deleteModuleForm = $('#delete-module-form');
            moduleTable.on('click', '.module-delete-btn', function () {
                const data = moduleTable.row($(this).parents('tr')).data();
                $('#delete-modal-module-name').text(data.module_name);

                let deleteURL = "{{ route('modules.destroy', ['module' => ':id'])}}";
                deleteURL = deleteURL.replace(':id', data.DT_RowId);
                deleteModuleForm.data('action', deleteURL);

                deleteModuleModal.show();
            });
            setupAjaxForm(deleteModuleForm);
            //
        });
    </script>
@endsection
