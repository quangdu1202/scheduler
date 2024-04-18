<?php

namespace App\Http\Controllers\Module;

use App\Http\Resources\Module\ModuleResource;
use App\Models\Module\Module;
use App\Services\Module\Contracts\ModuleServiceInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class ModuleController
 */
class ModuleController extends Controller
{
    /**
     * @var ModuleServiceInterface
     */
    protected ModuleServiceInterface $moduleService;

    /**
     * @param ModuleServiceInterface $service
     */
    public function __construct(ModuleServiceInterface $service)
    {
        $this->moduleService = $service;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function index(): \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
    {
        $modules = $this->moduleService->getAll();

        return view('module.index', [
            'modules' => $modules,
        ]);
    }

    /**
     * @param Module $module
     *
     * @return ModuleResource
     */
    public function show(Module $module): ModuleResource
    {
        return ModuleResource::make($module);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_code' => 'required|unique:modules,module_code', // Assuming 'modules' is your table and 'module_code' is a field
            'module_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $newModule = $this->moduleService->create($request->all());
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module created successfully!',
                'reloadTarget' => '#module-management-table',
                'resetTarget' => '#new-module-form'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Module creation failed: {$e->getMessage()}");

            // Return a generic error message to the client
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'An error occurred while creating the module. Please try again.'
            ]);
        }
    }

    /**
     * @param Module $module
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Module $module, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_code' => 'required|unique:modules,module_code,' . $module->id,
            'module_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $editedModule = $this->moduleService->update($module, $request->all());
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module updated successfully!',
                'reloadTarget' => '#module-management-table',
                'hideTarget' => '#edit-module-modal'
            ]);
        } catch (Exception $e) {
            Log::error("Update failed: {$e->getMessage()}");
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'An internal error occurred. Please try again.',
            ]);
        }
    }


    /**
     * @param Module $module
     *
     * @return JsonResponse
     */
    public function destroy(Module $module)
    {
        try {
            $this->moduleService->delete($module);
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module deleted successfully!',
                'reloadTarget' => '#module-management-table',
                'hideTarget' => '#delete-module-modal'
            ]);
        } catch (Exception $e) {
            Log::error("Failed to delete module: {$e->getMessage()}");
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function showPracticeClasses(int $id)
    {
        $module = $this->moduleService->findOrFail($id);

        $practiceClasses = $module->practiceClasses;

        return view(
            'module.practice-classes',
            [
                'module' => $module,
                'practiceClasses' => $practiceClasses
            ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData()
    {
//        <a href="' . route('modules.show-practice-classes', $module) . '" class="btn btn-success btn-sm" title="Practice Classes List">
//                            <i class="lni lni-shift-right align-middle"></i>
//                        </a>
        $modules = $this->moduleService->getAll();
        $responseData = $modules->map(function ($module, $index) {
            $actions = '<div class="dropup d-inline-flex">
                            <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="lni lni-angle-double-up align-middle"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="'. route('modules.show-practice-classes', $module) .'" class="btn btn-sm dropdown-item">Module Classes</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a href="'. route('modules.show-practice-classes', $module) .'" class="btn btn-sm dropdown-item">Practice Classes</a></li>
                            </ul>
                        </div>
                        <button type="button" title="Edit module" class="btn btn-primary btn-sm module-edit-btn">
                            <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" title="Delete module" class="btn btn-danger btn-sm module-delete-btn">
                            <i class="lni lni-trash-can align-middle"></i>
                        </button>';
            return [
                'DT_RowId' => $module->id,
                'DT_RowData' => $module,
                'index' => $index + 1,
                'module_code' => $module->module_code,
                'module_name' => $module->module_name,
                'practice_class_qty' => count($module->practiceClasses),
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }
}
