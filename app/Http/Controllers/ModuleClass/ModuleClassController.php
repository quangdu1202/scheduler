<?php

namespace App\Http\Controllers\ModuleClass;

use App\Http\Resources\ModuleClass\ModuleClassResource;
use App\Models\ModuleClass\ModuleClass;
use App\Services\Module\ModuleService;
use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Services\Teacher\TeacherService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Class ModuleClassController
 */
class ModuleClassController extends Controller
{
    /**
     * @var ModuleClassServiceInterface
     */
    protected ModuleClassServiceInterface $moduleClassService;

    /**
     * @var ModuleService
     */
    protected ModuleService $moduleService;

    /**
     * @var TeacherService
     */
    protected TeacherService $teacherService;


    /**
     * @param ModuleClassServiceInterface $moduleClassService
     * @param TeacherService $teacherService
     * @param ModuleService $moduleService
     */
    public function __construct(
        ModuleClassServiceInterface $moduleClassService,
        TeacherService              $teacherService,
        ModuleService               $moduleService
    )
    {
        $this->moduleClassService = $moduleClassService;
        $this->teacherService = $teacherService;
        $this->moduleService = $moduleService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function index()
    {
        $moduleClasses = $this->moduleClassService->getAll();

        $modules = $this->moduleService->getAll();

        $teachers = $this->teacherService->getAll();

        return view('module_class.index', [
            'moduleClasses' => $moduleClasses,
            'modules' => $modules,
            'teachers' => $teachers
        ]);
    }

    /**
     * @param ModuleClass $moduleClass
     *
     * @return ModuleClassResource
     */
    public function show(ModuleClass $moduleClass): ModuleClassResource
    {
        return ModuleClassResource::make($moduleClass);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'module_id' => 'required|exists:modules,id',
            'module_class_code' => 'required|unique:module_classes,module_class_code|string|max:255',
            'module_class_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'teacher_id' => 'nullable|exists:teachers,id',
            'student_qty' => 'required|integer|min:0',
            'status' => ['required', Rule::in([0, 1])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $newModuleClass = $this->moduleClassService->create($data);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module Class created successfully!',
                'reloadTarget' => '#mclass-management-table',
                'resetTarget' => '#new-mclass-form'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Module Class creation failed: {$e->getMessage()}");

            // Return a generic error message to the client
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!'
            ]);
        }
    }

    /**
     * @param ModuleClass $moduleClass
     * @param Request $request
     * @return JsonResponse
     */
    public function update(ModuleClass $moduleClass, Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'module_id' => 'required|exists:modules,id',
            'module_class_code' => 'required|unique:module_classes,module_class_code|string|max:255',
            'module_class_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'teacher_id' => 'nullable|exists:teachers,id',
            'student_qty' => 'required|integer|min:0',
            'status' => ['required', Rule::in([0, 1])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $editedModuleClass = $this->moduleClassService->update($moduleClass, $data);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module Class updated successfully!',
                'reloadTarget' => '#mclass-management-table',
                'hideTarget' => '#edit-mclass-modal'
            ]);
        } catch (Exception $e) {
            Log::error("Update failed: {$e->getMessage()}");
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    /**
     * @param ModuleClass $moduleClass
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(ModuleClass $moduleClass): JsonResponse
    {
//        if ($this->moduleClassService->count(['studentModuleClasses']) > 0) {
//            return response()->json([
//                'status' => 500,
//                'title' => 'Cannot delete!',
//                'message' => 'There is at least 1 active class for this module',
//            ]);
//        }

        try {
            $this->moduleClassService->delete($moduleClass);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module class deleted successfully!',
                'reloadTarget' => '#mclass-management-table',
                'hideTarget' => '#delete-mclass-modal'
            ]);
        } catch (Exception $e) {
            Log::error("Failed to delete module class: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    public function getJsonData()
    {
        $moduleClasses = $this->moduleClassService->getAll();

        $responseData = $moduleClasses->map(function ($mclass, $index) {
            $startDate = $mclass->start_date != null ? $mclass->start_date : '<i>Not set</i>';
            $endDate = $mclass->end_date != null ? $mclass->end_date : '<i>Not set</i>';

            $studentQty = $mclass->student_qty;

            $status = match ($mclass->status) {
                0 => [
                    'title' => 'Disabled',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-mclass-id="' . $mclass->id . '" type="checkbox" id="' . $mclass->id . '-status">
                                  <label for="' . $mclass->id . '-status" title="Disabled">Disabled</label>
                                </div>'
                ],
                1 => [
                    'title' => 'Enabled',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-mclass-id="' . $mclass->id . '" type="checkbox" id="' . $mclass->id . '-status" checked>
                                  <label for="' . $mclass->id . '-status" title="Enabled">Enabled</label>
                                </div>'
                ],
                2 => [
                    'title' => 'Archived',
                    'value' => '<span class="badge rounded-pill text-bg-dark" data-status="2">Archived</span>'
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
                                    <button type="button" class="btn btn-success btn-sm mclass-student-info-btn" data-get-url="' . route('module-classes.get-student-data-for-mclass', ['module_class' => $mclass->id]) . '">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm mclass-edit-btn" data-post-url="' . route('module-classes.update', ['module_class' => $mclass->id]) . '">
                                        <i class="lni lni-pencil-alt align-middle"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm mclass-delete-btn" data-post-url="' . route('module-classes.destroy', ['module_class' => $mclass->id]) . '">
                                        <i class="lni lni-trash-can align-middle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>';

            return [
                'DT_RowId' => $mclass->id,
                'DT_RowData' => $mclass,
                'index' => $index + 1,
                'module_class_code' => $mclass->module_class_code,
                'module_class_name' => $mclass->module_class_name,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'teacher' => $mclass->teacher != null ? $mclass->teacher->user->name : '<i>Not set</i>',
                'student_qty' => $studentQty,
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
    public function getJsonDataForStudentsOfModuleClass(Request $request)
    {
        $responseData = '';
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateModuleClassStatus(Request $request)
    {
        $status = $request->input('status');
        $classId = $request->input('mclassId');

        try {
            $editedMclass = $this->moduleClassService->update($classId, ['status' => $status]);

            // Define message based on status
            $message = $status == 0
                ? 'Module Class <b>disabled</b>!'
                : 'Module Class <b>enabled</b>!';

            $newStatus = $status == 0
                ? [
                    'title' => 'Disabled',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-mclass-id="' . $editedMclass->id . '" type="checkbox" id="'.$editedMclass->id.'-status">
                                  <label for="'.$editedMclass->id.'-status" title="Disabled">Disabled</label>
                                </div>'
                ]
                : [
                    'title' => 'Enabled',
                    'value' => '<div class="form-check form-switch">
                                  <input class="form-check-input status-change-btn" data-mclass-id="' . $editedMclass->id . '" type="checkbox" id="'.$editedMclass->id.'-status" checked>
                                  <label for="'.$editedMclass->id.'-status" title="Enabled">Enabled</label>
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
            Log::error("Module Class updated failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }
}