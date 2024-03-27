<?php

namespace App\Http\Controllers;

use App\Models\PracticeClass;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $user;
    protected $practiceClass;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user,
                                PracticeClass $practiceClass
    )
    {
//        $this->middleware('auth');
        $this->user = $user;
        $this->practiceClass = $practiceClass;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $today = date("Y-m-d");

        $practiceClasses = $this->practiceClass->all();

        $dateParts = explode('-', isset($request->date) ? $request->date : $today);
        $current_year = intval($dateParts[0]);
        $current_month = intval($dateParts[1]);
        $current_day = isset($dateParts[2]) ? intval($dateParts[2]) : 1;
        $highlight_day[] = $request->date;

        $month_names = [
            1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June",
            7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"
        ];

        $first_day_of_month = date("Y-m-01", strtotime("$current_year-$current_month-01"));
        $last_day_of_month = date("Y-m-t", strtotime($first_day_of_month));

        $start_day = date('N', strtotime($first_day_of_month));
        $end_day = date('N', strtotime($last_day_of_month));

        $start_padding = ($start_day - 1) % 7;
        $end_padding = 7 - $end_day;

        $start_date = date('Y-m-d', strtotime("-$start_padding day", strtotime($first_day_of_month)));
        $end_date = date('Y-m-d', strtotime("+$end_padding day", strtotime($last_day_of_month)));

        $month_days = [];

        // Loop through each day
        $current_date = $start_date;
        while ($current_date <= $end_date) {
            $month_days[] = $current_date;
            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
        }

        $weekday_names = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

        $prev_month = $current_month - 1 == 0 ? 12 : $current_month - 1;
        $prev_year = $current_month - 1 == 0 ? $current_year -1 : $current_year;

        $next_month = $current_month + 1 > 12 ? 1 : $current_month + 1;
        $next_year = $current_month + 1 > 12 ? $current_year + 1 : $current_year;

        return view('components.calendar',
            compact('today',
                'current_year',
                'current_month',
                'current_day',
                'month_names',
                'month_days',
                'weekday_names',
                'start_padding',
                'prev_month',
                'prev_year',
                'next_month',
                'next_year',
                'start_padding',
                'end_padding',
                'highlight_day',
                'practiceClasses'
            ));
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

        // Build the HTML response with the retrieved data
        $responseHTML = '
            <h2>Cell Data for '.$date.'</h2>
            <h3>Selected slot: '.$slot.'</h3>
            <p>'. "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum." .'</p>
        ';

        // Return the HTML response
        return response($responseHTML);
    }
}
