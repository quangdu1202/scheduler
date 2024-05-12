<?php

namespace App\Http\Controllers\Schedule;

use App\Helper\Helper;
use App\Models\PracticeClass\PracticeClass;
use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use App\Services\PracticeRoom\PracticeRoomService;
use App\Services\Schedule\Contracts\ScheduleServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ScheduleController
 */
class ScheduleController extends Controller
{
    /**
     * @var ScheduleServiceInterface
     */
    protected ScheduleServiceInterface $scheduleService;

    /**
     * @var PracticeClassServiceInterface
     */
    protected PracticeClassServiceInterface $practiceClassService;

    /**
     * @var PracticeRoomService
     */
    protected PracticeRoomService $practiceRoomService;

    /**
     * @var Helper
     */
    protected Helper $helper;

    /**
     * @param ScheduleServiceInterface $scheduleService
     * @param PracticeClassServiceInterface $practiceClassService
     * @param PracticeRoomService $practiceRoomService
     * @param Helper $helper
     */
    public function __construct(
        ScheduleServiceInterface      $scheduleService,
        PracticeClassServiceInterface $practiceClassService,
        PracticeRoomService           $practiceRoomService,
        Helper                        $helper
    )
    {
        $this->scheduleService = $scheduleService;
        $this->practiceClassService = $practiceClassService;
        $this->practiceRoomService = $practiceRoomService;
        $this->helper = $helper;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $practiceClassId = $request->input('practice_class_id');
        $add_mode = $request->input('add_mode');

        /** @var PracticeClass $practiceClass */
        $practiceClass = $this->practiceClassService->findOrFail($practiceClassId);

        $shift_qty = $practiceClass->shift_qty;

        DB::beginTransaction();

        try {
            if ($add_mode == 'multi') {
                $multi_schedule_qty = $request->input('multi_schedule_qty');

                $multi_schedule_session = $request->input('multi_schedule_session');
                $multi_schedule_start_date = $request->input('multi_schedule_start_date');

                $multiData = [
                    'schedule_date' => $multi_schedule_start_date,
                    'session' => $multi_schedule_session,
                ];

                for ($i = 0; $i < $multi_schedule_qty; $i++) {
                    $sessionId = $this->helper->uniqidReal();
                    $this->createSchedules($practiceClassId, $sessionId, $shift_qty, $multiData);
                    $multiData['schedule_date'] = date('Y-m-d', strtotime('+1 week', strtotime($multiData['schedule_date'])));
                }
            } else {
                $sessionId = $this->helper->uniqidReal();
                $this->createSchedules($practiceClassId, $sessionId, $shift_qty);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'success' => true,
                'title' => 'Success!',
                'message' => 'Schedule(s) created successfully!',
                'reloadTarget' => '#pclass-management-table, #pclass-all-schedule-table',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create schedules for a given practice class and session ID.
     *
     * @param int $practiceClassId
     * @param string $sessionId
     * @param int $shiftQty
     * @param array|null $multiData
     * @return void
     */
    private function createSchedules(int $practiceClassId, string $sessionId, int $shiftQty, array $multiData = null)
    {
        for ($i = 0; $i < $shiftQty; $i++) {
            if ($multiData) {
                $this->scheduleService->create([
                    'practice_class_id' => $practiceClassId,
                    'schedule_date' => $multiData['schedule_date'],
                    'session' => $multiData['session'],
                    'session_id' => $sessionId,
                    'shift' => $i + 1,
                ]);
            } else {
                $this->scheduleService->create([
                    'practice_class_id' => $practiceClassId,
                    'session_id' => $sessionId,
                    'shift' => $i + 1,
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSingleSchedule(Request $request)
    {
        $requestedSchedules = $request->all();

        foreach ($requestedSchedules as $key => $data) {
            $schedule = $this->scheduleService->findOrFail($key);

            try {
                $this->scheduleService->update($schedule, $data);
            } catch (Exception $e) {
                // Log the exception for internal review
                Log::error("Schedule update failed: {$e->getMessage()}");

                return response()->json([
                    'status' => 500,
                    'title' => 'Error!',
                    'message' => 'Unknown error occurred, try again later!',
                ]);
            }
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'title' => 'Success!',
            'message' => 'Schedule updated!',
            'reloadTarget' => '',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse | void
     * @throws Exception
     */
    public function deleteSingleSchedule(Request $request)
    {
        $session_id = $request->input('session_id');

        if (!$session_id) {
            return;
        }

        $schedules = $this->scheduleService->find(['session_id' => $session_id]);

        DB::beginTransaction();

        try {
            foreach ($schedules as $schedule) {
                $this->scheduleService->delete($schedule);
            }
            DB::commit();

            return response()->json([
                'status' => 200,
                'success' => true,
                'title' => 'Success!',
                'message' => 'Schedule(s) deleted successfully!',
                'reloadTarget' => '#pclass-all-schedule-table',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Practice Class creation failed: {$e->getMessage()}");

            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse | void
     */
    public function getAvailableRooms(Request $request)
    {
        $schedule_date = $request->input('schedule_date');
        $session = $request->input('session');

        if (!$schedule_date || !$session) {
            return;
        }

        $currentProomIds = $request->input('current_practice_class_room_ids');

        $session_id = $request->input('session_id');

        // Filter out the rooms for shift 1
        $schedulesToBeFiltered = $this->scheduleService
            ->with(['practiceRoom'])
            ->getAll()
            ->sortBy('shift')
            ->whereNotIn('session_id', $session_id);

        $filteredSchedules_1 = $schedulesToBeFiltered
            ->where('schedule_date', '==', $schedule_date)
            ->where('session', '==', $session)
            ->where('shift', '==', 1);

        $availableRooms_1 = $this->practiceRoomService->with(['schedules'])->getAll();
        $filteredRoomIds_1 = $filteredSchedules_1->pluck('practice_room_id')->toArray();

        $availableRooms_1 = $availableRooms_1->reject(function ($room) use ($filteredRoomIds_1) {
            return in_array($room->id, $filteredRoomIds_1);
        });

        // Filter out the rooms for shift 2
        $filteredSchedules_2 = $schedulesToBeFiltered
            ->where('schedule_date', '==', $schedule_date)
            ->where('session', '==', $session)
            ->where('shift', '==', 2);

        $availableRooms_2 = $this->practiceRoomService->with(['schedules'])->getAll();
        $filteredRoomIds_2 = $filteredSchedules_2->pluck('practice_room_id')->toArray();

        $availableRooms_2 = $availableRooms_2->reject(function ($room) use ($filteredRoomIds_2) {
            return in_array($room->id, $filteredRoomIds_2);
        });

        // Build the options for the practice room select dropdown
        $practiceRoomOptions_1 = '<option value="">->Select a practice room</option>';
        foreach ($availableRooms_1 as $practiceRoom_1) {
            $selected = isset($currentProomIds[0]) && $practiceRoom_1->id == $currentProomIds[0] ? 'selected' : '';
            $practiceRoomOptions_1 .= '<option value="' . $practiceRoom_1->id . '"' . $selected . '>' . $practiceRoom_1->name . ' - ' . $practiceRoom_1->location . '</option>';
        }

        // Build the options for the practice room select dropdown
        $practiceRoomOptions_2 = '<option value="">->Select a practice room</option>';
        foreach ($availableRooms_2 as $practiceRoom_2) {
            $selected = isset($currentProomIds[1]) && $practiceRoom_2->id == $currentProomIds[1] ? 'selected' : '';
            $practiceRoomOptions_2 .= '<option value="' . $practiceRoom_2->id . '"' . $selected . '>' . $practiceRoom_2->name . ' - ' . $practiceRoom_2->location . '</option>';
        }


        return response()->json([
            'status' => 200,
            'success' => true,
            'practice_room_options_1' => $practiceRoomOptions_1,
            'practice_room_options_2' => $practiceRoomOptions_2,
        ]);
    }
}