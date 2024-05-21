<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Teacher\Teacher;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Registration\RegistrationService;
use App\Services\Schedule\Contracts\ScheduleServiceInterface;
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

class TeacherController extends Controller
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

    public function __construct(
        ModuleClassServiceInterface   $moduleClassService,
        PracticeClassServiceInterface $practiceClassService,
        ScheduleServiceInterface      $scheduleService,
        ModuleServiceInterface        $moduleService,
        RegistrationService           $scheduleRegistrationService,
        TeacherService                $teacherService,
        PracticeRoomService           $practiceRoomService,
        RegistrationService           $registrationService,
        Helper                        $helper,
        FlasherInterface              $flasher,
    )
    {
        $this->middleware('teacher');
        $this->moduleClassService = $moduleClassService;
        $this->practiceClassService = $practiceClassService;
        $this->scheduleService = $scheduleService;
        $this->moduleService = $moduleService;
        $this->scheduleRegistrationService = $scheduleRegistrationService;
        $this->teacherService = $teacherService;
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
        /**@var Teacher $teacher */
        $teacher = Auth::user()->userable;
        $availableModules = $this->helper->getModulesByTeacherId($teacher->id);
        return view('teacher.register-classes', [
            'modules' => $availableModules
        ]);
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function manageClasses()
    {
        /**@var Teacher $teacher */
        $teacher = Auth::user()->userable;
        $availableModules = $this->helper->getModulesByTeacherId($teacher->id);
        $practiceRooms = $this->practiceRoomService->getAll();
        $classes = [
            [
                'className' => 'Math',
                'classTime' => '2024-05-22 07:00:00'
            ],
            [
                'className' => 'English',
                'classTime' => '2024-05-23 12:30:00'
            ]
        ];
        return view('teacher.manage-classes', [
            'modules' => $availableModules,
            'practiceRooms' => $practiceRooms,
            'classes' => $classes
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registerClass(Request $request)
    {
        $teacherId = $request->input('teacherId');
        $pclassId = $request->input('pclassId');

        if ($teacherId == null || $pclassId == null) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }

        try {
            /**@var PracticeClass $practiceClass */
            $practiceClass = $this->practiceClassService->findOrFail($pclassId);
            if ($practiceClass->teacher_id != null) {
                return response()->json([
                    'status' => 500,
                    'success' => false,
                    'title' => 'Fail!',
                    'message' => 'This class has been registered by another teacher, please reload the page!',
                ]);
            }

            $this->practiceClassService->update($practiceClass, [
                'teacher_id' => $teacherId,
                'status' => 3,
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelRegisteredClass(Request $request)
    {
        $pclassId = $request->input('pclassId');

        if ($pclassId == null) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }

        try {
            $practiceClass = $this->practiceClassService->findOrFail($pclassId);
            $this->practiceClassService->update($practiceClass, [
                'teacher_id' => null,
                'status' => 1,
            ]);

//            $this->flasher->addSuccess('Practice class canceled successfully!', 'Success!');

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class canceled successfully!',
                'reloadTarget' => '#pclass-register-table, #registered-pclass-table, #register-schedule-table',
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class canceled by teacher failed: {$e->getMessage()}");

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
    public function getJsonDataForScheduleTable()
    {
        /**@var Teacher $teacher */
        $teacher = Auth::user()->userable;
        $practiceClasses = $this->practiceClassService->with(['schedules'])->getAll(['teacher_id' => $teacher->id]);

        $practiceClassesMapped = $practiceClasses->mapWithKeys(function ($practiceClass) {
            /**@var PracticeClass $practiceClass */
            $signatureSchedule = $practiceClass->schedules->where('order', '=', 0)->first();

            // Compute the day of the week, ensuring there is a date to process
            $weekday = $signatureSchedule ? date('N', strtotime($signatureSchedule->schedule_date)) : null;

            if ($weekday == null) {
                return [];
            }

            return [
                $practiceClass->id => [
                    'schedules' => $signatureSchedule,
                    'weekday' => $weekday,
                    'session' => $signatureSchedule->session ?? null,
                    'practice_class_id' => $practiceClass->id,
                    'practice_class_code' => $practiceClass->practice_class_code,
                    'practice_class_name' => $practiceClass->practice_class_name,
                    'room_name' => $signatureSchedule->practiceRoom->name ?? null,
                    'room_location' => $signatureSchedule->practiceRoom->location ?? null,
                ]
            ];
        });

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
                if (isset($indexedClasses[$dayIndex][$i])) {
                    $classInfo = $indexedClasses[$dayIndex][$i];
                    $entry[$day] = "<div style='font-size: 13px; cursor: pointer' class='text-start position-relative m-1 p-1 pe-3 border border-primary rounded registered-class'>";
                    if (Route::currentRouteName() == 'teacher.get-schedule-table') {
                        $entry[$day] .= "<span class='position-absolute top-0 end-0 px-1 py-0 btn btn-sm text-danger cancel-class-btn' data-pclass-id=\"{$classInfo['practice_class_id']}\"><i class='fa-solid fa-xmark'></i></span>";
                    }
                    $entry[$day] .= "
                        <div>{$classInfo['practice_class_code']}</div>
                            <strong>{$classInfo['practice_class_name']}</strong><br>
                            <strong>{$classInfo['room_name']}</strong> <span>({$classInfo['room_location']})</span><br>
                        </div>
                    ";
                } else {
                    if (Route::currentRouteName() == 'teacher.get-schedule-table') {
                        $entry[$day] = '
                        <div class="d-flex align-items-center h-100">
                            <button type="button" 
                                    class="border-0 btn btn-outline-primary flex-grow-1 schedule-table-add-btn" 
                                    data-get-url="' . route('teacher.get-classes-ondate') . '" 
                                    data-weekday="' . $dayIndex . '"
                                    data-session="' . $i . '">
                                +
                            </button>
                        </div>
                    ';
                    } else {
                        $entry[$day] = '-';
                    }
                }
            }

            $responseData[] = $entry;
        }

        return response()->json($responseData);
    }

    public function getClassOndate(Request $request)
    {
        $weekDay = $request->input('weekDay');
        $session = $request->input('session');
        $practiceClasses = $this->practiceClassService->with(['schedules'])->getAll(['status' => 1]);

        // Filter the classes by signature schedule
        $filteredClasses = $practiceClasses->filter(function ($class) use ($weekDay, $session) {
            // Get the signature schedule of the class
            /**@var PracticeClass $class */
            $signatureSchedule = $class->schedules->where('order', '=', 0)->first();

            // If there's no schedule, skip this class
            if (!$signatureSchedule) {
                return false;
            }

            // Convert the schedule_date to a DateTime object to extract the day of the week
            $date = new DateTime($signatureSchedule->schedule_date);
            $dayOfWeek = (int)$date->format('N'); // 'N' gives the day of the week (1 = Monday, 7 = Sunday)

            // Check if the day and session match the request
            return $dayOfWeek == $weekDay && $signatureSchedule->session == $session;
        });

        $responseData = [];
        $index = 0;
        foreach ($filteredClasses as $pclass) {
            $index++;
            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('teacher.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '">
                    Register
                </button>
            ';

            $responseData[] = [
                'index' => $index,
                'practice_class_id' => $pclass->id,
                'module_info' => '(' . $pclass->module->module_code . ') ' . $pclass->module->module_name,
                'practice_class_name' => $pclass->practice_class_name,
                'practice_class_code' => $pclass->practice_class_code,
                'actions' => $actions
            ];
        }

        return response()->json($responseData);
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData(): JsonResponse
    {
        $practiceClasses = $this->practiceClassService->getAll([['status', '=', '1']]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            /**@var PracticeClass $pclass */

            $module_info = '(' . $pclass->module->module_code . ') ' . $pclass->module->module_name;

            $signatureSchedule = $pclass->schedules->where('order', '=', 0)->first();
            $start_date = '<strong class="btn-sm form-control">' . ($signatureSchedule != null ? $signatureSchedule->schedule_date : 'No info') . '</button>';
            $max_qty = $pclass->max_qty;

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

            $status = match ($pclass->status) {
                1 => [
                    'title' => 'Ready for registration',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-primary status-change-btn" data-status="1">Available</button>'
                ],
                2 => [
                    'title' => 'Awaiting Approval',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-warning status-change-btn" data-status="2">Pending Approval</button>'
                ],
                3 => [
                    'title' => 'Approved',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-success status-change-btn" data-status="3">Approved</button>'
                ],
                default => [
                    'title' => 'Unknown',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-dark">Unknown</button>'
                ],
            };

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('teacher.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm register-class-btn" data-pclass-id="' . $pclass->id . '">
                    Register
                </button>
            ';

            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $index + 1,
                'module_id' => $pclass->module_id,
                'module_info' => $module_info,
                'practice_class_code' => $pclass->practice_class_code,
                'practice_class_name' => $pclass->practice_class_name,
                'start_date' => $start_date,
                'max_qty' => $max_qty,
                'schedule_text' => $schedule_text,
                'status' => $status,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @return JsonResponse
     */
    public function getRegisteredClasses(): JsonResponse
    {
        /**@var Teacher $teacher */
        $teacher = Auth::user()->userable;
        $practiceClasses = $this->practiceClassService->withCount(['schedules'])->getAll([['teacher_id', '=', $teacher->id]]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
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
            $scheduleQty = floor($pclass->schedules_count / 2);

            $classInfo = "
                <div>
                    <span class='d-block fw-bold text-primary'>$pclass->practice_class_name</span>
                    <div class='fst-italic'>
                        <span class='d-inline-block'><strong>$pclass->practice_class_code</strong> - </span>
                        <span class='d-inline-block'><strong>$scheduleQty</strong> schedules - </span>
                        <span class='d-inline-block'><strong>$pclass->shift_qty</strong> shifts</span>
                    </div>
                </div>
            ";
            $start_date = $signatureSchedule->schedule_date;
            $signatureWeekday = strtoupper($this->helper->dateToFullCharsWeekday($start_date));

            $maxStudentsOfShifts = $this->helper->getMaxStudentOfShifts($pclass);
            $k1MaxQty = $maxStudentsOfShifts['studentQty1'];
            $k2MaxQty = $maxStudentsOfShifts['studentQty2'];

            $k1RegisteredQty = $pclass->registrations->where('shift', '=', 1)->count();
            $k2RegisteredQty = $pclass->registrations->where('shift', '=', 2)->count();

            $k1Qty = $k1RegisteredQty . '/' . $k1MaxQty;
            $k2Qty = $k2RegisteredQty . '/' . $k2MaxQty;

            $status = match ($pclass->status) {
                1 => [
                    'title' => 'Ready for registration',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-primary status-change-btn" data-status="1">Available</button>'
                ],
                2 => [
                    'title' => 'Awaiting Approval',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-warning status-change-btn" data-status="2">Pending Approval</button>'
                ],
                3 => [
                    'title' => 'Approved',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-success status-change-btn" data-status="3">Approved</button>'
                ],
                default => [
                    'title' => 'Unknown',
                    'value' => '<button type="button" class="btn badge rounded-pill text-bg-dark">Unknown</button>'
                ],
            };

            $actions = '
                <button type="button" class="btn btn-success btn-sm schedule-info-btn" data-get-url="'.route('practice-classes.get-json-data-for-schedule', ['practice_class_id' => $pclass->id]).'" data-pclass-id="'.$pclass->id.'">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm pclass-student-list-btn" data-get-url="'.route('practice-classes.get-students-of-pclass').'" data-pclass-id="'.$pclass->id.'" data-k1qty="'.$k1RegisteredQty.'" data-k2qty="'.$k2RegisteredQty.'">
                    <i class="fa-solid fa-user-graduate"></i>
                </button>
            ';

            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $index + 1,
                'module_info' => $module_info,
                'pclass_info' => $classInfo,
                'start_date' => $start_date,
                'weekday' => $signatureWeekday,
                'k1Qty' => $k1Qty,
                'k2Qty' => $k2Qty,
                'status' => $status,
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
}
