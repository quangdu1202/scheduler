<?php

namespace App\Http\Controllers\PracticeClass;

use App\Helper\Helper;
use App\Http\Resources\PracticeClass\PracticeClassResource;
use App\Models\PracticeClass\PracticeClass;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Registration\RegistrationService;
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
     * @param ModuleServiceInterface $moduleService
     * @param RegistrationService $scheduleRegistrationService
     * @param TeacherService $teacherService
     * @param PracticeRoomService $practiceRoomService
     * @param Helper $helper
     */
    public function __construct(
        PracticeClassServiceInterface $practiceClassService,
        ModuleServiceInterface        $moduleService,
        RegistrationService           $scheduleRegistrationService,
        TeacherService                $teacherService,
        PracticeRoomService           $practiceRoomService,
        Helper                        $helper,
    )
    {
        $this->practiceClassService = $practiceClassService;
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
        // Begin a database transaction
        DB::beginTransaction();

        $data = $request->all();

        $validator = Validator::make($data, [
            // Validation rules
        ]);

        if ($validator->fails()) {
            // Rollback the transaction and return a validation error response
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $data['recurring_id'] = $this->helper->uniqidReal();

            switch ($data['recurring_interval']) {
                // Case for recurring_interval = 0 (Once)
                case 0:
                    // Duplicate schedule
                    if (!$this->isValidToSave($data)) {
                        // Rollback the transaction and return a duplicate schedule error response
                        DB::rollBack();
                        return response()->json([
                            'status' => 422,
                            'title' => 'Duplicate Schedule',
                            'message' => 'Duplicate schedule on ' . $data['schedule_date']
                        ]);
                    }

                    // Create a single practice class
                    $newPracticeClass = $this->practiceClassService->create($data);

                    break;

                // Cases for recurring_interval = 604800 (Weekly) or 1209600 (Biweekly)
                case 604800:
                case 1209600:
                    $limitCount = $data['repeat_limit'];
                    $recurringInterval = $data['recurring_interval'];

                    // Create multiple practice classes based on repeat_limit
                    for ($i = 0; $i < $limitCount; $i++) {
                        $data['recurring_order'] = $i + 1;

                        // Duplicate schedule
                        if (!$this->isValidToSave($data)) {
                            // Rollback the transaction and delete the already created practice classes
                            DB::rollBack();

                            return response()->json([
                                'status' => 422,
                                'title' => 'Error',
                                'message' => 'Duplicate schedule on ' . $data['schedule_date']
                            ]);
                        }

                        $newPracticeClass = $this->practiceClassService->create($data);

                        // Set Schedule date for the next schedule
                        $data['schedule_date'] = date('Y-m-d', strtotime($data['schedule_date'] . "+$recurringInterval seconds"));
                    }

                    break;

                default:
                    // Rollback the transaction and return an error response for an invalid recurring interval
                    DB::rollBack();
                    return response()->json([
                        'status' => 422,
                        'title' => 'Error',
                        'message' => 'Invalid recurring interval data!'
                    ]);
            }

            // Commit the transaction
            DB::commit();

            // Return a successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Class created successfully!',
                'reloadTarget' => '#pclass-management-table',
                'resetTarget' => '#new-pclass-form'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            // Rollback the transaction and return a generic error message to the client
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e
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

        if (!$this->isValidToSave($data)) {
            return response()->json([
                'status' => 422,
                'title' => 'Duplicate Schedule',
                'message' => 'Duplicate schedule on ' . $data['schedule_date']
            ]);
        }

        try {
            $editedPclass = $this->practiceClassService->update($practiceClass, $data);
            // Return a successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Class updated successfully!',
                'reloadTarget' => '#pclass-management-table, #pclass-all-schedule-table',
                'hideTarget' => '#edit-single-pclass-modal'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!'
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

        // Guard clause to check delete mode
        $deleteMode = $request->input('_deleteMode');
        if (!in_array($deleteMode, ['all', 'single'])) {
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Invalid delete mode!',
            ]);
        }

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Delete practice classes based on delete mode
            if ($deleteMode === 'all') {
                $recurringId = $practiceClass->recurring_id;
                $this->practiceClassService->deleteByRecurringId($recurringId);
            } else {
                $this->practiceClassService->update($practiceClass->id, [
                    'schedule_date' => null,
                    'session' => null,
                    'practice_room_id' => null,
                    'teacher_id' => null
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Return a successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice class schedule(s) deleted successfully!',
                'resetTarget' => '#delete-pclass-form',
                'hideTarget' => '#delete-pclass-modal',
                'reloadTarget' => $deleteMode === 'all' ? '#pclass-management-table' : '#pclass-management-table, #pclass-all-schedule-table',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete practice classes: {$e->getMessage()}");
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    // Need update
    /**
     * @param $data
     * @return bool
     */
    protected function isValidToSave($data)
    {
        $class_id = $data['id'] ?? null;

        $module_id = $data['module_id'] ?? null;

        $schedule_date = $data['schedule_date'] ?? null;

        $session = $data['session'] ?? null;

        $practice_room_id = $data['practice_room_id'] ?? null;

        if (!$module_id || !$schedule_date || !$session || !$practice_room_id) {
            return false;
        }

        $practiceClasses =  $this->practiceClassService->getAll();

        // Filter $practiceClasses based on all conditions
        $filteredPracticeClasses = $practiceClasses->filter(function ($class) use ($class_id, $module_id, $schedule_date, $session, $practice_room_id) {
            return
                $class->module_id == $module_id &&
                $class->schedule_date == $schedule_date &&
                $class->session == $session &&
                $class->practice_room_id == $practice_room_id &&
                (!$class_id || $class->id != $class_id);
        });

        if ($filteredPracticeClasses->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData(): JsonResponse
    {
        $practiceClasses = $this->practiceClassService->getAll(['recurring_order' => 1]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            $endDate = $this->practiceClassService
                ->find(['recurring_id' => $pclass->recurring_id])
                ->sortByDesc('recurring_order')
                ->first()
                ->schedule_date;

            $session = match ($pclass->session) {
                1 => [
                    'title' => 'Morning',
                    'value' => '<span class="badge rounded-pill text-bg-success">S</span>'
                ],
                2 => [
                    'title' => 'Afternoon',
                    'value' => '<span class="badge rounded-pill text-bg-primary">C</span>'
                ],
                3 => [
                    'title' => 'Evening',
                    'value' => '<span class="badge rounded-pill text-bg-danger">T</span>'
                ],
                default => [
                    'title' => 'Unknown',
                    'value' => '<span class="badge rounded-pill text-bg-dark">Unknown</span>'
                ],
            };

            $recurring_interval = match ($pclass->recurring_interval) {
                0 => '<span class="badge rounded-pill text-bg-secondary">Once</span>',
                604800 => '<span class="badge rounded-pill text-bg-primary">Weekly</span>',
                1209600 => '<span class="badge rounded-pill text-bg-success">Biweekly</span>',
                default => '<span class="badge rounded-pill text-bg-dark">Unknown</span>',
            };

            $registered_qty = $pclass->registered_qty;
            $max_qty = $pclass->max_qty;

            $status = match ($pclass->status) {
                0 => [
                    'title' => 'Not available for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $pclass->id . '" type="checkbox" id="'.$pclass->id.'-status">
                                  <label for="'.$pclass->id.'-status" title="Not available for registration">Created</label>
                                </div>'
                ],
                1 => [
                    'title' => 'Ready for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $pclass->id . '" type="checkbox" id="'.$pclass->id.'-status" checked>
                                  <label for="'.$pclass->id.'-status" title="Ready for registration">Ready</label>
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
                                    <button type="button" class="btn btn-success btn-sm schedule-info-btn" data-get-url="' . route('practice-classes.get-json-data-for-schedule', ['recurring_id' => $pclass->recurring_id]) . '">
                                        <i class="fa-solid fa-magnifying-glass align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm pclass-student-info-btn" data-get-url="' . route('practice-classes.get-student-data-for-schedule', ['recurring_id' => $pclass->recurring_id]) . '">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm pclass-delete-btn" data-delete-mode="all">
                                        <i class="lni lni-trash-can align-middle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>';

            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $index + 1,
                'practice_class_name' => $pclass->practice_class_name,
                'start_date' => $pclass->schedule_date != null ? $pclass->schedule_date : '<i>Not set</i>',
                'end_date' => $endDate,
                'session' => $session,
                'practice_room' => [
                    'title' => $pclass->practiceRoom != null ? ($pclass->practiceRoom->location . ' - ' . $pclass->practiceRoom->name) : 'Not set',
                    'value' => $pclass->practiceRoom != null ? ('<b>' . $pclass->practiceRoom->location . '</b><br>' . $pclass->practiceRoom->name) : '<i>Not set</i>'
                ],
                'teacher' => $pclass->teacher != null ? $pclass->teacher->user->name : '<i>Not set</i>' ,
                'recurring_id' => $pclass->recurring_id,
                'recurring_interval' => $recurring_interval,
                'recurring_order' => $pclass->recurring_order,
                'registered_qty' => $registered_qty . '/' . $max_qty,
                'status' => $status,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getJsonDataForSchedule(Request $request): JsonResponse
    {
        $recurringId = $request->input('recurring_id');
        $practiceClasses = $this->practiceClassService->getAll(['recurring_id' => $recurringId]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            $session = match ($pclass->session) {
                1 => [
                    'title' => 'Morning',
                    'value' => '<span class="badge rounded-pill text-bg-success">S</span>'
                ],
                2 => [
                    'title' => 'Afternoon',
                    'value' => '<span class="badge rounded-pill text-bg-primary">C</span>'
                ],
                3 => [
                    'title' => 'Evening',
                    'value' => '<span class="badge rounded-pill text-bg-danger">T</span>'
                ],
                default => [
                    'title' => 'Unknown',
                    'value' => '<span class="badge rounded-pill text-bg-dark">Unknown</span>'
                ],
            };

            $actions = '<button type="button" class="btn btn-primary btn-sm pclass-single-edit-btn">
                            <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm pclass-delete-btn" data-delete-mode="single">
                            <i class="lni lni-trash-can align-middle"></i>
                        </button>';
            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $pclass->recurring_order,
                'schedule_date' => $pclass->schedule_date != null ? $pclass->schedule_date : '<i>Not set</i>',
                'practice_room' => [
                    'title' => $pclass->practiceRoom != null ? ($pclass->practiceRoom->location . ' - ' . $pclass->practiceRoom->name) : 'Not set',
                    'value' => $pclass->practiceRoom != null ? ('<b>' . $pclass->practiceRoom->location . '</b><br>' . $pclass->practiceRoom->name) : '<i>Not set</i>'
                ],
                'teacher' => $pclass->teacher != null ? $pclass->teacher->user->name : '<i>Not set</i>' ,
                'session' => [
                    'title' => $session['title'],
                    'value' => $session['value']
                ],
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateScheduleStatus(Request $request)
    {
        $status = $request->input('status');
        $classId = $request->input('pclassId');

        try {
            $editedPclass = $this->practiceClassService->update($classId, ['status' => $status]);

            // Define message based on status
            $message = $status == 0
                ? '<b>Disabled</b> for registration!'
                : '<b>Enabled</b> for registration!';

            $newStatus = $status == 0
                ? [
                    'title' => 'Not available for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $editedPclass->id . '" type="checkbox" id="'.$editedPclass->id.'-status">
                                  <label for="'.$editedPclass->id.'-status" title="Not available for registration">Created</label>
                                </div>'
                ]
                : [
                    'title' => 'Ready for registration',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-pclass-id="' . $editedPclass->id . '" type="checkbox" id="'.$editedPclass->id.'-status" checked>
                                  <label for="'.$editedPclass->id.'-status" title="Ready for registration">Ready</label>
                                </div>'
                ];

            // Return a unified successful JSON response
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => $message,
                'newStatus' => $newStatus
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
