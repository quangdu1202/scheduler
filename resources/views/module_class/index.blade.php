@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom">
        <h1 class="h2">Module Classes Management</h1>
    </div>

    <div class="top-nav nav mb-3 d-flex align-items-center">
        <!-- Action Buttons (Add new, etc.) -->
        <div class="action-buttons">
            <button id="add-mclass-form-toggle" class="btn btn-primary btn-sm" type="button">
                <i class="lni lni-circle-plus align-middle"></i> Add new
            </button>
        </div>
        <div class="vr mx-5"></div>
    </div>

    <!-- Create form -->
    <div id="new-mclass-form-wrapper" class="new-form-hidden border border-primary col-12">
        <form id="new-mclass-form"
              class="p-3"
              data-action="{{route('module-classes.store')}}"
              data-action-type="create"
              data-action-method="post">
            @csrf
            <fieldset class="">
                <div class="row">
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <select name="module_id" id="moduleSelect" class="form-select" required>
                                <option></option>
                                @foreach($modules as $module)
                                    <option value="{{$module->id}}">{{$module->module_name . ' (' . $module->module_code . ')'}}</option>
                                @endforeach
                            </select>
                            <label for="moduleSelect" class="form-label">Module</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="module_class_code" id="moduleClassCode" placeholder="Module Class Code" required>
                            <label for="moduleClassCode" class="form-label">Module Class Code</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="module_class_name" id="moduleClassName" placeholder="Module Class Name" required>
                            <label for="moduleClassName" class="form-label">Module Class Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="start_date" id="moduleClassStartDate">
                            <label for="moduleClassStartDate" class="form-label">Start Date</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="end_date" id="moduleClassEndDate">
                            <label for="moduleClassEndDate" class="form-label">End Date</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <select name="teacher_id" id="moduleClassTeacher" class="form-select">
                                <option></option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                @endforeach
                            </select>
                            <label for="moduleClassTeacher" class="form-label">Teacher</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="student_qty" id="moduleClassStudentQty" min="0" required>
                            <label for="moduleClassStudentQty" class="form-label">Student Qty</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <select name="status"
                                    id="moduleClassStatus"
                                    class="form-select"
                                    onchange="this.className='form-select' + ' ' + this.options[this.selectedIndex].getAttribute('data-bg-color')"
                                    required>
                                <option value="1" class="text-bg-light" data-bg-color="text-bg-success"><span class="badge text-bg-success">Active</span></option>
                                <option value="0" class="text-bg-light" data-bg-color="text-bg-warning"><span class="badge text-bg-dark">Inactive</span></option>
                            </select>
                            <label for="moduleClassStatus" class="form-label">Status</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-dark" id="create-mclass-btn">Create</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- Module Class Table -->
    <div class="table-responsive">
        <table id="mclass-management-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Module Class Code</th>
                    <th>Module Class Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Teacher</th>
                    <th>Student Qty</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Edit modal -->
    <div class="modal fade" id="edit-mclass-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Edit module class: <span id="edit-modal-mclass-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-mclass-form"
                          class="p-3"
                          data-action=""
                          data-action-type="update"
                          data-action-method="post">
                        @csrf
                        @method('PUT')
                        <fieldset class="">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="module_id" id="edit-ModuleSelect" class="form-select" required>
                                            <option></option>
                                            @foreach($modules as $module)
                                                <option value="{{$module->id}}">{{$module->module_name . ' (' . $module->module_code . ')'}}</option>
                                            @endforeach
                                        </select>
                                        <label for="edit-ModuleSelect" class="form-label">Module</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="module_class_code" id="edit-ModuleClassCode" placeholder="Module Class Code" required>
                                        <label for="edit-ModuleClassCode" class="form-label">Module Class Code</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="module_class_name" id="edit-ModuleClassName" placeholder="Module Class Name" required>
                                        <label for="edit-ModuleClassName" class="form-label">Module Class Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" name="start_date" id="edit-ModuleClassStartDate">
                                        <label for="edit-ModuleClassStartDate" class="form-label">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" name="end_date" id="edit-ModuleClassEndDate">
                                        <label for="edit-ModuleClassEndDate" class="form-label">End Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="teacher_id" id="edit-ModuleClassTeacher" class="form-select">
                                            <option></option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="edit-ModuleClassTeacher" class="form-label">Teacher</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="student_qty" id="edit-ModuleClassStudentQty" min="0" required>
                                        <label for="edit-ModuleClassStudentQty" class="form-label">Student Qty</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="status"
                                                id="edit-ModuleClassStatus"
                                                class="form-select"
                                                onchange="this.className='form-select' + ' ' + this.options[this.selectedIndex].getAttribute('data-bg-color')"
                                                required>
                                            <option value="1" class="text-bg-light" data-bg-color="text-bg-success"><span class="badge text-bg-success">Active</span></option>
                                            <option value="0" class="text-bg-light" data-bg-color="text-bg-warning"><span class="badge text-bg-dark">Inactive</span></option>
                                        </select>
                                        <label for="edit-ModuleClassStatus" class="form-label">Status</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="edit-mclass-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-mclass-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Delete module class: <span id="delete-modal-mclass-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delete-mclass-form"
                          class="p-3"
                          data-action=""
                          data-action-type="delete"
                          data-action-method="post">
                        @csrf
                        <fieldset class="">
                            <input type="hidden" name="_method" value="delete">
                            <div class="row">
                                <p>Delete this module class?</p>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-mclass-form" class="btn btn-primary">Delete</button>
                    <span class="mx-2">or</span>
                    <button type="button" class="btn btn-dark">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        $(document).ready(function() {
            $('form select').not('#moduleClassStatus, #edit-ModuleClassStatus').select2({
                theme: "bootstrap-5",
                placeholder: "Select an option",
            });

            //Data table initiate
            const mclassTable = $('#mclass-management-table').DataTable({
                ajax: {
                    url: '{{route('module-classes.get-json-data')}}',
                    dataSrc: ''
                },
                columns: [
                    { data: 'index',  width: '5%'},
                    { data: 'module_class_code', type: 'string', width: '15%' },
                    { data: 'module_class_name', type: 'string', width: '20%' },
                    { data: 'start_date', type: 'string', width: '10%' },
                    { data: 'end_date', type: 'string', width: '10%' },
                    { data: 'teacher', type: 'string', width: '15%' },
                    { data: 'student_qty', type: 'number', width: '10%' },
                    { data: 'status', type: 'html', width: '10%',
                        render: function(data, type, row) {
                            return `
                                <div class="cell-clamp" title="${data.title}">
                                    ${data.value}
                                </div>
                            `;
                        }
                    },
                    { data: 'actions', type: 'html', width: '5%' },
                ],
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: [0, 3, 4, 6, 7, 8]
                    },
                    {
                        className: "dt-head-center",
                        targets: '_all'
                    },
                    {
                        orderable: false,
                        targets: [1, 2, 5, 7, 8]
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
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
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

            // Update the module class status
            mclassTable.on('change', '.status-change-btn', function(e) {
                // e.preventDefault();
                const status = $(this).is(':checked') ? 1 : 0;
                const mclassId = $(this).data('mclass-id');
                const $row = $(this).closest('tr'); // Get the closest row (<tr>) element
                const rowData = mclassTable.row($row).data(); // Get the data for this row

                // Show the loading overlay
                showOverlay();

                $.ajax({
                    url: '{{route('module-classes.update-mclass-status')}}',
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        status: status,
                        mclassId: mclassId
                    },
                    success: function(response) {
                        // Hide the loading overlay
                        hideOverlay();

                        console.log(response);
                        switch (response.status) {
                            case 200:
                                toastr.success(response.message, response.title || "Success");
                                // Update the row data here if needed
                                rowData.status = response.newStatus; // Assume response contains new status
                                mclassTable.row($row).data(rowData).invalidate().draw(false); // Invalidate the data cache
                                break;
                            default:
                                toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        toastr.error("A server error occurred. Please try again.", "Error");
                    }
                });
            });
            //

            // Create module class form
            $('#add-mclass-form-toggle').click(function() {
                $('#new-mclass-form-wrapper').slideToggle(400, 'linear');
            });
            const newModuleClassForm = $('#new-mclass-form');
            setupAjaxForm(newModuleClassForm);

            // Edit module class modal
            const editModuleClassModal = new bootstrap.Modal('#edit-mclass-modal', {backdrop: true})
            const editModuleClassForm = $('#edit-mclass-form');
            const moduleClassStatusSelect = $('#edit-ModuleClassStatus');
            mclassTable.on('click', '.mclass-edit-btn', function () {
                const data = mclassTable.row($(this).parents('tr')).data();

                $('#edit-ModuleSelect').val(data.DT_RowData.module_id).change();
                $('#edit-ModuleClassCode').val(data.module_class_code);
                $('#edit-ModuleClassName').val(data.module_class_name);
                $('#edit-ModuleClassStartDate').val(data.start_date);
                $('#edit-ModuleClassEndDate').val(data.end_date);
                $('#edit-ModuleClassTeacher').val(data.DT_RowData.teacher_id).change();
                $('#edit-ModuleClassStudentQty').val(data.student_qty);
                moduleClassStatusSelect.val(data.DT_RowData.status);
                moduleClassStatusSelect.removeClass().addClass('form-select').addClass(moduleClassStatusSelect.find('option:selected').data('bg-color'));

                let updateURL = $(this).data('post-url');
                editModuleClassForm.data('action', updateURL);

                editModuleClassModal.show();
            });
            setupAjaxForm(editModuleClassForm);
            //

            // Delete module class modal
            const deleteModuleClassModal = new bootstrap.Modal('#delete-mclass-modal', {backdrop: true})
            const deleteModuleClassForm = $('#delete-mclass-form');
            mclassTable.on('click', '.mclass-delete-btn', function () {
                const data = mclassTable.row($(this).parents('tr')).data();
                $('#delete-modal-mclass-name').text(data.module_class_name);

                let deleteURL = $(this).data('post-url');
                deleteModuleClassForm.data('action', deleteURL);

                deleteModuleClassModal.show();
            });
            setupAjaxForm(deleteModuleClassForm);
            //
        });
    </script>
@endsection
