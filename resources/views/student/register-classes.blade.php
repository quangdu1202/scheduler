@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="py-3 mb-3 border-bottom sticky-top bg-body d-flex justify-content-between">
        <h1 class="h2 fw-bold">Register Class</h1>
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
                                <th>Teacher</th>
                                <th>K1 QTY</th>
                                <th>K2 QTY</th>
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
            <form id="module-filter" action="#" class="d-flex align-items-center my-2 col-3">
                <div class="input-group mb-3">
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
        <table id="pclass-register-table" class="table table-bordered table-hover w-100">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Module</th>
                <th>Class Info</th>
                <th>Teacher</th>
                <th>Start Date</th>
                <th>Schedule</th>
                <th>K1_QTY</th>
                <th>K2_QTY</th>
                <th>Actions</th>
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
        <h2 class="text-danger fw-bold fs-1" style="text-shadow: 2px 0 #dc3545; letter-spacing:2px;">STUDENT ACCOUNT</h2>
    </div>

    <!-- Scripts -->
    @include('student.register-scripts')
    @include('partials.class-timer-script')
    <script>
        $(document).ready(function () {
            const registerScheduleTable = $('#register-schedule-table');

            // show class on date
            const classOnDateModal = new bootstrap.Modal('#pclass-ondate-modal', {backdrop: true});
            const pClassOndateTable = $('#pclass-ondate-table');
            registerScheduleTable.on('click', '.schedule-table-add-btn', function () {
                showClassesOnDate($(this));
            });

            function showClassesOnDate($addBtn) {
                showOverlay();
                const $weekDay = $addBtn.data('weekday');
                const $session = $addBtn.data('session');
                const $shift = $addBtn.data('shift');

                if ($.fn.DataTable.isDataTable(pClassOndateTable)) {
                    pClassOndateTable.DataTable().destroy();
                }

                pClassOndateTable.DataTable({
                    ajax: {
                        url: $($addBtn).data('get-url'),
                        data: {
                            weekDay: $weekDay,
                            session: $session,
                            shift: $shift,
                        },
                        dataSrc: ''
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data: ", error);
                        toastr.error("An error occurred while loading the data", "Error");
                        hideOverlay();
                    },
                    columns: [
                        {data: 'index', width: '5%'},
                        {data: 'module_info', type: 'html', width: '15%'},
                        {data: 'class_info', type: 'html', width: '20%'},
                        {data: 'teacher_name', type: 'html', width: '9%'},
                        {data: 'k1Qty', type: 'html', width: '6%'},
                        {data: 'k2Qty', type: 'html', width: '6%'},
                        {data: 'actions', type: 'html', width: '9%'},
                    ],
                    autoWidth: false,
                    columnDefs: [
                        {
                            className: "dt-center",
                            targets: [0,4,5,6]
                        },
                        {
                            orderable: false,
                            targets: [1,2,3,4,5]
                        }
                    ],
                    layout: {
                        topStart: {},
                        topEnd: {},
                        bottomStart: {},
                        bottomEnd: {},
                    },
                    paging: false,
                    initComplete: function () {
                        hideOverlay();
                        classOnDateModal.show();
                    }
                });
            }
            // end
        })
    </script>
@endsection
