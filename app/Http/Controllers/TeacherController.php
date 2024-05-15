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
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * @var Helper
     */
    protected Helper $helper;

    public function __construct(
        ModuleClassServiceInterface   $moduleClassService,
        PracticeClassServiceInterface $practiceClassService,
        ScheduleServiceInterface      $scheduleService,
        ModuleServiceInterface        $moduleService,
        RegistrationService           $scheduleRegistrationService,
        TeacherService                $teacherService,
        PracticeRoomService           $practiceRoomService,
        Helper                        $helper,
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
        $this->helper = $helper;
    }

    public function index()
    {
        $user = auth()->user();
        $availableModules = $this->helper->getModulesByTeacherId($user->userable->id);
        return view('teacher.register-schedule', [
            'modules' => $availableModules
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
            $practiceClass = $this->practiceClassService->findOrFail($pclassId);
            $this->practiceClassService->update($practiceClass, [
                'teacher_id' => $teacherId,
                'status' => 3,
            ]);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class registered successfully!',
                'hideTarget' => '#pclass-schedules-modal',
                'reloadTarget' => '#pclass-register-table, #registered-pclass-table',
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

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class canceled successfully!',
                'reloadTarget' => '#pclass-register-table, #registered-pclass-table',
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
        /**@var Teacher $teacher*/
        $teacher = auth()->user()->userable;
        $practiceClasses = $this->practiceClassService->with(['schedules'])->getAll(['teacher_id' => $teacher->id]);

        $schedules = $practiceClasses->mapWithKeys(function ($practiceClass) {
            // Collect all schedules, setting 'session_id' as key and 'schedule_date' as value
            $schedulesMapped = $practiceClass->schedules->mapWithKeys(function ($schedule) {
                return [$schedule->session_id => ['session' => $schedule->session, 'date' => $schedule->schedule_date]];
            });
            // Remove duplicate dates, keeping the first occurrence
            $uniqueSchedules = $schedulesMapped->unique();

            // Map practice class id to its unique schedules
            return [$practiceClass->id => $uniqueSchedules];
        });

//        dd($schedules);

        $responseData = [];
        for ($i = 1; $i <= 3; $i++) {
            $responseData[] = [
                'row_session' => $i,
                'mon' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'tue' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'wed' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'thu' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'fri' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'sat' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
                'sun' => '<button type="button" class="h-100 w-100 border-0 btn btn-outline-primary schedule-table-add-btn"><i class="lni lni-plus align-middle"></i></button>',
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

            $schedule_dates = $pclass->schedules->sortBy('schedule_date')->first();
            $start_date = '<strong class="btn-sm form-control">' . ($schedule_dates != null ? $schedule_dates->schedule_date : 'No info') . '</button>';
            $max_qty = $pclass->max_qty;

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
                'shift_qty' => $pclass->shift_qty,
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
        $teacherId = auth()->user()->userable->id;

        $practiceClasses = $this->practiceClassService->getAll([['teacher_id', '=', $teacherId]]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            /**@var PracticeClass $pclass */

            $module_info = '(' . $pclass->module->module_code . ') ' . $pclass->module->module_name;

            $registered_qty = $pclass->registered_qty;
            $max_qty = $pclass->max_qty;

            $status = [
                'title' => 'Approved',
                'value' => '<button type="button" class="btn badge rounded-pill text-bg-success status-change-btn" data-status="3">Approved</button>'
            ];

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-info-btn" data-get-url="' . route('teacher.get-class-schedules', ['practice_class_id' => $pclass->id]) . '">
                    <i class="fa-solid fa-magnifying-glass align-middle"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm pclass-student-info-btn" data-get-url="">
                    <i class="fa-solid fa-user-graduate"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm cancel-class-btn" data-pclass-id="' . $pclass->id . '">
                    <i class="lni lni-ban align-middle"></i>
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
                'registered_qty' => $registered_qty . '/' . $max_qty,
                'shift_qty' => $pclass->shift_qty,
                'status' => $status,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @param $practice_class_id
     * @param Request $request
     * @return JsonResponse
     */
    public function getJsonDataForSchedule($practice_class_id, Request $request): JsonResponse
    {
        $schedulesBySessionId = $this->scheduleService->getAll(['practice_class_id' => $practice_class_id])->sortBy(['schedule_date', 'shift'])->groupBy('session_id');

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
            $pRoom1 = $schedules[0]->practiceRoom;
            /**@var PracticeRoom $pRoom2 */
            $pRoom2 = $schedules[1]->practiceRoom;

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
    public function getStudentOfPracticeClass(Request $request): JsonResponse
    {
        $pClassId = $request->input('$pClassId');

        /**@var PracticeClass|null $pClass*/
        $pClass = $this->practiceClassService->findOrFail($pClassId);

        $pClassStudents = $pClass->students;

        $responseData = $pClassStudents->map(function ($student, $index) use ($pClassId) {
            return [
                'DT_RowData' => $student,
                'index' => $index++,
                'student_code' => $student->student_code,
                'student_name' => $student->user->name,
                'student_gender' => 'Male',
                'student_email' => $student->user->email,
                'student_telephone' => '0123456789',
            ];
        });

        return response()->json($responseData);
    }
}
