<?php

namespace App\Http\Controllers\PracticeClass;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\PracticeClass\PracticeClass;
use App\Http\Resources\PracticeClass\PracticeClassResource;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;

/**
 * Class PracticeClassController
 */
class PracticeClassController extends Controller
{
	/**
	 * @var PracticeClassServiceInterface
	 */
	protected PracticeClassServiceInterface $service;

	/**
	 * @param PracticeClassServiceInterface $service
	 */
	public function __construct(PracticeClassServiceInterface $service)
	{
		$this->service = $service;
	}

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
	public function index()
	{

		$practiceClasses = PracticeClassResource::collection($this->service->getAll());

        return view('practice_class.index', [
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
	 * @return PracticeClassResource
	 */
	public function store(Request $request): PracticeClassResource
	{
        $request->validate([
            ''
        ]);

		return PracticeClassResource::make($this->service->create($request->all()), ResponseStatus::HTTP_CREATED);
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

		return PracticeClassResource::make($this->service->update($practiceClass, $request->all()));
	}

	/**
	 * @param PracticeClass $practiceClass
	 *
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function destroy(PracticeClass $practiceClass): JsonResponse
	{
		$this->service->delete($practiceClass);

		return Response::json(null, ResponseStatus::HTTP_NO_CONTENT);
	}

    public function create()
    {
        return view('practice_class.create');
    }
}
