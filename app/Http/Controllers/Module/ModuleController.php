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
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

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
     *
     * @return ModuleResource
     */
    public function store(Request $request): ModuleResource
    {
        return ModuleResource::make($this->moduleService->create($request->all(), ResponseStatus::HTTP_CREATED));
    }

    /**
     * @param Module $module
     * @param Request $request
     *
     * @return ModuleResource
     */
    public function update(Module $module, Request $request): ModuleResource
    {
        return ModuleResource::make($this->moduleService->update($module, $request->all()));
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
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Module $module): JsonResponse
    {
        $this->moduleService->delete($module);

        return Response::json(null, ResponseStatus::HTTP_NO_CONTENT);
    }
}
