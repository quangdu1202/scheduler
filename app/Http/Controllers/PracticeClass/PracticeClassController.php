<?php

namespace App\Http\Controllers\PracticeClass;

use App\Helper\Helper;
use App\Http\Resources\PracticeClass\PracticeClassResource;
use App\Models\PracticeClass\PracticeClass;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

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
     * @var Helper
     */
    protected Helper $helper;

    /**
     * @param PracticeClassServiceInterface $practiceClassService
     * @param ModuleServiceInterface $moduleService
     * @param Helper $helper
     */
    public function __construct(
        PracticeClassServiceInterface $practiceClassService,
        ModuleServiceInterface        $moduleService,
        Helper                        $helper
    )
    {
        $this->practiceClassService = $practiceClassService;
        $this->moduleService = $moduleService;
        $this->helper = $helper;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $modules = $this->moduleService->getAll();
        $practiceClasses = $this->practiceClassService->getAll();

        return view('practice_class.index', [
            'modules' => $modules,
            'practiceClasses' => $practiceClasses
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
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'practice_class_name' => 'required|string|max:255',
            'schedule_date' => 'required|date',
            'session' => 'required|int',
            'module_id' => 'required|int',
            'practice_room_id' => 'required|int',
            'teacher_id' => 'nullable|int',
            'recurring_interval' => 'required|int',
            'repeat_limit' => 'nullable|int|min:1|max:20',
        ]);

        if ($validator->fails()) {
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
                        return response()->json([
                            'status' => 422,
                            'title' => 'Duplicate Schedule',
                            'message' => 'Duplicate schedule on ' . $data['schedule_date']
                        ]);
                    }

                    // Create a single practice class
                    $newPracticeClass = $this->practiceClassService->create($data);

                    return response()->json([
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'Practice Class created successfully!',
                        'reloadTarget' => '#pclass-management-table',
                        'resetTarget' => '#new-pclass-form'
                    ]);

                // Cases for recurring_interval = 604800 (Weekly) or 1209600 (Biweekly)
                case 604800:
                case 1209600:
                    $limitCount = $data['repeat_limit'];
                    $recurringInterval = $data['recurring_interval'];

                    // Track created practice classes
                    $createdPracticeClasses = [];

                    // Create multiple practice classes based on repeat_limit
                    for ($i = 0; $i < $limitCount; $i++) {
                        $data['recurring_order'] = $i + 1;

                        // Duplicate schedule
                        if (!$this->isValidToSave($data)) {
                            // Remove the already created practice classes
                            foreach ($createdPracticeClasses as $practiceClass) {
                                $this->practiceClassService->delete($practiceClass->id);
                            }

                            return response()->json([
                                'status' => 422,
                                'title' => 'Error',
                                'message' => 'Duplicate schedule on ' . $data['schedule_date']
                            ]);
                        }

                        $newPracticeClass = $this->practiceClassService->create($data);
                        // Track the created practice class
                        $createdPracticeClasses[] = $newPracticeClass;
                        // Set Schedule date for next schedule
                        $data['schedule_date'] = date('Y-m-d', strtotime($data['schedule_date'] . "+$recurringInterval seconds"));
                    }

                    return response()->json([
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'Practice Class created successfully!',
                        'reloadTarget' => '#pclass-management-table',
                        'resetTarget' => '#new-pclass-form'
                    ]);

                default:
                    return response()->json([
                        'status' => 422,
                        'title' => 'Error',
                        'message' => 'Invalid recurring interval data!' // Sends back the first validation error
                    ]);
            }
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            // Return a generic error message to the client
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e
            ]);
        }
    }

    public function create()
    {
        return view('practice_class.create');
    }

    /**
     * @param PracticeClass $practiceClass
     * @param Request $request
     *
     * @return PracticeClassResource
     */
    public function update(PracticeClass $practiceClass, Request $request): PracticeClassResource
    {
        $request->validate([
            ''
        ]);

        return PracticeClassResource::make($this->practiceClassService->update($practiceClass, $request->all()));
    }

    /**
     * @param PracticeClass $practiceClass
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(PracticeClass $practiceClass): JsonResponse
    {
        $this->practiceClassService->delete($practiceClass);

        return Response::json(null, ResponseStatus::HTTP_NO_CONTENT);
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData()
    {
        $practiceClasses = $this->practiceClassService->getAll(['recurring_order' => 1]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            $session = match ($pclass->session) {
                1 => '<span class="badge rounded-pill text-bg-success">S</span>',
                2 => '<span class="badge rounded-pill text-bg-primary">C</span>',
                3 => '<span class="badge rounded-pill text-bg-danger">T</span>',
                default => '<span class="badge rounded-pill text-bg-dark">Unknown</span>',
            };

            $recurring_interval = match ($pclass->recurring_interval) {
                0 => '<span class="badge rounded-pill text-bg-secondary">Once</span>',
                604800 => '<span class="badge rounded-pill text-bg-primary">Weekly</span>',
                1209600 => '<span class="badge rounded-pill text-bg-success">Biweekly</span>',
                default => '<span class="badge rounded-pill text-bg-dark">Unknown</span>',
            };

            $actions = '<div class="dropdown">
                            <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="lni lni-angle-double-down align-middle"></i>
                            </button>
                            <div class="dropdown-menu">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button type="button" class="btn btn-success btn-sm schedule-info-btn" data-get-url="'.route('practice-classes.get-json-data-for-schedule', ['recurring_id' => $pclass->recurring_id]).'">
                                        <i class="fa-solid fa-magnifying-glass align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm schedule-edit-btn">
                                        <i class="lni lni-pencil-alt align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm schedule-delete-btn">
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
                'schedule_date' => $pclass->schedule_date,
                'session' => $session,
                'practice_room' => [
                    'room_id' => $pclass->practice_room_id,
                    'room_info' => '(' . $pclass->practiceRoom->location . ') ' . $pclass->practiceRoom->name
                ],
                'teacher' => $pclass->teacher_id,
                'recurring_id' => $pclass->recurring_id,
                'recurring_interval' => $recurring_interval,
                'recurring_order' => $pclass->recurring_order,
                'registered_qty' => $pclass->registered_qty,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }

    protected function isValidToSave($data)
    {
        $module_id = $data['module_id'];
        $schedule_date = $data['schedule_date'];
        $session = $data['session'];
        $practice_room_id = $data['practice_room_id'];

        $filteredByModule = $this->practiceClassService->getAll(['module_id' => $module_id]);

        $filterByDate = $filteredByModule->where('schedule_date', $schedule_date);

        $filterBySession = $filterByDate->where('session', $session);

        $filterByRoom = $filterBySession->where('practice_room_id', $practice_room_id);

        if ($filterByRoom->count() > 0) {
            return false;
        }

        return true;
    }

    public function getJsonDataForSchedule(Request $request)
    {
        $recurringId = $request->input('recurring_id');
        $practiceClasses = $this->practiceClassService->getAll(['recurring_id' => $recurringId]);

        $responseData = $practiceClasses->map(function ($pclass, $index) {
            $actions = '<button type="button" class="btn btn-primary btn-sm room-edit-btn">
                            <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm room-delete-btn">
                            <i class="lni lni-trash-can align-middle"></i>
                        </button>';
            return [
                'DT_RowId' => $pclass->id,
                'DT_RowData' => $pclass,
                'index' => $pclass->recurring_order,
                'schedule_date' => $pclass->schedule_date,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }
}
