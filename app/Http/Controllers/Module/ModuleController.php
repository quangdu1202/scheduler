<?php

namespace App\Http\Controllers\Module;

use App\Http\Resources\Module\ModuleResource;
use App\Models\Module\Module;
use App\Services\Module\Contracts\ModuleServiceInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
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
     * @return
     */
    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $newModule = $this->moduleService->create($data);

            toastr()->success('New module added successfully!');
            return redirect()->route('modules.index');
        } catch (Exception $e) {
            $oldData = $data;
            toastr()->error('Duplicate module code or unknown error occurred.');
            return back()->with(
                [
                    'oldData' => $oldData
                ]
            );
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
     * @return
     */
    public function update(Module $module, Request $request)
    {
        $data = $request->all();

        try {
            $editedModule = $this->moduleService->update($module, $data);

            return redirect()->route('modules.index');
        } catch (Exception $e) {
            $oldData = $data;

            return view(
                'module.create',
                [
                    'hasError' => true,
                    'oldData' => $oldData
                ]
            );
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
     * @return
     * @throws Exception
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
}
