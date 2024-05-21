@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body">
        <h1 class="h2">Practice Classes Management</h1>
    </div>

    <!-- Action Buttons (Add new, etc.) -->
    <div class="row">
        <div class="input-group">
            <button id="add-pclass-form-toggle" class="btn btn-primary btn-sm" type="button">
                <i class="lni lni-circle-plus align-middle"></i> Add new
            </button>
        </div>
        <!-- Create form -->
        <div id="new-pclass-form-wrapper" class="new-form-hidden border border-primary col-12 m-2">
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
                                <select name="module_id" id="moduleSelect" class="form-select" required>
                                    <option></option>
                                    @foreach($modules as $module)
                                        <option value="{{$module->id}}">{{$module->module_name . ' (' . $module->module_code . ')'}}</option>
                                    @endforeach
                                </select>
                                <label for="moduleSelect" class="form-label">Module</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="form-control form-switch py-3">
                                <input class="form-check-input" name="multi_create" type="checkbox" role="switch"
                                       id="multi-switch">
                                <label class="form-check-label ms-1" for="multi-switch">Multi</label>
                            </div>
                        </div>
                        <div class="col-1 show-for-multi" style="display: none">
                            <div class="form-floating mb-3">
                                <input type="number" name="multi_qty" class="form-control" id="multi-qty" min="2"
                                       disabled
                                       required>
                                <label for="multi-qty" class="form-label">Qty</label>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden-for-multi">
                        <div class="col-3">
                            <div class="form-floating mb-3">
                                <select name="teacher_id" id="teacherSelect" class="form-select">
                                    <option></option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                    @endforeach
                                </select>
                                <label for="teacherSelect" class="form-label">Teacher</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating mb-3">
                                <input type="text" name="practice_class_code" class="form-control" id="classCode"
                                       placeholder="Class Code" required>
                                <label for="classCode" class="form-label">Class Code</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating mb-3">
                                <input type="text" name="practice_class_name" class="form-control" id="className"
                                       placeholder="Class Name" required>
                                <label for="className" class="form-label">Class Name</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="form-floating mb-3">
                                <input type="number" name="max_qty" class="form-control" id="studentQty" min="0">
                                <label for="studentQty" class="form-label">Max Student</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="form-floating mb-3">
                                <input type="number" name="shift_qty" class="form-control" id="shiftQty" min="1"
                                       required>
                                <label for="shiftQty" class="form-label">Shift QTY</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="form-floating mb-3">
                                <select name="status" id="statusSelect" class="form-select">
                                    <option value="0"></option>
                                    <option value="1">Ready</option>
                                    <option value="2">Approval</option>
                                    <option value="3">Approved</option>
                                </select>
                                <label for="statusSelect" class="form-label">Status</label>
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
        <!-- Filters -->
        <form id="module-filter" action="#" class="d-flex align-items-center my-2 col-3">
            <div class="input-group">
                <label for="module-filter-select" class="input-group-text">MODULE</label>
                <select name="module" id="module-filter-select" class="form-select">
                    <option></option>
                    @foreach($modules as $module)
                        <option value="{{$module->id}}">{{'(' . $module->module_code . ') ' . $module->module_name}}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Practice Classes Table -->
    <div class="table-responsive">
        <table id="pclass-management-table" class="table table-bordered table-hover w-100" style="table-layout: fixed">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Teacher</th>
                <th>StudentQTY</th>
                <th>ScheduleQTY</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- All schedules Info modal -->
    <div class="modal modal-xl fade" id="all-schedule-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="all-schedule-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="pclass-signature-data">
                        <h4>Data to show in Calendar (signature data)</h4>
                        <form id="pclass-signature-form"
                              class="p-3"
                              data-action="{{route('schedules.update-signature-schedule')}}"
                              data-action-type="create"
                              data-action-method="post">
                            @csrf
                            <fieldset class="">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-danger">
                                            <label for="weekdaySelect" class="input-group-text">Weekday</label>
                                            <select name="weekday" id="weekdaySelect" class="form-select" required>
                                                <option value="">->Select</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                                <option value="7">Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-danger">
                                            <div class="input-group-text">START</div>
                                            <input type="date" name="start_date" class="form-control form-control-sm"
                                                   id="start_date" required>
                                            <label for="start_date" class="visually-hidden">START</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="d-flex multi-schedule-session-select border border-danger">
                                            <div class="input-group-text">SESSION</div>

                                            <input type="radio" name="session" value="1"
                                                   class="btn-check signature-session" id="session-1" autocomplete="off"
                                                   required>
                                            <label class="btn btn-outline-primary" for="session-1">S</label>

                                            <input type="radio" name="session" value="2"
                                                   class="btn-check signature-session" id="session-2" autocomplete="off"
                                                   required>
                                            <label class="btn btn-outline-primary" for="session-2">C</label>

                                            <input type="radio" name="session" value="3"
                                                   class="btn-check signature-session" id="session-3" autocomplete="off"
                                                   required>
                                            <label class="btn btn-outline-primary" for="session-3">T</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-success">
                                            <label for="pRoomSelect" class="input-group-text">ROOM</label>
                                            <select name="pRoomId" id="pRoomSelect" class="form-select">
                                                <option value="">->Select (not required)</option>
                                                @foreach($practiceRooms as $practiceRoom)
                                                    <option value="{{$practiceRoom->id}}">{{$practiceRoom->name . ' - ' . $practiceRoom->location}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-1 ms-auto">
                                        <button type="submit" class="btn btn-primary" id="create-pclass-btn">Save
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" id="add-schedule-btn" class="btn btn-success">Add Schedule
                                </button>
                                <div class="vr mx-3"></div>
                                <form data-action=""
                                      data-action-method="post"
                                      id="multi-schedule-form"
                                      class="d-inline-block">
                                    <!-- Add multi schedules form -->
                                    @csrf
                                    <input type="hidden" name="practice_class_id" id="multi-schedule-pclass-id">
                                    <input type="hidden" name="add_mode" value="multi">
                                    <div class="input-group d-inline-flex w-auto">
                                        <div class="input-group-text">QTY</div>
                                        <input type="number" name="multi_schedule_qty"
                                               class="form-control form-control-sm" id="multi-schedule-qty" min="2"
                                               max="10" required>
                                        <label for="multi-schedule-qty" class="visually-hidden">Qty</label>
                                    </div>
                                    <button type="submit" form="multi-schedule-form" id="add-multi-schedule-btn"
                                            class="btn btn-primary rounded-start-0">Add Multi Schedules
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="pclass-all-schedule-table" class="table table-bordered table-hover w-100"
                               style="table-layout: fixed">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Schedule Date</th>
                                <th>Weekday</th>
                                <th>Session</th>
                                <th>Shift</th>
                                <th>Practice Room</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal -->
    <div class="modal modal-lg fade" id="edit-pclass-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Edit Practice Class Schedule:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-pclass-form"
                          data-action=""
                          data-action-type="update"
                          data-action-method="post">
                        @csrf
                        @method('put')
                        <fieldset class="">
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="module_id" id="editModuleId">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="practice_class_code" class="form-control"
                                               id="editClassCode" placeholder="Class Code" required>
                                        <label for="editClassCode" class="form-label">Class Code</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" name="practice_class_name" class="form-control"
                                               id="editClassName" placeholder="Class Name" required>
                                        <label for="editClassName" class="form-label">Class Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <select name="teacher_id" id="editTeacherSelect" class="form-select">
                                            <option value=""></option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="editTeacherSelect" class="form-label">Teacher</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating mb-3">
                                        <input type="number" name="max_qty" class="form-control" id="editStudentQty"
                                               min="0" required>
                                        <label for="editStudentQty" class="form-label">Max Student</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating mb-3">
                                        <select name="status" id="editStatusSelect" class="form-select" required>
                                            <option value="0">Created</option>
                                            <option value="1">Ready</option>
                                            <option value="2">Approval</option>
                                            <option value="3">Approved</option>
                                        </select>
                                        <label for="editStatusSelect" class="form-label">Status</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="edit-pclass-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-pclass-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delete-modal-title">
                        Delete practice class: <span id="delete-modal-pclass-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delete-pclass-form"
                          data-action=""
                          data-action-type="delete"
                          data-action-method="post">
                        @csrf
                        <fieldset class="">
                            <input type="hidden" name="_method" value="delete">
                            <input type="hidden" id="delete-mode" name="_deleteMode" value="">
                            <div class="row">
                                <p>Any associated schedules and student registrations will also be deleted.</p>
                                <div class="mb-3">
                                    <label for="pclass-confirm-delete">
                                        Enter "<b><i>delete</i></b>" in the field below to proceed
                                    </label>
                                    <input type="text" name="confirmDelete" class="form-control"
                                           id="pclass-confirm-delete">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-pclass-form" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    @include('practice_class.scripts')
@endsection
