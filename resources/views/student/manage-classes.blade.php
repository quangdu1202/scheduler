@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2 fw-bold">Manage Classes</h1>
        @include('partials.class-timer-placeholder')
        @if(Auth::user() !== null)
            <div class="user-info">
                <span class="d-block text-end">Hello Student <b>{{Auth::user()->name}}</b>!</span>
                <span class="d-block text-end">Student Code: <b>{{Auth::user()->userable->student_code}}</b></span>
            </div>
        @endif
    </div>

    <!-- Schedule table -->
    @php
        $days = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN']; // Weekday names starting from Monday
        $todayIndex = date('w') - 1; // 'w' gives the day of the week (0 for Sunday, 6 for Saturday)
        $today = $todayIndex >= 0 ? $days[$todayIndex] : 'SUN'; // Adjust for Sunday case
    @endphp

    <div class="table-responsive">
        <table id="register-schedule-table" class="table table-bordered w-100" style="table-layout: fixed">
            <thead class="border-black">
            <tr>
                <th>#</th>
                <th>K</th>
                @foreach ($days as $day)
                    <th class="{{ $day === $today ? 'text-bg-primary' : '' }}">{{ $day }}</th>
                @endforeach
            </tr>
            </thead>
        </table>
    </div>

    <!-- Registered Classes Table -->
    <!-- Action Buttons (Add new, etc.) -->
    <div class="row">
        <!-- Filters -->
        <div class="row">
            <div class="col-4">
                <div class="input-group mb-3">
                    <label for="module-filter-select" class="input-group-text">MODULE</label>
                    <select name="module" id="module-filter-select" class="form-select">
                        <option></option>
                        @foreach($modules as $module)
                            <option value="{{$module->module_code}}">{{'(' . $module->module_code . ') ' . $module->module_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <label for="status-filter-select" class="input-group-text">STATUS</label>
                    <select name="status" id="status-filter-select" class="form-select">
                        <option value=""></option>
                        <option value="In progress">In progress</option>
                        <option value="Complete">Complete</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="registered-pclass-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Info</th>
                <th>Teacher</th>
                <th>Start Date</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Actions</th>
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
                        <form id="pclass-signature-form">
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
                                            <select name="pRoomId" id="pRoomSelect" class="form-select" disabled>
                                                @foreach($practiceRooms as $practiceRoom)
                                                    <option value="{{$practiceRoom->id}}">{{$practiceRoom->name . ' - ' . $practiceRoom->location}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="table-responsive p-2">
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

    <div class="position-fixed" style="bottom: 50px; right: 35px; pointer-events: none;">
        <h2 class="text-danger fw-bold fs-1" style="text-shadow: 2px 0 #dc3545; letter-spacing:2px;">STUDENT ACCOUNT</h2>
    </div>

    <!-- Scripts -->
    @include('student.manage-scripts')
    @include('partials.class-timer-script')
@endsection
