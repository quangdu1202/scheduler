<!-- Create form -->
<div id="new-pclass-form-wrapper" class="new-form-hidden border border-primary col-12">
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
                <div class="col-5">
                    <div class="form-floating mb-3">
                        <input type="text" name="practice_class_name" class="form-control" id="className" placeholder="Class Name" required>
                        <label for="className" class="form-label">Class Name</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="form-floating mb-3">
                        <select name="practice_room_id" id="roomSelect" class="form-select" required>
                            <option></option>
                            @foreach($practiceRooms as $practiceRoom)
                                <option value="{{$practiceRoom->id}}" data-pc-qty="{{$practiceRoom->pc_qty}}">{{$practiceRoom->name . ' (' . $practiceRoom->location . ')'}}</option>
                            @endforeach
                        </select>
                        <label for="roomSelect" class="form-label">Practice Room</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-floating mb-3 position-relative">
                        <input type="number" name="max_qty" class="form-control" id="maxStudentQty" required disabled
                               data-bs-toggle="tooltip" data-bs-placement="right" aria-describedby="maxStudentQtyFeedback">
                        <label for="maxStudentQty" class="form-label">Max Students</label>
                        <div class="invalid-tooltip" id="maxStudentQtyFeedback">
                            Exceeding the number of PCs.
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" name="schedule_date" id="startDate" min="{{ date('Y-m-d') }}" required>
                        <label for="startDate" class="form-label">Start Date</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-floating mb-3">
                        <div class="form-control" id="sessionSelect">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="session" id="session-1" value="1" required>
                                <label class="form-check-label" for="session-1"><span class="badge rounded-pill text-bg-success">S</span></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="session" id="session-2" value="2" required>
                                <label class="form-check-label" for="session-2"><span class="badge rounded-pill text-bg-primary">C</span></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="session" id="session-3" value="3" required>
                                <label class="form-check-label" for="session-3"><span class="badge rounded-pill text-bg-danger">T</span></label>
                            </div>
                        </div>
                        <label for="sessionSelect" class="form-label">Session</label>
                    </div>
                    <style>
                        #sessionSelect .form-check-inline {
                            margin-right: 0.6rem;
                        }
                    </style>
                </div>
                <div class="col-2">
                    <div class="form-floating mb-3">
                        <select name="recurring_interval" id="recurringSelect" class="form-select" required>
                            <option></option>
                            <option value="0">Once</option>
                            <option value="604800">Weekly</option>
                            <option value="1209600">Biweekly</option>
                        </select>
                        <label for="recurringSelect" class="form-label">Recurring</label>
                    </div>
                </div>
                <div class="col-1">
                    <div class="form-floating mb-3">
                        <input type="number" id="repeatLimit" class="form-control" name="repeat_limit" min="1" max="20" disabled>
                        <label for="repeatLimit" class="form-label">Repeat</label>
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