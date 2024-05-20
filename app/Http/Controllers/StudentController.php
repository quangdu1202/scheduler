<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Registration\Registration;
use App\Models\Student\Student;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Registration\RegistrationService;
use App\Services\Schedule\Contracts\ScheduleServiceInterface;
use App\Services\Student\StudentService;
use App\Services\Teacher\TeacherService;
use DateTime;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Route;

class StudentController extends Controller
{
    /**
     * @var ModuleClassServiceInterface
     */
    protected ModuleClassServiceInterface $moduleClassService;

    /**
     * @var PracticeClassServiceInterface
     */
    protected PracticeClassServiceInterface $practiceClassService;

    /**
     * @var ScheduleServiceInterface
     */
    protected ScheduleServiceInterface $scheduleService;

    /**
     * @var ModuleServiceInterface
     */
    protected ModuleServiceInterface $moduleService;

    /**
     * @var TeacherService
     */
    protected TeacherService $teacherService;

    /**
     * @var StudentService
     */
    protected StudentService $studentService;

    /**
     * @var RegistrationService
     */
    protected RegistrationService $scheduleRegistrationService;

    /**
     * @var PracticeRoomService
     */
    protected PracticeRoomService $practiceRoomService;

    /**
     * @var RegistrationService
     */
    protected RegistrationService $registrationService;

    /**
     * @var Helper
     */
    protected Helper $helper;

    /**
     * @var FlasherInterface
     */
    protected FlasherInterface $flasher;

    /**
     * @param ModuleClassServiceInterface $moduleClassService
     * @param PracticeClassServiceInterface $practiceClassService
     * @param ScheduleServiceInterface $scheduleService
     * @param ModuleServiceInterface $moduleService
     * @param RegistrationService $scheduleRegistrationService
     * @param TeacherService $teacherService
     * @param StudentService $studentService
     * @param PracticeRoomService $practiceRoomService
     * @param RegistrationService $registrationService
     * @param Helper $helper
     * @param FlasherInterface $flasher
     */
    public function __construct(
        ModuleClassServiceInterface   $moduleClassService,
        PracticeClassServiceInterface $practiceClassService,
        ScheduleServiceInterface      $scheduleService,
        ModuleServiceInterface        $moduleService,
        RegistrationService           $scheduleRegistrationService,
        TeacherService                $teacherService,
        StudentService                $studentService,
        PracticeRoomService           $practiceRoomService,
        RegistrationService           $registrationService,
        Helper                        $helper,
        FlasherInterface              $flasher,
    )
    {
        $this->middleware('student');
        $this->moduleClassService = $moduleClassService;
        $this->practiceClassService = $practiceClassService;
        $this->scheduleService = $scheduleService;
        $this->moduleService = $moduleService;
        $this->scheduleRegistrationService = $scheduleRegistrationService;
        $this->teacherService = $teacherService;
        $this->studentService = $studentService;
        $this->practiceRoomService = $practiceRoomService;
        $this->registrationService = $registrationService;
        $this->helper = $helper;
        $this->flasher = $flasher;
    }

    public function index()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;

        $availableModules = $this->helper->getModulesByStudentId($student->id);

        return view('student.register-classes', [
            'modules' => $availableModules
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getJsonDataForScheduleTable()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;
        $registrations = $this->registrationService->getAll(['student_id' => $student->id]);

        $practiceClassesMapped = [];
        foreach ($registrations as $registration) {
            /**@var Registration $registration */
            $practiceClass = $registration->practiceClass;
            $signatureSchedule = $practiceClass->schedules->where('order', '=', 0)->first();
            $weekday = date('N', strtotime($signatureSchedule->schedule_date));

            $practiceClassesMapped[$practiceClass->id] = [
                'schedules' => $signatureSchedule,
                'weekday' => $weekday,
                'session' => $signatureSchedule->session ?? null,
                'shift' => $registration->shift,
                'practice_class_id' => $practiceClass->id,
                'practice_class_code' => $practiceClass->practice_class_code,
                'practice_class_name' => $practiceClass->practice_class_name,
                'room_name' => $signatureSchedule->practiceRoom->name ?? null,
                'room_location' => $signatureSchedule->practiceRoom->location ?? null,
            ];

        }

        $indexedClasses = [];
        foreach ($practiceClassesMapped as $class) {
            $weekday = $class['weekday']; // e.g., 1 for Monday
            $session = $class['session']; // e.g., 1, 2, or 3
            $indexedClasses[$weekday][$session] = $class;
        }

//        $practiceClasses = $this->registrationService->getAll(['student_id' => $student->id]);
//
//        $practiceClassesMapped = $practiceClasses->mapWithKeys(function ($practiceClass) {
//            /**@var PracticeClass $practiceClass */
//            $signatureSchedule = $practiceClass->schedules->where('order', '=', 0)->first();
//
//            // Compute the day of the week, ensuring there is a date to process
//            $weekday = $signatureSchedule ? date('N', strtotime($signatureSchedule->schedule_date)) : null;
//
//            if ($weekday == null) {
//                return [];
//            }
//
//            return [
//                $practiceClass->id => [
//                    'schedules' => $signatureSchedule,
//                    'weekday' => $weekday,
//                    'session' => $signatureSchedule->session ?? null,
//                    'practice_class_id' => $practiceClass->id,
//                    'practice_class_code' => $practiceClass->practice_class_code,
//                    'practice_class_name' => $practiceClass->practice_class_name,
//                    'room_name' => $signatureSchedule->practiceRoom->name ?? null,
//                    'room_location' => $signatureSchedule->practiceRoom->location ?? null,
//                ]
//            ];
//        });
//
//        $indexedClasses = [];
//        foreach ($practiceClassesMapped as $class) {
//            $weekday = $class['weekday']; // e.g., 1 for Monday
//            $session = $class['session']; // e.g., 1, 2, or 3
//            $indexedClasses[$weekday][$session] = $class;
//        }

        $responseData = [];

        for ($i = 1; $i <= 3; $i++) { // sessions 1 to 3
            $entry = [
                'index' => $i,
                'row_session' => '<strong class="text-danger">' . ($i == 1 ? 'S' : ($i == 2 ? 'C' : 'T')) . '</strong>'
            ];
            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

            foreach ($days as $index => $day) {
                $dayIndex = $index + 1; // Convert day string to day index (1 = Monday, 2 = Tuesday, etc.)

                // Cell for holding split rows
                $entry[$day] = '<div class="cell-row-split m-0">';

                for ($j = 1; $j <= 2; $j++) {
                    // Start split rows in cell
                    $entry[$day] .= '<div class="row-split p-0">';

                    // If there is class on a session of weekday
                    if (isset($indexedClasses[$dayIndex][$i])) {
                        $classInfo = $indexedClasses[$dayIndex][$i];

                        if ($classInfo['shift'] == $j) {
                            $entry[$day] .= "<div style='font-size: 13px; cursor: pointer' class='text-start position-relative m-1 p-1 pe-3 border border-primary rounded registered-class'>";

                            if (Route::currentRouteName() == 'student.get-schedule-table') {
                                $entry[$day] .= "<span class='position-absolute top-0 end-0 px-1 py-0 btn btn-sm text-danger cancel-class-btn' data-pclass-id=\"{$classInfo['practice_class_id']}\"><i class='fa-solid fa-xmark'></i></span>";
                            }

                            $entry[$day] .= "
                                <div>{$classInfo['practice_class_code']}</div>
                                    <strong>{$classInfo['practice_class_name']}</strong><br>
                                    <strong>{$classInfo['room_name']}</strong> <span>({$classInfo['room_location']})</span><br>
                                </div>
                            ";
                        } else {
                            if (Route::currentRouteName() == 'student.get-schedule-table') {
                                $entry[$day] .= '
                                <button type="button"
                                        class="border-0 btn btn-outline-primary w-100 schedule-table-add-btn"
                                        data-get-url="' . route('teacher.get-classes-ondate') . '"
                                        data-weekday="' . $dayIndex . '"
                                        data-session="' . $i . '"
                                        data-shift="' . $j . '">
                                    +
                                </button>
                            ';
                            } else {
                                $entry[$day] = '-';
                            }
                        }
                    } else {
                        if (Route::currentRouteName() == 'student.get-schedule-table') {
                            $entry[$day] .= '
                                <button type="button"
                                        class="border-0 btn btn-outline-primary w-100 schedule-table-add-btn"
                                        data-get-url="' . route('teacher.get-classes-ondate') . '"
                                        data-weekday="' . $dayIndex . '"
                                        data-session="' . $i . '"
                                        data-shift="' . $j . '">
                                    +
                                </button>
                            ';
                        } else {
                            $entry[$day] = '-';
                        }
                    }
                    // End split rows in cell
                    $entry[$day] .= '</div>';
                }
                // End Cell for holding split rows
                $entry[$day] .= '</div>';
            }

            $responseData[] = $entry;
        }
        return response()->json($responseData);
    }

    /**
     * @return JsonResponse
     */
    public function getAvailableClasses()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;

        $availableModulesIds = $this->helper->getModulesByStudentId($student->id)->pluck('id')->all();
        $registeredModulesIds = $student->registrations->pluck('practice_class_id')->all();

        $practiceClasses = $this->practiceClassService
            ->withCount(['schedules'])
            ->getAll([['id', 'not_in', $registeredModulesIds], ['status', '=', '3'], ['module_id', 'in', $availableModulesIds]]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            /**@var PracticeClass $pclass */

            $module_info = "({$pclass->module->module_code}) {$pclass->module->module_name}";
            $schedulesQty = floor($pclass->schedules_count / 2);

            $classInfo = "
                <div>
                    <span class='d-block fw-bold text-primary'>$pclass->practice_class_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$pclass->practice_class_code</strong> - </span>
                        <span class='d-inline-block'><strong>$schedulesQty</strong> schedules - </span>
                        <span class='d-inline-block'><strong>$pclass->shift_qty</strong> shifts</span>
                    </div>
                </div>
            ";

            $signatureSchedule = $pclass->getSignatureSchedule();
            $start_date = '<strong class="btn-sm form-control">' . ($signatureSchedule != null ? $signatureSchedule->schedule_date : 'No info') . '</button>';

            $session_text = match ($signatureSchedule->session) {
                1 => 'S',
                2 => 'C',
                3 => 'T',
                default => null
            };
            $date = new DateTime($signatureSchedule->schedule_date);
            $weekday_int = (int)$date->format('N');
            $weekday_text = match ($weekday_int) {
                1 => 'T2',
                2 => 'T3',
                3 => 'T4',
                4 => 'T5',
                5 => 'T6',
                6 => 'T7',
                7 => 'CN',
                default => null,
            };
            $schedule_text = $session_text . '_' . $weekday_text;

            $maxStudentsOfShifts = $this->helper->getMaxStudentOfShifts($pclass);
            $k1MaxQty = $maxStudentsOfShifts['studentQty1'];
            $k2MaxQty = $maxStudentsOfShifts['studentQty2'];

            $k1RegisteredQty = $this->registrationService->getAll([['practice_class_id' => $pclass->id], ['shift' => 1]])->count();
            $k2RegisteredQty = $this->registrationService->getAll([['practice_class_id' => $pclass->id], ['shift' => 2]])->count();

            $k1Qty = $k1RegisteredQty . '/' . $k1MaxQty;
            $k2Qty = $k2RegisteredQty . '/' . $k2MaxQty;

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('student.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="'.$signatureSchedule->session.'" data-shift="1">
                    K1
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="'.$signatureSchedule->session.'" data-shift="2">
                    K2
                </button>
            ';

            return [
                'DT_RowId' => $pclass->id,
                // 'DT_RowData' => $pclass,
                'index' => $index + 1,
                'module_id' => $pclass->module_id,
                'module_info' => $module_info,
                'classInfo' => $classInfo,
                'start_date' => $start_date,
                'schedule_text' => $schedule_text,
                'k1Qty' => $k1Qty,
                'k2Qty' => $k2Qty,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @param $practice_class_id
     * @return JsonResponse
     */
    public function getJsonDataForSchedule($practice_class_id): JsonResponse
    {
        $schedulesBySessionId = $this->scheduleService->getAll(['practice_class_id' => $practice_class_id])->where('order', '!=', 0)->sortBy(['schedule_date', 'shift'])->groupBy('session_id');

        $responseData = [];
        $index = 0;

        foreach ($schedulesBySessionId as $schedules) {
            $index++;
            $schedule_date = $schedules[0]->schedule_date;

            $session = match ($schedules[0]->session) {
                1 => '<span class="badge rounded-pill text-bg-success px-3 py-2 fs-6">S</span>',
                2 => '<span class="badge rounded-pill text-bg-warning px-3 py-2 fs-6">C</span>',
                3 => '<span class="badge rounded-pill text-bg-dark px-3 py-2 fs-6">T</span>',
            };

            $shifts = '<div class="cell-row-split">';
            $shifts .= "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block'>K1</strong></div>";
            $shifts .= "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block'>K2</strong></div>";
            $shifts .= '</div>';

            /**@var PracticeRoom $pRoom1 */
            $pRoom1 = $schedules[0]->practiceRoom ?? null;
            /**@var PracticeRoom $pRoom2 */
            $pRoom2 = $schedules[1]->practiceRoom ?? null;

            $practiceRooms = '<div class="cell-row-split">';

            $practiceRooms .= $pRoom1 === null ?
                "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block text-bg-warning'>Not set</strong></div>" :
                "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block'>($pRoom1->location) $pRoom1->name</strong></div>";

            $practiceRooms .= $pRoom2 === null ?
                "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block text-bg-warning'>Not set</strong></div>" :
                "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block'>($pRoom2->location) $pRoom2->name</strong></div>";

            $practiceRooms .= '</div>';

            $responseData[] = [
                'DT_RowData' => $schedules,
                'index' => $index,
                'practice_class_id' => $practice_class_id,
                'schedule_date' => $schedule_date,
                'session' => $session,
                'shifts' => $shifts,
                'practice_room' => $practiceRooms,
            ];
        }

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registerClass(Request $request)
    {
        $studentId = $request->input('studentId');
        $pclassId = $request->input('pclassId');
        $shift = $request->input('shift');

        if ($studentId == null || $pclassId == null || $shift == null) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }

        /**@var ModuleClass $moduleClass*/
        $moduleClass = $this->moduleClassService->with(['students'])->findOrFail(['students.id' => $studentId]);

        try {
            // Create new registration
            $this->registrationService->create([
                'student_id' => $studentId,
                'module_class_id' => $moduleClass->id,
                'practice_class_id' => $pclassId,
                'shift' => $shift
            ]);
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class registered successfully!',
                'hideTarget' => '#pclass-schedules-modal, #pclass-ondate-modal',
                'reloadTarget' => '#registered-pclass-table, #register-schedule-table, #pclass-register-table',
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class registration by teacher failed: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage(),
            ]);
        }
    }


}