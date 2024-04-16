<?php

namespace App\Http\Controllers\Module;

use App\Http\Resources\Module\ModuleResource;
use App\Models\Module\Module;
use App\Services\Module\Contracts\ModuleServiceInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        ])->with(
            [
                'success' => null,
                'message' => null
            ]
        );
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
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $newModule = $this->moduleService->create($data);
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'New module created successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'title' => 'Error!',
                'message' => 'Duplicate module code or unknown error occurred!',
            ]);
        }
    }

    public function create()
    {
        return view(
            'module.create',
            [
                'hasError' => false,
                'oldData' => null
            ]
        );
    }

    /**
     * @param Module $module
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Module $module, Request $request)
    {
        $data = $request->all();

        try {
            $editedModule = $this->moduleService->update($module, $data);

            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Module updated successfully!',
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 422,
                'title' => 'Error!',
                'message' => 'Duplicate module code or unknown error occurred!',
            ]);
        }

        // return ModuleResource::make($this->moduleService->update($module, $request->all()));
    }

    /**
     * @param Module $module
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|Application|Factory|View
     */
    public function edit(Module $module): \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
    {
        return view('module.edit', [
            'module' => $module,
        ]);
    }

    /**
     * @param Module $module
     *
     * @return RedirectResponse
     */
    public function destroy(Module $module)
    {
        try {
            $this->moduleService->delete($module);

            return redirect()->route('modules.index')->with(
                [
                    'success' => true,
                    'message' => "Module deleted successfully!"
                ]
            );
        } catch (Exception $e) {
            return redirect()->route('modules.index')->with(
                [
                    'success' => false,
                    'message' => "Failed to delete module, please try again later!"
                ]
            );
        }

        // return Response::json(null, ResponseStatus::HTTP_NO_CONTENT);
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
        $modules = $this->moduleService->getAll();
//        <a href="'. route('modules.edit', $module) .'" class="table-row-btn module-btn-edit btn btn-primary btn-sm" title="Edit Module Info">
//                            <i class="lni lni-pencil-alt align-middle"></i>
//                        </a>
        $responseData = $modules->map(function ($module, $index) {
            $actions = '<a href="'. route('modules.show-practice-classes', $module) .'" class="table-row-btn module-btn-info btn btn-success btn-sm" title="Module Info">
                            <i class="fa-solid fa-magnifying-glass align-middle"></i>
                        </a>
                        <button type="button" class="btn btn-primary btn-sm module-edit-btn">
                          <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm module-delete-btn">
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
