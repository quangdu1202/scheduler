<?php

namespace App\Http\Controllers\PracticeRoom;

use App\Http\Resources\PracticeRoom\PracticeRoomResource;
use App\Models\PracticeRoom\PracticeRoom;
use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class PracticeRoomController
 */
class PracticeRoomController extends Controller
{
    /**
     * @var PracticeRoomServiceInterface
     */
    protected PracticeRoomServiceInterface $practiceRoomService;

    /**
     * @param PracticeRoomServiceInterface $practiceRoomService
     */
    public function __construct(PracticeRoomServiceInterface $practiceRoomService)
    {
        $this->practiceRoomService = $practiceRoomService;
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $practiceRooms = $this->practiceRoomService->getAll();

        return view('practice_room.index', [
            'practiceRooms' => $practiceRooms,
        ]);
    }

    /**
     * @param PracticeRoom $practiceRoom
     *
     * @return PracticeRoomResource
     */
    public function show(PracticeRoom $practiceRoom): PracticeRoomResource
    {
        return PracticeRoomResource::make($practiceRoom);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
    }

    /**
     * @param PracticeRoom $practiceRoom
     * @param
     *
     */
    public function update(PracticeRoom $practiceRoom)
    {
    }

    /**
     * @param PracticeRoom $practiceRoom
     *
     * @return JsonResponse
     */
    public function destroy(PracticeRoom $practiceRoom): JsonResponse
    {

    }

    /**
     * @return JsonResponse
     */
    public function getJsonData()
    {
        $practiceRooms = $this->practiceRoomService->getAll();
        $responseData = $practiceRooms->map(function ($room, $index) {
            $actions = '<button type="button" class="btn btn-primary btn-sm module-edit-btn">
                            <i class="fa-solid fa-magnifying-glass align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm module-edit-btn">
                            <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm module-delete-btn">
                            <i class="lni lni-trash-can align-middle"></i>
                        </button>';
            return [
                'DT_RowId' => $room->id,
                'DT_RowData' => $room,
                'index' => $index + 1,
                'name' => $room->name,
                'location' => $room->location,
                'pc_qty' => $room->pc_qty,
                'status' =>$room->status,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }
}
