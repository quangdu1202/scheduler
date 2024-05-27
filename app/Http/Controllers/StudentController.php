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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;
        $availableModules = $this->helper->getModulesByStudentId($student->id);
        $nextClasses = $this->helper->getNextSchedulesOfStudent($student->id);

        return view('student.register-classes', [
            'modules' => $availableModules,
            'classes' => $nextClasses
        ]);
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function manageClasses()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;
        $availableModules = $this->helper->getModulesByStudentId($student->id);
        $practiceRooms = $this->practiceRoomService->getAll();
        $nextClasses = $this->helper->getNextSchedulesOfStudent($student->id);
        return view('student.manage-classes', [
            'modules' => $availableModules,
            'practiceRooms' => $practiceRooms,
            'classes' => $nextClasses
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getJsonDataForScheduleTable()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;
        $registrations = $student->registrations;

        $practiceClassesMapped = [];
        foreach ($registrations as $registration) {
            /**@var Registration $registration */
            $practiceClass = $registration->practiceClass;
            $signatureSchedule = $practiceClass->schedules->where('order', '=', 0)->first();
            $weekday = date('N', strtotime($signatureSchedule->schedule_date));

            $practiceClassesMapped[$practiceClass->id] = [
                'registration_id' => $registration->id,
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
                                $entry[$day] .= "<span class='position-absolute top-0 end-0 px-1 py-0 btn btn-sm text-danger cancel-class-btn' data-registration-id=\"{$classInfo['registration_id']}\"><i class='fa-solid fa-xmark'></i></span>";
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
                                        data-get-url="' . route('student.get-classes-ondate') . '"
                                        data-weekday="' . $dayIndex . '"
                                        data-session="' . $i . '"
                                        data-shift="' . $j . '">
                                    +
                                </button>
                            ';
                            } else {
                                $entry[$day] .= '-';
                            }
                        }
                    } else {
                        if (Route::currentRouteName() == 'student.get-schedule-table') {
                            $entry[$day] .= '
                                <button type="button"
                                        class="border-0 btn btn-outline-primary w-100 schedule-table-add-btn"
                                        data-get-url="' . route('student.get-classes-ondate') . '"
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
     * @throws Exception
     */
    public function getAvailableClasses()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;

        $availableModulesIds = $this->helper->getModulesByStudentId($student->id)->pluck('id')->all();
        $registeredModulesIds = $student->registrations->pluck('practice_class_id')->all();

        $practiceClasses = $this->practiceClassService
            ->with(['registrations'])
            ->withCount(['schedules'])
            ->getAll()
            ->whereIn('module_id', $availableModulesIds)
            ->whereNotIn('id', $registeredModulesIds)
            ->where('status', '=', '3')
        ;

        $index = 0;
        $responseData = [];

        foreach ($practiceClasses as $pclass) {
            /**@var PracticeClass $pclass */

            $module = $pclass->module;
            $module_info = "
                <div>
                    <span class='d-block fw-bold text-primary'>$module->module_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$module->module_code</strong></span>
                    </div>
                </div>
            ";
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

            $teacher_name = $pclass->teacher ? $pclass->teacher->user->name : '<i>Not set</i>';

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

            $k1RegisteredQty = $pclass->registrations->where('shift', '=', 1)->count();
            $k2RegisteredQty = $pclass->registrations->where('shift', '=', 2)->count();

            $k1Qty = $k1RegisteredQty . '/' . $k1MaxQty;
            $k2Qty = $k2RegisteredQty . '/' . $k2MaxQty;

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('student.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="' . $signatureSchedule->session . '" data-shift="1">
                    K1
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="' . $signatureSchedule->session . '" data-shift="2">
                    K2
                </button>
            ';

            $responseData[] = [
                'DT_RowId' => $pclass->id,
                'index' => ++$index,
                'module_id' => $pclass->module_id,
                'module_info' => $module_info,
                'classInfo' => $classInfo,
                'teacher_name' => $teacher_name,
                'start_date' => $start_date,
                'schedule_text' => $schedule_text,
                'k1Qty' => $k1Qty,
                'k2Qty' => $k2Qty,
                'actions' => $actions
            ];
        }

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

        try {
            /**@var ModuleClass $moduleClass */
            $moduleClass = $this->moduleClassService->with(['students'])->findOrFail(['students.id' => $studentId]);

            /**@var PracticeClass $pclass*/
            $pclass = $this->practiceClassService->findOrFail($pclassId);
            $maxStudentsOfShifts = $this->helper->getMaxStudentOfShifts($pclass);

            $shiftKey = 'studentQty' . $shift;
            $maxQty = $maxStudentsOfShifts[$shiftKey];
            $registeredQty = $pclass->registrations->where('shift', $shift)->count();

            if ($registeredQty >= $maxQty) {
                return response()->json([
                    'success' => false,
                    'status' => 500,
                    'title' => 'Error!',
                    'message' => 'This class session has no more seat',
                ]);
            }

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
            Log::error("Practice Class registration by student failed: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelRegisteredClass(Request $request)
    {
        $registrationId = $request->input('registrationId');

        if ($registrationId == null) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }

        try {
            $this->registrationService->delete($registrationId);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Class canceled successfully!',
                'reloadTarget' => '#pclass-register-table, #registered-pclass-table, #register-schedule-table',
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class canceled by student failed: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getRegisteredClasses()
    {
        /**@var Student $student */
        $student = Auth::user()->userable;
        $practiceClasses = $student->practiceClasses;

        $responseData = $practiceClasses->map(function ($pclass, $index) use ($student) {
            $module = $pclass->module;
            $module_info = "
                <div>
                    <span class='d-block fw-bold text-primary'>$module->module_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$module->module_code</strong></span>
                    </div>
                </div>
            ";
            $schedulesQty = floor($pclass->schedules->count() / 2);

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

            $teacher_name = $pclass->teacher ? $pclass->teacher->user->name : '<i>Not set</i>';

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

            /**@var Registration $registration */
            $registration = $this->registrationService->find(['practice_class_id' => $pclass->id, 'student_id' => $student->id])->first();
            $shift = $registration->shift;
            $shift_text = $shift == 1 ? 'K1' : 'K2';

            $schedule_text = $session_text . '_' . $weekday_text . '_' . $shift_text;

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('student.get-registered-class-schedules', ['practice_class_id' => $pclass->id, 'shift' => $shift]) . '" data-pclass-id="' . $pclass->id . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
            ';

            return [
                'DT_RowId' => $pclass->id,
                'index' => ++$index,
                'module_id' => $pclass->module_id,
                'module_info' => $module_info,
                'classInfo' => $classInfo,
                'teacher_name' => $teacher_name,
                'start_date' => $start_date,
                'schedule_text' => $schedule_text,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @param $practice_class_id
     * @param $shift
     * @return JsonResponse
     */
    public function getRegisteredClassSchedules($practice_class_id, $shift)
    {
        /**@var PracticeClass $pClass */
        $pClass = $this->practiceClassService->findOrFail($practice_class_id);
        $schedules = $pClass->schedules->where('shift', '=', $shift);

        $responseData = [];
        $index = 0;
        foreach ($schedules as $schedule) {
            $weekday = $this->helper->dateToFullCharsWeekday($schedule->schedule_date);
            $weekdayText = '<span class="weekday-text">' . strtoupper($weekday) . '</span>';

            $schedule_date = $schedule->schedule_date;
            $session = match ($schedule->session) {
                1 => 'S',
                2 => 'C',
                3 => 'T'
            };

            $practiceRoom = $schedule->practiceRoom->name . ' - ' . $schedule->practiceRoom->location;

            $responseData[] = [
                'index' => ++$index,
                'practice_class_id' => $pClass->id,
                'weekday' => $weekdayText,
                'schedule_date' => $schedule_date,
                'session' => $session,
                'shift' => 'K' . $shift,
                'practice_room' => $practiceRoom,
            ];
        }

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getClassOndate(Request $request)
    {
        /**@var Student $student*/
        $student = Auth::user()->userable;

        $registeredClassIds = $student->practiceClasses->pluck('id')->all();

        $weekDay = $request->input('weekDay');
        $session = $request->input('session');
        $shift = $request->input('shift');

        $practiceClasses = $this->practiceClassService->with(['schedules'])->getAll(['status' => 3])->whereNotIn('id', $registeredClassIds);

        $filteredClasses = $practiceClasses->filter(function ($class) use ($weekDay, $session) {
            /**@var PracticeClass $class */
            $signatureSchedule = $class->getSignatureSchedule();

            if (!$signatureSchedule) {
                return false;
            }

            $date = new DateTime($signatureSchedule->schedule_date);
            $dayOfWeek = (int)$date->format('N');

            return $dayOfWeek == $weekDay && $signatureSchedule->session == $session;
        });

        $responseData = [];
        $index = 0;
        foreach ($filteredClasses as $pclass) {
            ++$index;

            /**@var PracticeClass $pclass */
            $signatureSchedule = $pclass->getSignatureSchedule();

            $module = $pclass->module;
            $module_info = "
                <div>
                    <span class='d-block fw-bold text-primary'>$module->module_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$module->module_code</strong></span>
                    </div>
                </div>
            ";

            $schedulesQty = floor($pclass->schedules->count() / 2);
            $class_info = "
                <div>
                    <span class='d-block fw-bold text-primary'>$pclass->practice_class_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$pclass->practice_class_code</strong> - </span>
                        <span class='d-inline-block'><strong>$schedulesQty</strong> schedules</span>
                    </div>
                </div>
            ";

            $teacher_name = $pclass->teacher ? $pclass->teacher->user->name : '<i>Not set</i>';

            $maxStudentsOfShifts = $this->helper->getMaxStudentOfShifts($pclass);
            $k1MaxQty = $maxStudentsOfShifts['studentQty1'];
            $k2MaxQty = $maxStudentsOfShifts['studentQty2'];

            $k1RegisteredQty = $pclass->registrations->where('shift', '=', 1)->count();
            $k2RegisteredQty = $pclass->registrations->where('shift', '=', 2)->count();

            $k1Qty = $k1RegisteredQty . '/' . $k1MaxQty;
            $k2Qty = $k2RegisteredQty . '/' . $k2MaxQty;

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('student.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="' . $signatureSchedule->session . '" data-shift="1">
                    K1
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '" data-session="' . $signatureSchedule->session . '" data-shift="2">
                    K2
                </button>
            ';

            $responseData[] = [
                'index' => $index,
                'module_info' => $module_info,
                'class_info' => $class_info,
                'teacher_name' => $teacher_name,
                'k1Qty' => $k1Qty,
                'k2Qty' => $k2Qty,
                'actions' => $actions,
            ];
        }

        return response()->json($responseData);
    }
}