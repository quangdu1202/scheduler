@extends('layouts.app')

@section('content')
    <div class="mx-3 h-100 right-content">

        <!-- Page Header -->
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Schedule Calendar</h1>
        </div>

        <div class="text-center mb-4 d-flex justify-content-center flex-wrap">
            <h2 class="fw-bold d-block w-100">{{ $selectedDate->format('F Y') }}</h2>
            <div class="nav d-flex justify-content-center w-100">
                <a href="{{ route('calendar', ['date' => $selectedDate->copy()->firstOfMonth()->subMonth()->format('Y-m-d')]) }}"
                   type="button" class="btn btn-primary btn-sm month-nav prev-month me-4">
                    <i class="fa fa-chevron-left"></i>
                    Prev Month
                </a>

                <form id="dateForm" action="{{ route('calendar') }}" method="GET" class="d-flex justify-content-center">
                    <div class="form-group d-flex align-items-center">
                        <label for="datePicker" class="me-2 text-nowrap">Select Date:</label>
                        <input type="date" id="datePicker" name="date" class="form-control"
                               value="{{ $selectedDate->format('Y-m-d') }}">
                    </div>
                </form>

                <a href="/" type="button" class="btn btn-primary btn-sm mx-4 month-nav">Today</a>

                <a href="{{ route('calendar', ['date' => $selectedDate->copy()->firstOfMonth()->addMonth()->format('Y-m-d')]) }}"
                   type="button" class="btn btn-primary btn-sm month-nav next-month">
                    Next Month
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="mb-3 d-flex">
            <form id="calendar-filter" action="{{ route('calendar.filter') }}"
                  class="align-items-center d-flex flex-wrap">
                <div class="d-flex align-items-center">
                    <label for="calendar-module-select" class="me-2 text-nowrap fw-bold">Module Class:</label>
                    <select name="calendar-module-select" id="calendar-module-select" class="form-select">
                        <option value="1">202320503196001</option>
                        <option value="2">202320503197001</option>
                        <option value="3">20224IT6030002</option>
                        <option value="4">20231LP6013037</option>
                    </select>
                </div>

                <div class="d-flex align-items-center ms-5">
                    <label for="calendar-room-select" class="me-2 text-nowrap fw-bold">Room:</label>
                    <select name="calendar-room-select" id="calendar-room-select" class="form-select">
                        <option value="1">PM 1 (601 - A1)</option>
                        <option value="2">PM 3 (701 - A1)</option>
                        <option value="3">PM 6 (801 - A1)</option>
                        <option value="4">PM 9 (802 - A1)</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="schedule-table"
                   class="mt-1 table table-bordered text-center main-table w-100 border-opacity-25 border-dark h-100">
                <thead>
                <th class="row-order d-none">#</th>
                @foreach ($weekday_names as $weekday)
                    <th>{{ $weekday }}</th>
                @endforeach
                </thead>
                <tbody>
                @php
                    $days_in_month = count($monthDays);
                @endphp
                @for ($row = 0; $row < ceil($days_in_month / 7); $row++)
                    <tr>
                        <td class="row-order d-none">{{$row}}</td>
                        @for ($col = 0; $col < 7; $col++)
                            @php
                                $index = $row * 7 + $col;
                                $date = $monthDays[$index] ?? null;
                                $isCurrentMonth = $date && $date->month === $selectedDate->month;
                                $practiceClassesForDate = $date ? $practiceClasses->where('schedule_date', $date->format('Y-m-d')) : collect();
                            @endphp
                            <td data-date="{{ $date ? $date->format('d-m-Y') : '' }}"
                                class="single-cell {{ $isCurrentMonth ? 'current-month' : 'other-month' }}{{ ($date && $date->isToday()) ? ' today' : '' }}{{ ($date && $date->equalTo($highlight_day)) ? ' highlight' : '' }}">
                                <div class="cell-wrapper h-100">
                                    <div class="cell-top">
                                        <span
                                            class="row-week">{{ $col == 0 && $date ? 'Week ' . $date->weekOfYear : '' }}</span>
                                        <span class="cell-date">{{ $date ? $date->format('j') : '' }}</span>
                                    </div>
                                    <div class="cell-content d-flex flex-column justify-content-between">
                                        @for($session = 1; $session <= 3; $session++)
                                            @php
                                                $practiceClass = $practiceClassesForDate->firstWhere('session', $session);
                                            @endphp
                                            {{--Has Registered Class--}}
                                            @if($practiceClass)
                                                <div data-date="{{ $date ? $date->format('d-m-Y') : '' }}"
                                                     data-slot="{{ $session }}" class="cell-item registered">
                                                    <div class="slot-labels">
                                                        <div
                                                            class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>
                                                    </div>
                                                    <div
                                                        class="cell-class-details text-start h-100 p-2 d-flex flex-column justify-content-between">
                                                        {{--<p class="mb-0">{{$practiceClass->ten_lop_thuc_hanh}}</p>
                                                        <p class="mb-0">{{$practiceClass->teacher->ten_giang_vien}}</p>
                                                        <p class="mb-0">{{$practiceClass->ten_lop_thuc_hanh}}</p>--}}
                                                        <p class="mb-0">Quan ly cac du an Cong nghe thong tin asd as dsa
                                                            dsad asdas dsad asd asd asd asd asd asd</p>
                                                        <p class="mb-0">GV: Nguyen Van A</p>
                                                        <p class="mb-0">Lop: CNTT04</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div data-date="{{ $date ? $date->format('d-m-Y') : '' }}"
                                                     data-slot="{{ $session }}" class="cell-item">
                                                    <div class="slot-labels">
                                                        <div
                                                            class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>
                                                    </div>
                                                    <div class="visually-hidden cell-class-register text-start h-100">
                                                        <form action="#"
                                                              class="cell-register d-flex flex-wrap h-100 flex-column justify-content-evenly align-items-center">
                                                            <fieldset class="cell-register-fieldset"
                                                                      style="display: contents">
                                                                <label
                                                                    for="recurring-select-{{$date->format('j-n-y')}}"></label>
                                                                <select id="recurring-select-{{$date->format('j-n-y')}}"
                                                                        class="form-select is-invalid recurring-select border border-dark d-inline-block w-75"
                                                                        name="recurring" required aria-describedby>
                                                                    <option value="-1">No repeat</option>
                                                                    <option value="1">Weekly</option>
                                                                    <option value="2">Biweekly</option>
                                                                </select>
                                                                <div
                                                                    class="cell-register-actions w-100 d-flex justify-content-evenly">
                                                                    <button
                                                                        class="cell-action btn btn-sm btn-secondary action-cancel-register border border-secondary">
                                                                        Cancel
                                                                    </button>
                                                                    <button
                                                                        class="cell-action btn btn-sm btn-primary action-submit-register border border-primary">
                                                                        Confirm
                                                                    </button>
                                                                </div>
                                                            </fieldset>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <div id="cell-popup-modal" class="popup-modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="cell-content"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const table = $('#schedule-table');
            table.DataTable({
                order: [0, 'asc'],
                searching: false,
                "pageLength": 1,
                columns: [{width: '0'}, {width: '14%'}, {width: '14%'}, {width: '14%'}, {width: '14%'}, {width: '14%'}, {width: '14%'}, {width: '14%'}],
                "columnDefs": [
                    {visible: false, targets: 0},
                    {"orderable": false, "targets": "_all"},
                    {"className": "dt-center", "targets": "_all"}
                ],
                language: {
                    "info": "Showing _START_ to _END_ of _TOTAL_ weeks",
                    //customize pagination prev and next buttons: use arrows instead of words
                    'paginate': {
                        'previous': '<span">Prev Week</span>',
                        'next': '<span>Next Week</span>'
                    },
                    //customize number of elements to be displayed
                    "lengthMenu": 'Show&nbsp;&nbsp;&nbsp;' +
                        '<select class="form-control input-sm">' +
                        '<option value="1">1</option>' +
                        '<option value="2">2</option>' +
                        '<option value="-1">All</option>' +
                        '</select> week(s)'
                }
            });
            const $label = $('<label/>');
            $label.text('Module:').appendTo('#module-select');
            const $select = $('<select/>').appendTo('#module-select');
            $('<option/>').val('1').text('option #1').appendTo($select);
            $('<option/>').val('2').text('option #2').appendTo($select);
            $('<option/>').val('3').text('option #3').appendTo($select);
        });
    </script>
@endsection
