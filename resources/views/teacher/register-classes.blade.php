@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2 fw-bold">Register Classes</h1>
        @include('partials.class-timer-placeholder')
        @if(Auth::user() !== null)
            <div class="user-info">
                <span class="d-block text-end">Hello Teacher <b>{{Auth::user()->name}}</b>!</span>
                <span class="d-block text-end">Teacher ID: <b>{{Auth::user()->userable->id}}</b></span>
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
                                <th>Module Info</th>
                                <th>Class Info</th>
                                <th>Weekday</th>
                                <th>Room</th>
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
    <!-- Practice Classes Table -->
    <button class="btn btn-outline-primary mx-auto d-block" id="toggle-register-table">
        Load available classes
        <i class="lni lni-chevron-down align-middle"></i>
    </button>
    <div class="table-responsive" id="toggle-register-table-target" style="display: none">
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
            </div>
        </div>
        <table id="pclass-register-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Start Date</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <hr>

    <!-- Class schedules Info modal -->
    <div class="modal modal-xl fade" id="pclass-schedules-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="pclass-schedules-modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-modal-title">
                        All schedules for:
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="pclass-schedules-table" class="table table-bordered table-hover w-100">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Schedule Date</th>
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
        <h2 class="text-danger fw-bold fs-1" style="text-shadow: 2px 0 #dc3545; letter-spacing:2px;">TEACHER ACCOUNT</h2>
    </div>

    <!-- Scripts -->
    @include('teacher.register-scripts')
    @include('partials.class-timer-script')
@endsection
