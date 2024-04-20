<?php

namespace App\Http\Controllers\PracticeClass;

use App\Http\Resources\Module\ModuleResource;
use App\Http\Resources\PracticeClass\PracticeClassResource;
use App\Models\PracticeClass\PracticeClass;
use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
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

    protected ModuleServiceInterface $moduleService;

    /**
     * @param PracticeClassServiceInterface $practiceClassService
     */
    public function __construct(
        PracticeClassServiceInterface $practiceClassService,
        ModuleServiceInterface        $moduleService
    ) {
        $this->practiceClassService = $practiceClassService;
        $this->moduleService = $moduleService;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $modules = ModuleResource::collection($this->moduleService->getAll());

        $practiceClasses = PracticeClassResource::collection($this->practiceClassService->getAll());

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
     * @return
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //        $validatedData = $request->validate([
        //            'module' => 'required',
        //            'className' => 'required',
        //            'practiceRoom' => 'required',
        //            'startDate' => 'required',
        //            'session' => 'required',
        //            'recurring' => 'required',
        //            'teacher' => 'required',
        //        ]);

        $newPracticeClass = $this->practiceClassService->create($data);

        if ($newPracticeClass) {
            return redirect()->route('practice-classes.index');
        }

        // return PracticeClassResource::make($this->practiceClassService->create($data), ResponseStatus::HTTP_CREATED);
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
}
