<?php

namespace App\Http\Controllers\PracticeClass;

use App\Helper\Helper;
use App\Http\Resources\PracticeClass\PracticeClassResource;
use App\Models\Module\Module;
use App\Models\PracticeClass\PracticeClass;
use App\Models\PracticeRoom\PracticeRoom;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Registration\RegistrationService;
use App\Services\Schedule\Contracts\ScheduleServiceInterface;
use App\Services\Teacher\TeacherService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class PracticeClassController
 */
class PracticeClassController extends Controller
{
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

    protected PracticeRoomService $practiceRoomService;

    /**
     * @var Helper
     */
    protected Helper $helper;

    /**
     * @param PracticeClassServiceInterface $practiceClassService
     * @param ScheduleServiceInterface $scheduleService
     * @param ModuleServiceInterface $moduleService
     * @param RegistrationService $scheduleRegistrationService
     * @param TeacherService $teacherService
     * @param PracticeRoomService $practiceRoomService
     * @param Helper $helper
     */
    public function __construct(
        PracticeClassServiceInterface $practiceClassService,
        ScheduleServiceInterface      $scheduleService,
        ModuleServiceInterface        $moduleService,
        RegistrationService           $scheduleRegistrationService,
        TeacherService                $teacherService,
        PracticeRoomService           $practiceRoomService,
        Helper                        $helper,
    )
    {
        $this->practiceClassService = $practiceClassService;
        $this->scheduleService = $scheduleService;
        $this->moduleService = $moduleService;
        $this->scheduleRegistrationService = $scheduleRegistrationService;
        $this->teacherService = $teacherService;
        $this->practiceRoomService = $practiceRoomService;
        $this->helper = $helper;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $modules = $this->moduleService->getAll();
        $practiceClasses = $this->practiceClassService->getAll();
        $teachers = $this->teacherService->getAll();
        $practiceRooms = $this->practiceRoomService->getAll();

        return view('practice_class.index', [
            'modules' => $modules,
            'practiceClasses' => $practiceClasses,
            'teachers' => $teachers,
            'practiceRooms' => $practiceRooms
        ]);
    }

    /**
     * @param PracticeClass $practiceClass
     *
     * @return PracticeClassResource
     */
    public function show(PracticeClass $practiceClass): PracticeClassResource
    {
        return PracticeClassResource::make($practiceClass);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        $data = $request->all();

        $validator = Validator::make($data, [
            // Validation rules
        ]);

        if ($validator->fails()) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            if (isset($data['multi_create'])) {
                /**@var Module|null $module */
                $module = $this->moduleService->findOrFail($data['module_id']);
                $multi_qty = $data['multi_qty'];

                $practiceClasses = $this->practiceClassService->find(['module_id' => $module->id]);

                // Find the last practice class based on the index of the practice_class_code
                $lastPracticeClass = $practiceClasses->sortByDesc('practice_class_code')->first();

                if ($lastPracticeClass)
                    $lastIndex = intval(substr($lastPracticeClass->practice_class_code, -3));
                else
                    $lastIndex = 0;

                for ($i = 1; $i <= $multi_qty; $i++) {
                    $practiceClassData = [
                        'module_id' => $module->id,
                        'practice_class_name' => $module->module_name,
                        'registered_qty' => 0,
                        'shift_qty' => 2,
                        'max_qty' => null,
                        'status' => 0,
                    ];

                    $newIndex = $lastIndex + $i;

                    // Format the new practice_class_code
                    $practiceClassData['practice_class_code'] = $module->module_code . 'TH' . str_pad($newIndex, 3, '0', STR_PAD_LEFT);

                    $newPracticeClass = $this->practiceClassService->create($practiceClassData);
                }
            } else {
                $newPracticeClass = $this->practiceClassService->create($data);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Class(es) created successfully!',
                'reloadTarget' => '#pclass-management-table',
                'resetTarget' => '#new-pclass-form'
            ]);
        } catch (Exception $e) {
            Log::error("Practice Class creation failed: {$e->getMessage()}");
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param PracticeClass $practiceClass
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(PracticeClass $practiceClass, Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            // Validation rules
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $editedPclass = $this->practiceClassService->update($practiceClass, $data);
            // Return a successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Class updated successfully!',
                'reloadTarget' => '#pclass-management-table',
                'hideTarget' => '#edit-pclass-modal'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param PracticeClass $practiceClass
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(PracticeClass $practiceClass, Request $request): JsonResponse
    {
        $confirmDelete = $request->input('confirmDelete');

        if ($confirmDelete !== "delete") {
            return response()->json([
                'status' => 500,
                'title' => 'Caution!',
                'message' => 'Please confirm to delete!',
            ]);
        }

        try {
            $this->practiceClassService->delete($practiceClass);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class deleted successfully!',
                'resetTarget' => '#delete-pclass-form',
                'hideTarget' => '#delete-pclass-modal',
                'reloadTarget' => '#pclass-management-table',
            ]);
        } catch (Exception $e) {
            Log::error("Practice Class delete failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData(): JsonResponse
    {
        $practiceClasses = $this->practiceClassService->withCount(['schedules'])->getAll();

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            /**@var PracticeClass $pclass */

            $registered_qty = $pclass->registered_qty;
            $max_qty = $pclass->max_qty;

            $status = match ($pclass->status) {
                0 => [
                    'title' => 'Not available for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $pclass->id . '" type="checkbox" id="' . $pclass->id . '-status">
                                  <label for="' . $pclass->id . '-status" title="Not available for registration">Created</label>
                                </div>'
                ],
                1 => [
                    'title' => 'Ready for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $pclass->id . '" type="checkbox" id="' . $pclass->id . '-status" checked>
                                  <label for="' . $pclass->id . '-status" title="Ready for registration">Ready</label>
                                </div>'
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

            $actions = '<div class="dropdown">
                            <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="lni lni-angle-double-down align-middle"></i>
                            </button>
                            <div class="dropdown-menu">
                                <div class="d-flex justify-content-evenly">
                                    <button type="button" class="btn btn-success btn-sm schedule-info-btn" data-get-url="' . route('practice-classes.get-json-data-for-schedule', ['practice_class_id' => $pclass->id]) . '" data-pclass-id="' . $pclass->id . '">
                                        <i class="fa-solid fa-magnifying-glass align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm pclass-student-info-btn" data-get-url="' . route('practice-classes.get-student-data-for-schedule', ['recurring_id' => $pclass->recurring_id]) . '">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm pclass-edit-btn" data-post-url="' . route('practice-classes.update', ['practice_class' => $pclass]) . '">
                                        <i class="lni lni-pencil-alt align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm pclass-delete-btn">
                                        <i class="lni lni-trash-can align-middle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>';

            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $index + 1,
                'module_id' => $pclass->module_id,
                'practice_class_code' => $pclass->practice_class_code,
                'practice_class_name' => $pclass->practice_class_name,
                'teacher' => $pclass->teacher != null ? $pclass->teacher->user->name : '<i>Not set</i>',
                'teacher_id' => $pclass->teacher_id,
                'registered_qty' => $max_qty == null ? '<i>Not set</i>' : $registered_qty . '/' . $max_qty,
                'max_qty' => $max_qty,
                'schedules_count' => $pclass->schedules_count / 2,
                'status' => $status,
                'status_raw' => $pclass->status,
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
            $teacher = $schedules[0]->practiceClass->teacher;
            $sessionId = $schedules[0]->session_id;

            $index++;
            $schedule_date = '<input type="date" name="schedule_date" class="form-control d-inline-block schedule-date-select" value="' . $schedules[0]->schedule_date . '">';
            $shifts = $practiceRooms = '<div class="cell-row-split">';
            $shift = 0;

            foreach ($schedules as $schedule) {
                $shift++;

                $practiceRoom = $this->practiceRoomService
                    ->with(['schedules'])
                    ->getAll()
                    ->filter(function ($room) use ($schedule, $shift) {
                        return $room->id === $schedule->practice_room_id
                            && $room->schedules->contains(function ($s) use ($schedule, $shift) {
                                return $s->id === $schedule->id && $s->shift == $shift;
                            });
                    })
                    ->first();

                $shifts .= "<div class=\"row-split\"><strong class='form-control fw-bold d-inline-block'>C$shift</strong></div>";

                $practiceRooms .= "<div class=\"row-split\">
                                        <select name='practice_room_id' class='form-control d-inline-block practice-room-select' id='" . $sessionId . '-' . $schedule->shift . "' data-shift='" . $schedule->shift . "'>
                                            <option value=''>Pick date and session first</option>";

                if (isset($practiceRoom)) {
                    /**@var PracticeRoom $practiceRoom */
                    $practiceRooms .= "<option value='$practiceRoom->id' selected>$practiceRoom->name - $practiceRoom->location</option>";
                }

                $practiceRooms .= "</select></div>";
            }

            $shifts .= '</div>';

            $sessionSelect = '
                <select class="form-control text-center session-select" id="session-' . $sessionId . '">
                    <option value=""></option>
            ';

            $availableSessions = [1 => 'S', 2 => 'C', 3 => 'T'];

            foreach ($availableSessions as $value => $label) {
                $selected = ($schedules[0]->session == $value) ? 'selected' : '';
                $sessionSelect .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
            }

            $sessionSelect .= '</select>';

            $actions = '
                <button type="button" class="btn btn-primary btn-sm schedule-single-save-btn">
                    <i class="lni lni-save align-middle"></i>
                </button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="lni lni-trash-can align-middle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <button class="btn btn-sm dropdown-item schedule-single-delete-confirm" data-session-id="' . $sessionId . '">Confirm</button>
                    </div>
                </div>
            ';

            $responseData[] = [
                'DT_RowData' => $schedules,
                'index' => $index,
                'practice_class_id' => $practice_class_id,
                'teacher' => $teacher != null ? $teacher->user->name : '<i>Not set</i>',
                'schedule_date' => $schedule_date,
                'session' => $sessionSelect,
                'shifts' => $shifts,
                'practice_room' => $practiceRooms,
                'actions' => $actions
            ];
        }

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePracticeClassStatus(Request $request)
    {
        $status = $request->input('status');
        $classId = $request->input('pclassId');

        try {
            /**@var PracticeClass $editedPclass */
            $editedPclass = $this->practiceClassService->update($classId, ['status' => $status]);

            // Define message based on status
            $message = $status == 0
                ? '<b>Disabled</b> for registration!'
                : '<b>Enabled</b> for registration!';

            $newStatus = $status == 0
                ? [
                    'title' => 'Not available for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $editedPclass->id . '" type="checkbox" id="' . $editedPclass->id . '-status">
                                  <label for="' . $editedPclass->id . '-status" title="Not available for registration">Created</label>
                                </div>'
                ]
                : [
                    'title' => 'Ready for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $editedPclass->id . '" type="checkbox" id="' . $editedPclass->id . '-status" checked>
                                  <label for="' . $editedPclass->id . '-status" title="Ready for registration">Ready</label>
                                </div>'
                ];

            // Return a unified successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => $message,
                'newStatus' => $newStatus,
                'newStatusRaw' => $editedPclass->status,
            ]);

        } catch (Exception $e) {
            Log::error("Practice Class updated failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getJsonDataForStudentsOfPracticeClass()
    {
        // TODO
    }
}
