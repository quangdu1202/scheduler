@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2">Manage Classes</h1>
        <div class="d-flex flex-column text-center">
            <div class="fw-bold">Current Time: <span id="current-time" class="fw-normal">Loading...</span></div>
            <div id="next-class-info">Loading next class info...</div>
        </div>
        @if(auth()->user() !== null)
                <div class="user-info">
                    <span>Hello Teacher <b>{{Auth::user()->name}}</b>!</span>
                    <span>Teacher ID: {{Auth::user()->userable->id}}</span>
                </div>
            @endif
    </div>

    <!-- Schedule table -->
    <div class="table-responsive">
        <table id="register-schedule-table" class="table table-bordered w-100" style="table-layout: fixed">
            <thead class="border-black">
            <tr>
                <th>#</th>
                <th>K</th>
                <th>MON</th>
                <th>TUE</th>
                <th>WED</th>
                <th>THU</th>
                <th>FRI</th>
                <th>SAT</th>
                <th>SUN</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Class on date modal -->
    <div class="modal modal-xl fade" id="pclass-ondate-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="pclass-ondate-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All class on:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="pclass-ondate-table" class="table table-bordered table-hover w-100">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Class Code</th>
                                <th>Class Name</th>
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
    <hr>
    <!-- Registered Classes Table -->
    <div class="table-responsive">
        <table id="registered-pclass-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Info</th>
                <th>Start Date</th>
                <th>Weekday</th>
                <th>K1</th>
                <th>K2</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- All schedules Info modal -->
    <div class="modal fade" id="all-schedule-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" id="all-schedule-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column overflow-y-hidden py-0">
                    <div class="pclass-signature-data sticky-top p-2 bg-white">
                        <h4>Signature Data (show on calendar)</h4>
                        <form id="pclass-signature-form"
                              data-action="{{route('schedules.update-signature-schedule')}}"
                              data-action-type="create"
                              data-action-method="post">
                            @csrf
                            <fieldset class="">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="input-group mb-3 border border-danger">
                                            <label for="weekdaySelect" class="input-group-text">Weekday</label>
                                            <select name="weekday" id="weekdaySelect" class="form-select" disabled>
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
                                                   id="start_date" disabled>
                                            <label for="start_date" class="visually-hidden">START</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="d-flex multi-schedule-session-select border border-danger">
                                            <div class="input-group-text">SESSION</div>

                                            <input type="radio" name="session" value="1"
                                                   class="btn-check signature-session" id="session-1" autocomplete="off"
                                                   disabled>
                                            <label class="btn btn-outline-primary" for="session-1">S</label>

                                            <input type="radio" name="session" value="2"
                                                   class="btn-check signature-session" id="session-2" autocomplete="off"
                                                   disabled>
                                            <label class="btn btn-outline-primary" for="session-2">C</label>

                                            <input type="radio" name="session" value="3"
                                                   class="btn-check signature-session" id="session-3" autocomplete="off"
                                                   disabled>
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
                                    <div class="col-3">
                                        <div class="d-flex multi-schedule-session-select border border-success">
                                            <div class="input-group-text">STUDENT QTY</div>

                                            <label class="btn btn-primary border-primary" for="studentQty1">K1</label>
                                            <input type="number" min="0" max="99" name="studentQty1" value=""
                                                   class="form-control form-control-sm" id="studentQty1" autocomplete="off">

                                            <label class="btn btn-primary border-primary" for="studentQty2">K2</label>
                                            <input type="number" min="0" max="99" name="studentQty2" value=""
                                                   class="form-control form-control-sm" id="studentQty2" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-auto ms-auto">
                                        <button type="submit" class="btn btn-primary" id="create-pclass-btn">Save
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div class="row">
                            <div class="col-auto">
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
                            <div class="col-auto ms-auto">
                                <button class="btn btn-primary reload-table-btn"><i class="lni lni-reload align-middle"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive p-2">
                        <table id="pclass-all-schedule-table" class="table table-bordered table-hover w-100" style="table-layout: fixed">
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

    <!-- Students list modal -->
    <div class="modal fade" id="pclass-student-list-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="pclass-student-list-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        Students list for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column overflow-y-hidden py-0">
                    <div class="table-responsive p-2">
                        <table id="pclass-student-list-table" class="table table-bordered table-hover w-100" style="table-layout: fixed">
                            <thead class="thead-light">
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Student Code</th>
                                <th rowspan="2">Student Name</th>
                                <th rowspan="2">Gender</th>
                                <th rowspan="2">Date of birth</th>
                                <th colspan="2" class="text-center">Shift</th>
                            </tr>
                            <tr>
                                <th id="k1qty">K1</th>
                                <th id="k2qty">K2</th>
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

    <!-- Scripts -->
    @include('teacher.register-scripts')
    @include('teacher.manage-scripts')
    <script>
        $(document).ready(function() {
            function displayCurrentTime() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const dateTimeString = now.toLocaleDateString('en-US', options);
                $('#current-time').text(`${dateTimeString}`);
            }

            function updateNextClassInfo() {
                const classes = @json($classes);
                const now = new Date();

                let nextClass = null;
                let smallestDiff = Number.MAX_SAFE_INTEGER;

                $.each(classes, function(index, cls) {
                    const classTime = new Date(cls.classTime);
                    const diffInSeconds = (classTime - now) / 1000;

                    if (diffInSeconds > 0 && diffInSeconds < smallestDiff) {
                        smallestDiff = diffInSeconds;
                        nextClass = cls;
                    }
                });

                if (nextClass) {
                    const classTime = new Date(nextClass.classTime);
                    const options = { weekday: 'long', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                    const formattedClassTime = classTime.toLocaleDateString('en-US', options);

                    const hours = Math.floor(smallestDiff / 3600);
                    const minutes = Math.floor((smallestDiff % 3600) / 60);
                    const timeToNextClass = `${hours} hours ${minutes} minutes`;

                    $('#next-class-info').html(`Next class: <span class="fw-bold">${nextClass.className}</span> at ${formattedClassTime} in <span class="fw-bold">${timeToNextClass}</span>`);
                } else {
                    $('#next-class-info').text('No upcoming classes.');
                }
            }

            function updatePage() {
                displayCurrentTime();
                updateNextClassInfo();
            }

            updatePage(); // Initial update on page load
            setInterval(displayCurrentTime, 1000); // Update every second
            setInterval(updateNextClassInfo, 60000); // Update every minutes
        });
    </script>
@endsection
