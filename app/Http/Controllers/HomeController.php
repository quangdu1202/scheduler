<?php

namespace App\Http\Controllers;

use App\Models\PracticeClass;
use App\Models\User;
use App\Services\Module\Contracts\ModuleServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @var User
     */
    protected User $user;
    /**
     * @var PracticeClass
     */
    protected PracticeClass $practiceClass;

    protected ModuleServiceInterface $module;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user,
                                PracticeClass $practiceClass,
                                ModuleServiceInterface $module
    )
    {
//        $this->middleware('auth');
        $this->user = $user;
        $this->practiceClass = $practiceClass;
        $this->module = $module;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
//    public function index(Request $request)
//    {
//        $practiceClasses = $this->practiceClass->all();
//
//        $today = date("Y-m-d");
//        $dateParts = explode('-', isset($request->date) ? $request->date : $today);
//        $current_year = intval($dateParts[0]);
//        $current_month = intval($dateParts[1]);
//        $current_day = isset($dateParts[2]) ? intval($dateParts[2]) : 1;
//        $highlight_day[] = $request->date;
//
//        $month_names = [
//            1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June",
//            7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"
//        ];
//
//        $first_day_of_month = date("Y-m-01", strtotime("$current_year-$current_month-01"));
//        $last_day_of_month = date("Y-m-t", strtotime($first_day_of_month));
//
//        $start_day = date('N', strtotime($first_day_of_month));
//        $end_day = date('N', strtotime($last_day_of_month));
//
//        $start_padding = ($start_day - 1) % 7;
//        $end_padding = 7 - $end_day;
//
//        $start_date = date('Y-m-d', strtotime("-$start_padding day", strtotime($first_day_of_month)));
//        $end_date = date('Y-m-d', strtotime("+$end_padding day", strtotime($last_day_of_month)));
//
//        $month_days = [];
//
//        // Loop through each day
//        $current_date = $start_date;
//        while ($current_date <= $end_date) {
//            $month_days[] = $current_date;
//            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
//        }
//
//        $weekday_names = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
//
//        $prev_month = $current_month - 1 == 0 ? 12 : $current_month - 1;
//        $prev_year = $current_month - 1 == 0 ? $current_year -1 : $current_year;
//
//        $next_month = $current_month + 1 > 12 ? 1 : $current_month + 1;
//        $next_year = $current_month + 1 > 12 ? $current_year + 1 : $current_year;
//
//        return view('components.calendar',
//            compact('today',
//                'current_year',
//                'current_month',
//                'current_day',
//                'month_names',
//                'month_days',
//                'weekday_names',
//                'start_padding',
//                'end_padding',
//                'prev_month',
//                'prev_year',
//                'next_month',
//                'next_year',
//                'highlight_day',
//                'practiceClasses'
//            ));
//    }


    public function index(Request $request)
    {
        $today = now();
        $selectedDate = $request->filled('date') ? Carbon::parse($request->date) : $today;
        $monthDays = $this->getMonthDays($selectedDate);

        $practiceClasses = $this->practiceClass->all();

        $weekday_names = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $highlight_day = $selectedDate->format('Y-m-d');

        return view('components.calendar', [
            'today' => $today,
            'weekday_names' => $weekday_names,
            'highlight_day' => $highlight_day,
            'selectedDate' => $selectedDate,
            'monthDays' => $monthDays,
            'practiceClasses' => $practiceClasses,
        ]);
    }

    private function getMonthDays(Carbon $date)
    {
        $firstDayOfMonth = $date->copy()->startOfMonth();
        $lastDayOfMonth = $date->copy()->endOfMonth();

        // Determine the start padding based on the day of the week (0 = Monday, 6 = Sunday)
        $startPadding = $firstDayOfMonth->dayOfWeek === 0 ? 6 : $firstDayOfMonth->dayOfWeek - 1;

        // Add days from the previous month
        $prevMonthLastDay = $firstDayOfMonth->copy()->subDays($startPadding);

        // Determine the end padding based on the day of the week (0 = Monday, 6 = Sunday)
        $endPadding = $lastDayOfMonth->dayOfWeek === 0 ? 0 : 7 - $lastDayOfMonth->dayOfWeek;

        // Add days from the next month
        $nextMonthFirstDay = $lastDayOfMonth->copy()->addDays($endPadding + 1);

        $monthDays = [];

        // Add days from the previous month
        for ($i = 0; $i < $startPadding; $i++) {
            $monthDays[] = $prevMonthLastDay->copy()->addDays($i);
        }

        // Add days from the current month
        for ($currentDay = $firstDayOfMonth; $currentDay <= $lastDayOfMonth; $currentDay->addDay()) {
            $monthDays[] = $currentDay->copy();
        }

        // Add days from the next month
        for ($i = 0; $i < $endPadding; $i++) {
            $monthDays[] = $nextMonthFirstDay->copy()->addDays($i);
        }

        return $monthDays;
    }


    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function weeklyCalendar(Request $request)
    {
        $highlight_day = '';
        $today = date("j-n-Y");

        $startDate = $request->startDate
            ? date("Y-m-d", strtotime($request->startDate))
            : date('Y-m-d', strtotime("last Monday", strtotime($today)));
        $current_year = (int)date("Y", strtotime($startDate));
        $current_month = (int)date("n", strtotime($startDate));
        $current_day = (int)date('j', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime("+6 days", strtotime($startDate)));

        $month_names = [
            1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June",
            7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"
        ];

        $week_days = [];
        $weekday_names = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $week_days[] = $currentDate;
            $weekday_names[] = date('l', strtotime($currentDate));
            $currentDate = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
        }

        return view('components.weeklyCalendar',
            compact('today',
                'current_year',
                'current_month',
                'current_day',
                'startDate',
                'endDate',
                'month_names',
                'week_days',
                'weekday_names',
                'highlight_day'
            ));
    }


    public function rooms()
    {
        return view('components.rooms');
    }

    public function getCellData(Request $request)
    {
        $date = $request->input('date');
        $slot = $request->input('slot');

        $date = date('Y-m-d', strtotime($date));

        $practiceClass = $this->practiceClass->where(['schedule_date' => $date ,'session' => $slot])->first();

        $responseHTML = "$practiceClass";

        // Return the HTML response
        return response($responseHTML);
    }

    public function filter(Request $request)
    {
        $room = $request->room;

        return response($room);
    }

    public function registerSchedule(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->input('date')));
        $slot = $request->input('slot');

        $newPracticeClass = $this->practiceClass->create([
            'ten_lop_thuc_hanh' => 'Lap trinh C (test)',
            'schedule_date' => $date,
            'session' => $slot,
            'practice_room_id' => 1,
            'teacher_id' => 1,
            'module_id' => 1
        ]);

        if ($newPracticeClass) {
            $response = ['status' => 'success'];
        }else {
            $response = ['status' => 'error'];
        }


        return $response;
    }
}
