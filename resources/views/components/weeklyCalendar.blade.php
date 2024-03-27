<?php
//    dd($weekday_names);
//    $currentDate = $startDate;
?>

<html lang="en">
<head>
    <title>Calendar</title>
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
</head>
<body>
<div class="calendar-container">
    <h2>{{ $month_names[$current_month] }} {{ $current_year }}</h2>
    <div class="navigation">
        <a href="{{ route('weekly', ['startDate' => date('Y-m-d', strtotime("-7 days", strtotime($startDate)))]) }}" class="prev-button">&lt; Previous</a>
        <a href="{{ route('weekly', ['startDate' => date('Y-m-d', strtotime("+7 days", strtotime($startDate)))]) }}" class="next-button">Next &gt;</a>
        @php
        @endphp
    </div>

    <!-- Weekly table -->
    <table class="main-table">
        <thead>
        <tr>
            @foreach ($weekday_names as $weekday_name)
                <th>{{ $weekday_name }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach($week_days as $week_day)
                <td class="{{ (date('n', strtotime($week_day)) < date('n', strtotime($startDate)) || date('n', strtotime($week_day)) > date('n', strtotime($startDate))) ? "other-month" : "" }} {{ strtotime($week_day) == strtotime($today) ? "today" : "" }}">
                    {{ date('j', strtotime($week_day)) }}
                </td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
