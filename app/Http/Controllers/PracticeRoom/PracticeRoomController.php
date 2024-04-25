<?php

namespace App\Http\Controllers\PracticeRoom;

use App\Http\Resources\PracticeRoom\PracticeRoomResource;
use App\Models\PracticeRoom\PracticeRoom;
use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'pc_qty' => 'required|int',
            'status' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first() // Sends back the first validation error
            ]);
        }

        try {
            $newRoom = $this->practiceRoomService->create($request->all());
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Room created successfully!',
                'reloadTarget' => '#room-management-table',
                'resetTarget' => '#new-room-form'
            ]);
        } catch (Exception $e) {
            // Log the exception for internal review
            Log::error("Practice Room creation failed: {$e->getMessage()}");

            // Return a generic error message to the client
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'An error occurred while creating the room. Please try again.'
            ]);
        }
    }

    /**
     * @param PracticeRoom $practiceRoom
     * @param Request $request
     * @return JsonResponse
     */
    public function update(PracticeRoom $practiceRoom, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'pc_qty' => 'required|int',
                'status' => 'required|int',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'title' => 'Validation Error',
                    'message' => $validator->errors()->first() // Sends back the first validation error
                ]);
            }

            $editedRoom = $this->practiceRoomService->update($practiceRoom, $request->all());
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Practice Room updated successfully!',
                'reloadTarget' => '#room-management-table',
                'hideTarget' => '#edit-room-modal'
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
     * @param PracticeRoom $practiceRoom
     *
     * @return JsonResponse
     */
    public function destroy(PracticeRoom $practiceRoom): JsonResponse
    {
        try {
            $this->practiceRoomService->delete($practiceRoom);
            return response()->json([
                'status' => 200,
                'title' => 'Success!',
                'message' => 'Room deleted successfully!',
                'reloadTarget' => '#room-management-table',
                'hideTarget' => '#delete-room-modal'
            ]);
        } catch (Exception $e) {
            Log::error("Failed to delete room: {$e->getMessage()}");
            return response()->json([
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Unknown error occurred, try again later!',
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getJsonData()
    {
        $practiceRooms = $this->practiceRoomService->getAll();
        $responseData = $practiceRooms->map(function ($room, $index) {
            $status = '<span class="badge rounded-pill text-bg-dark">Unknown</span>';
            switch ($room->status) {
                case 1:
                    $status = '<span class="badge rounded-pill text-bg-success">Available</span>';
                    break;
                case 2:
                    $status = '<span class="badge rounded-pill text-bg-warning">In use</span>';
                    break;
                case 3:
                    $status = '<span class="badge rounded-pill text-bg-secondary">Not Available</span>';
                    break;
            }

            $actions = '<button type="button" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-magnifying-glass align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm room-edit-btn">
                            <i class="lni lni-pencil-alt align-middle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm room-delete-btn">
                            <i class="lni lni-trash-can align-middle"></i>
                        </button>';
            return [
                'DT_RowId' => $room->id,
                'DT_RowData' => $room,
                'index' => $index + 1,
                'name' => $room->name,
                'location' => $room->location,
                'pc_qty' => $room->pc_qty,
                'status' => $status,
                'raw_status' => $room->status,
                'actions' => $actions
            ];
        });

        return response()->json($responseData);
    }
}
