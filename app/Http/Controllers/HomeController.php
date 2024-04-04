<?php

namespace App\Http\Controllers;

use App\Models\PracticeClass\PracticeClass;
use App\Models\User;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    /**
     * @var ModuleServiceInterface
     */
    protected ModuleServiceInterface $module;

    /**
     * @var PracticeRoomServiceInterface
     */
    protected PracticeRoomServiceInterface $room;

    /**
     * @var ModuleClassServiceInterface
     */
    protected ModuleClassServiceInterface $moduleClass;

    /**
     * @param User $user
     * @param PracticeClass $practiceClass
     * @param ModuleServiceInterface $module
     * @param PracticeRoomServiceInterface $room
     * @param ModuleClassServiceInterface $moduleClass
     */
    public function __construct(User                         $user,
                                PracticeClass                $practiceClass,
                                ModuleServiceInterface       $module,
                                PracticeRoomServiceInterface $room,
                                ModuleClassServiceInterface  $moduleClass
    )
    {
//        $this->middleware('auth');
        $this->user = $user;
        $this->practiceClass = $practiceClass;
        $this->module = $module;
        $this->room = $room;
        $this->moduleClass = $moduleClass;
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

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function rooms()
    {
        return view('components.rooms');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function getCellData(Request $request)
    {
        $date = $request->input('date');
        $slot = $request->input('slot');

        $date = date('Y-m-d', strtotime($date));

        $practiceClass = $this->practiceClass->where(['schedule_date' => $date, 'session' => $slot])->first();

        $response = json_encode($practiceClass);

        // Return the HTML response
        return response($response);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        $room = $request->room;
        $practiceClass = $request->class;
        $date = $request->date;

        $filter = [
            'date' => $date,
            'room' => $room,
            'practiceClass' => $practiceClass
        ];

        return $this->index($request, $filter);
    }

    /**
     * @param Request $request
     * @param $filter
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request, $filter = null)
    {
        $today = now();
        $selectedDate = $request->date ? Carbon::parse($request->date) : $today;
        if ($filter) {
            $selectedDate = Carbon::parse($filter['date']);
            $practiceClasses = $this->practiceClass
                ->whereYear('schedule_date', $selectedDate->year)
                ->whereMonth('schedule_date', $selectedDate->month)
                ->where('practice_room_id', $filter['room'])
                ->get();
        } else {
            $practiceClasses = $this->practiceClass->all();
        }

        $modules = $this->module->getAll();

        $rooms = $this->room->getAll();

        $monthDays = $this->getMonthDays($selectedDate);

        $weekday_names = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $highlight_day = $selectedDate->format('Y-m-d');

        return view('components.calendar', [
            'filter',
            'today' => $today,
            'weekday_names' => $weekday_names,
            'highlight_day' => $highlight_day,
            'selectedDate' => $selectedDate,
            'monthDays' => $monthDays,
            'modules' => $modules,
            'rooms' => $rooms,
            'practiceClasses' => $practiceClasses,
        ]);
    }

    /**
     * @param Carbon $date
     * @return array
     */
    private function getMonthDays(Carbon $date)
    {
        $firstDayOfMonth = $date->copy()->startOfMonth();
        $lastDayOfMonth = $date->copy()->endOfMonth();

        // Determine the start padding based on the day of the week (0 = Monday, 6 = Sunday)
        $startPadding = $firstDayOfMonth->dayOfWeek === 0 ? 6 : $firstDayOfMonth->dayOfWeek - 1;

        // Add days from the previous month
        $prevMonthLastDay = $firstDayOfMonth->copy()->subDays($startPadding);

        // Determine the end padding based on the day of the week (0 = Monday, 6 = Sunday)
        $endPadding = $lastDayOfMonth->dayOfWeek === 6 ? 0 : 7 - $lastDayOfMonth->dayOfWeek;

        // Determine the first day of next month
        $nextMonthFirstDay = $lastDayOfMonth->copy()->addDays();

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
     * @return string[]
     */
    public function registerSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'slot' => 'required|numeric',
            'recurring' => 'required|numeric'
        ]);

        $date = date('Y-m-d', strtotime($request->date));
        $slot = $request->slot;
        $recurring = $request->recurring;

        $response = [];

        switch ($recurring) {
            case 0:
                // Non-recurring, single registration
                $response = $this->createPracticeClass($date, $slot) ? ['status' => 'success'] : ['status' => 'error'];
                break;
            case 1:
                // Weekly recurring registration
                for ($week = 0; $week < 10; $week++) {
                    $newDate = date('Y-m-d', strtotime("+$week week", strtotime($date)));
                    if (!$this->createPracticeClass($newDate, $slot)) {
                        return ['status' => 'error'];
                    }
                }
                $response = ['status' => 'success'];
                break;
            case 2:
                // Biweekly recurring registration
                for ($week = 0; $week < 5; $week++) {
                    $newDate = date('Y-m-d', strtotime("+" . ($week * 2) . " weeks", strtotime($date)));
                    if (!$this->createPracticeClass($newDate, $slot)) {
                        return ['status' => 'error'];
                    }
                }
                $response = ['status' => 'success'];
                break;
            default:
                return ['status' => 'error'];
        }

        return $response;
    }

    /**
     * @param $date
     * @param $slot
     * @return PracticeClass
     */
    private function createPracticeClass($date, $slot)
    {
        return $this->practiceClass->create([
            'practice_class_name' => 'Lập trình C (test)',
            'schedule_date' => $date,
            'session' => $slot,
            'practice_room_id' => 1,
            'teacher_id' => 1,
            'module_id' => 1
        ]);
    }

}
