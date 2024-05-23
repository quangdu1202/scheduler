@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body">
        <h1 class="h2">Practice Rooms Management</h1>
    </div>

    <div class="top-nav nav mb-3 d-flex align-items-center">
        <!-- Action Buttons (Add new, etc.) -->
        <div class="action-buttons">
            <button id="add-room-form-toggle" class="btn btn-primary btn-sm" type="button">
                <i class="lni lni-circle-plus align-middle"></i> Add new
            </button>
        </div>
    </div>

    <!-- Create form -->
    <div id="new-room-form-wrapper" class="new-form-hidden border border-primary col-12">
        <form id="new-room-form"
              class="p-3"
              data-action="{{route('practice-rooms.store')}}"
              data-action-type="create"
              data-action-method="post">
            @csrf
            <fieldset class="">
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="room-name" placeholder="Room Name" required>
                            <label for="room-name" class="form-label">Room Name</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="location" id="room-location" placeholder="Room Location" required>
                            <label for="room-location" class="form-label">Room Location</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pc_qty" id="room-pc-qty" placeholder="PC Qty" required>
                            <label for="room-pc-qty" class="form-label">PC Qty</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <select name="status"
                                    id="room-status-select"
                                    class="form-select text-bg-success"
                                    onchange="this.className='form-select' + ' ' + this.options[this.selectedIndex].getAttribute('data-bg-color')"
                                    required>
                                <option value="1" class="text-bg-light" data-bg-color="text-bg-success"><span class="badge text-bg-success">Available</span></option>
                                <option value="2" class="text-bg-light" data-bg-color="text-bg-warning"><span class="badge text-bg-warning">In use</span></option>
                                <option value="3" class="text-bg-light" data-bg-color="text-bg-dark"><span class="badge text-bg-dark">Not available</span></option>
                            </select>
                            <label for="room-status-select" class="form-label">Status</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-dark" id="create-room-btn">Create</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- Practice Room Table -->
    <div class="table-responsive">
        <table id="room-management-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-start ps-3">Room Name</th>
                    <th class="text-center ps-3">Location</th>
                    <th class="text-center">PC Qty</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Edit modal -->
    <div class="modal fade" id="edit-room-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Edit Room:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-room-form"
                          class="p-3"
                          data-action=""
                          data-action-type="update"
                          data-action-method="post">
                        @csrf
                        @method('PUT')
                        <fieldset class="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="name" id="edit-room-name" placeholder="Room Name" required>
                                        <label for="edit-room-name" class="form-label">Room Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="location" id="edit-room-location" placeholder="Room Location" required>
                                        <label for="edit-room-location" class="form-label">Room Location</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="pc_qty" id="edit-room-pc-qty" placeholder="PC Qty" required>
                                        <label for="edit-room-pc-qty" class="form-label">PC Qty</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="status"
                                                id="edit-room-status"
                                                class="form-select"
                                                onchange="this.className='form-select' + ' ' + this.options[this.selectedIndex].getAttribute('data-bg-color')"
                                                required>
                                            <option value="1" class="text-bg-light" data-bg-color="text-bg-success"><span class="badge text-bg-success">Available</span></option>
                                            <option value="2" class="text-bg-light" data-bg-color="text-bg-warning"><span class="badge text-bg-warning">In use</span></option>
                                            <option value="3" class="text-bg-light" data-bg-color="text-bg-dark"><span class="badge text-bg-dark">Not available</span></option>
                                        </select>
                                        <label for="edit-room-status" class="form-label">Status</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="edit-room-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-room-modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Delete room: <span id="delete-modal-room-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delete-room-form"
                          class="p-3"
                          data-action=""
                          data-action-type="delete"
                          data-action-method="post">
                        @csrf
                        <fieldset class="">
                            <input type="hidden" name="_method" value="delete">
                            <div class="row">
                                <p>Delete this practice room?</p>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-room-form" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        $(document).ready(function() {
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

            // Create room form
            $('#add-room-form-toggle').click(function() {
                $('#new-room-form-wrapper').slideToggle(400, 'linear');
            });

            const newRoomForm = $('#new-room-form');
            setupAjaxForm(newRoomForm);
            //

            // Edit room modal
            const editRoomModal = new bootstrap.Modal('#edit-room-modal', {backdrop: true});
            const editRoomForm = $('#edit-room-form');
            const roomStatusSelect = $('#edit-room-status');
            roomTable.on('click', '.room-edit-btn', function () {
                const data = roomTable.row($(this).parents('tr')).data();

                $('#edit-room-name').val(data.name);
                $('#edit-room-location').val(data.location);
                $('#edit-room-pc-qty').val(data.pc_qty);
                roomStatusSelect.val(data.raw_status);
                roomStatusSelect.removeClass().addClass('form-select').addClass(roomStatusSelect.find('option:selected').data('bg-color'));

                let updateURL = "{{ route('practice-rooms.update', ['practice_room' => ':id'])}}";
                updateURL = updateURL.replace(':id', data.DT_RowId);
                editRoomForm.data('action', updateURL);

                editRoomModal.show();
            });
            setupAjaxForm(editRoomForm);
            //

            // Delete room modal
            const deleteRoomModal = new bootstrap.Modal('#delete-room-modal', {backdrop: true})
            const deleteRoomForm = $('#delete-room-form');
            roomTable.on('click', '.room-delete-btn', function () {
                const data = roomTable.row($(this).parents('tr')).data();
                $('#delete-modal-room-name').text(data.name);

                let deleteURL = "{{ route('practice-rooms.destroy', ['practice_room' => ':id'])}}";
                deleteURL = deleteURL.replace(':id', data.DT_RowId);
                deleteRoomForm.data('action', deleteURL);

                deleteRoomModal.show();
            });
            setupAjaxForm(deleteRoomForm);
            //
        });
    </script>
@endsection
