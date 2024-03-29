{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--<div class="container h-100 right-content">--}}

{{--    <!-- Page Header -->--}}
{{--    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">--}}
{{--        <h1 class="h2">Schedule Calendar</h1>--}}
{{--    </div>--}}

{{--    <div class="text-center mb-4 d-flex justify-content-center flex-wrap">--}}
{{--        <h2 class="fw-bold d-block w-100">{{ $month_names[$current_month] }} {{ $current_year }}</h2>--}}
{{--        <div class="nav d-flex justify-content-center w-100">--}}
{{--            <a href="{{ route('calendar', ['date' => date('Y-m-d', strtotime('previous month', strtotime("$current_year-$current_month-$current_day")))]) }}" type="button" class="btn btn-primary btn-sm month-nav prev-month me-4">--}}
{{--                <i class="fa fa-chevron-left"></i>--}}
{{--                Prev Month--}}
{{--            </a>--}}

{{--            <form id="dateForm" action="{{ route('calendar') }}" method="GET" class="d-flex justify-content-center">--}}
{{--                <div class="form-group d-flex align-items-center">--}}
{{--                    <label for="datePicker" class="me-2 text-nowrap">Select Date:</label>--}}
{{--                    <input type="date" id="datePicker" name="date" class="form-control" value="{{ $current_year }}-{{ str_pad($current_month, 2, '0', STR_PAD_LEFT) }}-{{ str_pad($current_day, 2, '0', STR_PAD_LEFT) }}">--}}
{{--                </div>--}}
{{--            </form>--}}

{{--            <a href="/" type="button" class="btn btn-primary btn-sm mx-4 month-nav">Today</a>--}}

{{--            <a href="{{ route('calendar', ['date' => date('Y-m-d', strtotime('next month', strtotime("$current_year-$current_month-$current_day")))]) }}" type="button" class="btn btn-primary btn-sm month-nav next-month">--}}
{{--                Next Month--}}
{{--                <i class="fa fa-chevron-right"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="mb-3 d-flex">--}}
{{--        <form id="calendar-filter" action="{{route('calendar.filter')}}" class="align-items-center d-flex flex-wrap">--}}
{{--            <div class="d-flex align-items-center">--}}
{{--                <label for="calendar-module-select" class="me-2 text-nowrap fw-bold">Module Class:</label>--}}
{{--                <select name="calendar-module-select" id="calendar-module-select" class="form-select">--}}
{{--                    <option value="1">202320503196001</option>--}}
{{--                    <option value="2">202320503197001</option>--}}
{{--                    <option value="3">20224IT6030002</option>--}}
{{--                    <option value="4">20231LP6013037</option>--}}
{{--                </select>--}}
{{--            </div>--}}

{{--            <div class="d-flex align-items-center ms-5">--}}
{{--                <label for="calendar-room-select" class="me-2 text-nowrap fw-bold">Room:</label>--}}
{{--                <select name="calendar-room-select" id="calendar-room-select" class="form-select">--}}
{{--                    <option value="1">PM 1 (601 - A1)</option>--}}
{{--                    <option value="2">PM 3 (701 - A1)</option>--}}
{{--                    <option value="3">PM 6 (801 - A1)</option>--}}
{{--                    <option value="4">PM 9 (802 - A1)</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}

{{--    <div class="table-responsive">--}}
{{--        <table id="schedule-table" class="mt-1 table table-bordered text-center main-table w-100 border-opacity-25 border-dark h-100">--}}
{{--            <thead>--}}
{{--                @foreach ($weekday_names as $weekday)--}}
{{--                    <th>{{ $weekday }}</th>--}}
{{--                @endforeach--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @php--}}
{{--                $days_in_month = count($month_days);--}}
{{--            @endphp--}}
{{--            @for ($row = 0; $row < ceil($days_in_month / 7); $row++)--}}
{{--                <tr>--}}
{{--                    @for ($col = 0; $col < 7; $col++)--}}
{{--                        @php--}}
{{--                            $index = $row * 7 + $col;--}}
{{--                            $date = $month_days[$index] ?? null;--}}
{{--                            $isToday = ($date === $today);--}}
{{--                            $isHighlighted = in_array($date, $highlight_day);--}}
{{--                            $isCurrentMonth = ($index >= $start_padding) && ($index < $start_padding + $days_in_month);--}}
{{--                            $practiceClass = $practiceClasses->firstWhere('schedule_date', $date);--}}
{{--                        @endphp--}}
{{--                        <td data-date="{{ date('d-m-Y', strtotime($date)) }}"--}}
{{--                            class="single-cell {{ $isCurrentMonth ? 'current-month' : 'other-month' }}{{ $isToday ? ' today' : '' }}{{ $isHighlighted ? ' highlight' : '' }}">--}}
{{--                            <div class="cell-wrapper h-100">--}}
{{--                                <div class="cell-top">--}}
{{--                                    <span class="row-week">{{ $col == 0 ? date('W', strtotime($date)) : '' }}</span>--}}
{{--                                    <span class="cell-date">{{ $date ? date('j', strtotime($date)) : '' }}</span>--}}
{{--                                </div>--}}
{{--                                <div class="cell-content d-flex flex-column justify-content-between">--}}
{{--                                    @if($practiceClass)--}}
{{--                                        @for($session = 1; $session <= 3; $session++)--}}
{{--                                            @php--}}
{{--                                                $isHighlighted = $practiceClass->session == $session;--}}
{{--                                            @endphp--}}
{{--                                            <div data-date="{{ date('d-m-Y', strtotime($date)) }}" data-slot="{{ $session }}" class="cell-item{{ $isHighlighted ? ' highlight' : '' }}">--}}
{{--                                                <div class="slot-labels">--}}
{{--                                                    <div class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endfor--}}
{{--                                    @else--}}
{{--                                        @for($session = 1; $session <= 3; $session++)--}}
{{--                                            <div data-date="{{ date('d-m-Y', strtotime($date)) }}" data-slot="{{ $session }}" class="cell-item">--}}
{{--                                                <div class="slot-labels">--}}
{{--                                                    <div class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endfor--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                    @endfor--}}
{{--                </tr>--}}
{{--            @endfor--}}
{{--            </tbody>--}}
{{--        </table>--}}

{{--        <div id="cell-popup-modal" class="popup-modal">--}}
{{--            <div class="modal-content">--}}
{{--                <span class="close">&times;</span>--}}
{{--                <div id="cell-content"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<script>--}}
{{--    $(document).ready(function () {--}}
{{--        $('#schedule-table').DataTable({--}}
{{--            searching: false,--}}
{{--            "pageLength": 1,--}}
{{--            "columnDefs": [--}}
{{--                { "orderable": false, "targets": '_all' },--}}
{{--                {"className": "dt-center", "targets": "_all"}--}}
{{--            ],--}}
{{--            language: {--}}
{{--                "info": "Showing _START_ to _END_ of _TOTAL_ weeks",--}}
{{--                //customize pagination prev and next buttons: use arrows instead of words--}}
{{--                'paginate': {--}}
{{--                    'previous': '<span">Prev Week</span>',--}}
{{--                    'next': '<span>Next Week</span>'--}}
{{--                },--}}
{{--                //customize number of elements to be displayed--}}
{{--                "lengthMenu": 'Show&nbsp;&nbsp;&nbsp;' +--}}
{{--                    '<select class="form-control input-sm">'+--}}
{{--                    '<option value="1">1</option>'+--}}
{{--                    '<option value="2">2</option>'+--}}
{{--                    '<option value="-1">All</option>'+--}}
{{--                    '</select> week(s)'--}}
{{--            }--}}
{{--        });--}}
{{--        const $label = $('<label/>');--}}
{{--        $label.text('Module:').appendTo('#module-select');--}}
{{--        const $select = $('<select/>').appendTo('#module-select');--}}
{{--        $('<option/>').val('1').text('option #1').appendTo($select);--}}
{{--        $('<option/>').val('2').text('option #2').appendTo($select);--}}
{{--        $('<option/>').val('3').text('option #3').appendTo($select);--}}
{{--    });--}}
{{--</script>--}}
{{--<style>--}}
{{--    div.dt-container .dt-length {--}}
{{--        display: inline-block;--}}
{{--    }--}}
{{--    #module-select {--}}
{{--        display: inline-block;--}}
{{--        padding-left: 30px;--}}
{{--    }--}}
{{--</style>--}}
{{--@endsection--}}

@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Schedule Calendar</h1>
        </div>

        <div class="text-center mb-4 d-flex justify-content-center flex-wrap">
            <h2 class="fw-bold d-block w-100">{{ $selectedDate->format('F Y') }}</h2>
            <div class="nav d-flex justify-content-center w-100">
                <a href="{{ route('calendar', ['date' => $selectedDate->copy()->subMonth()->format('Y-m-d')]) }}" type="button" class="btn btn-primary btn-sm month-nav prev-month me-4">
                    <i class="fa fa-chevron-left"></i>
                    Prev Month
                </a>

                <form id="dateForm" action="{{ route('calendar') }}" method="GET" class="d-flex justify-content-center">
                    <div class="form-group d-flex align-items-center">
                        <label for="datePicker" class="me-2 text-nowrap">Select Date:</label>
                        <input type="date" id="datePicker" name="date" class="form-control" value="{{ $selectedDate->format('Y-m-d') }}">
                    </div>
                </form>

                <a href="/" type="button" class="btn btn-primary btn-sm mx-4 month-nav">Today</a>

                <a href="{{ route('calendar', ['date' => $selectedDate->copy()->addMonth()->format('Y-m-d')]) }}" type="button" class="btn btn-primary btn-sm month-nav next-month">
                    Next Month
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="mb-3 d-flex">
            <form id="calendar-filter" action="{{ route('calendar.filter') }}" class="align-items-center d-flex flex-wrap">
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
            <table id="schedule-table" class="mt-1 table table-bordered text-center main-table w-100 border-opacity-25 border-dark h-100">
                <thead>
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
                        @for ($col = 0; $col < 7; $col++)
                            @php
                                $index = $row * 7 + $col;
                                $date = $monthDays[$index] ?? null;
                                $isToday = $date && $date->isToday();
                                $isHighlighted = $date && $date->equalTo($highlight_day);
                                $isCurrentMonth = $date && $date->month === $selectedDate->month;
                                $practiceClass = $practiceClasses->firstWhere('schedule_date', $date ? $date->format('Y-m-d') : null);
                            @endphp
                            <td data-date="{{ $date ? $date->format('d-m-Y') : '' }}"
                                class="single-cell {{ $isCurrentMonth ? 'current-month' : 'other-month' }}{{ $isToday ? ' today' : '' }}{{ $isHighlighted ? ' highlight' : '' }}">
                                <div class="cell-wrapper h-100">
                                    <div class="cell-top">
                                        <span class="row-week">{{ $col == 0 && $date ? $date->weekOfYear : '' }}</span>
                                        <span class="cell-date">{{ $date ? $date->format('j') : '' }}</span>
                                    </div>
                                    <div class="cell-content d-flex flex-column justify-content-between">
                                        @if($practiceClass)
                                            @for($session = 1; $session <= 3; $session++)
                                                @php
                                                    $isHighlighted = $practiceClass->session == $session;
                                                @endphp
                                                <div data-date="{{ $date ? $date->format('d-m-Y') : '' }}" data-slot="{{ $session }}" class="cell-item{{ $isHighlighted ? ' highlight' : '' }}">
                                                    <div class="slot-labels">
                                                        <div class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            @for($session = 1; $session <= 3; $session++)
                                                <div data-date="{{ $date ? $date->format('d-m-Y') : '' }}" data-slot="{{ $session }}" class="cell-item">
                                                    <div class="slot-labels">
                                                        <div class="slot-label">{{ $session == 1 ? 'S' : ($session == 2 ? 'C' : 'T') }}</div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>

            <div id="cell-popup-modal" class="popup-modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div id="cell-content"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#schedule-table').DataTable({
                searching: false,
                "pageLength": 1,
                "columnDefs": [
                    { "orderable": false, "targets": '_all' },
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
                        '<select class="form-control input-sm">'+
                        '<option value="1">1</option>'+
                        '<option value="2">2</option>'+
                        '<option value="-1">All</option>'+
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
    <style>
        div.dt-container .dt-length {
            display: inline-block;
        }
        #module-select {
            display: inline-block;
            padding-left: 30px;
        }
    </style>
@endsection
