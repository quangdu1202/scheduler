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
     * //     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $practiceClassId = $request->input('practice_class_id');
        $add_mode = $request->input('add_mode');

        /** @var PracticeClass $practiceClass */
        $practiceClass = $this->practiceClassService->findOrFail($practiceClassId);

        $signatureSchedule = $practiceClass->schedules->where('order', '=', 0)->first();

        if (!$signatureSchedule) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Caution!',
                'message' => 'Please set the signature data first!',
            ]);
        }
        $lastSchedule = $practiceClass->schedules->sortByDesc('schedule_date')->first();

        if (count($practiceClass->schedules) > 1) {
            $lastScheduleDate = $lastSchedule->schedule_date;
            $session = $signatureSchedule->session;
            $lastOrder = $lastSchedule->order;
        } else {
            $lastScheduleDate = date('Y-m-d', strtotime('-1 week', strtotime($signatureSchedule->schedule_date)));
            $session = $signatureSchedule->session;
            $lastOrder = 0;
        }

        $shift_qty = $practiceClass->shift_qty;

        DB::beginTransaction();

        try {
            if ($add_mode == 'multi') {
                $multi_schedule_qty = $request->input('multi_schedule_qty');

                // Collect all dates to check for duplicates
                $newDates = [];
                for ($i = 1; $i <= $multi_schedule_qty; $i++) {
                    $newDates[] = date('Y-m-d', strtotime("+$i week", strtotime($lastScheduleDate)));
                }

                // Check for existing dates in the database
                $existingDates = $practiceClass->schedules->where('order', '!=', 0)->pluck('schedule_date')->toArray();
                $duplicateDates = array_intersect($newDates, $existingDates);
                if (count($duplicateDates) > 0) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 500,
                        'success' => false,
                        'title' => 'Error!',
                        'message' => 'Duplicate schedule dates detected: ' . implode(', ', $duplicateDates),
                    ]);
                }

                foreach ($newDates as $date) {
                    $sessionId = $this->helper->uniqidReal();
                    $data = [
                        'schedule_date' => $date,
                        'session' => $session,
                        'order' => $lastOrder + 1
                    ];
                    $this->createSchedules($practiceClassId, $sessionId, $shift_qty, $data);
                }
            } else {
                $sessionId = $this->helper->uniqidReal();
                $data = [
                    'schedule_date' => date('Y-m-d', strtotime('+1 week', strtotime($lastScheduleDate))),
                    'session' => $session,
                    'order' => $lastOrder + 1
                ];
                $this->createSchedules($practiceClassId, $sessionId, $shift_qty, $data);
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
     * @param $data
     * @return void
     */
    private function createSchedules(int $practiceClassId, string $sessionId, int $shiftQty, $data)
    {
        for ($i = 0; $i < $shiftQty; $i++) {
            $this->scheduleService->create([
                'practice_class_id' => $practiceClassId,
                'schedule_date' => $data['schedule_date'],
                'session' => $data['session'],
                'session_id' => $sessionId,
                'order' => $data['order'],
                'shift' => $i + 1,
            ]);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSingleSchedule(Request $request)
    {
        $practiceClassId = $request->input('pclassId');
        $requestedSchedules = $request->input('newData');

        /** @var PracticeClass $practiceClass */
        $practiceClass = $this->practiceClassService->findOrFail($practiceClassId);
        $existingDates = $practiceClass->schedules->whereNotIn('id', [array_keys($requestedSchedules)[0], array_keys($requestedSchedules)[1]])->where('order', '!=', 0)->pluck('schedule_date')->toArray();

        // Extracting first existing schedule for comparison
        $firstSchedule = $practiceClass->schedules->first();
        $existingWeekDay = date('N', strtotime($firstSchedule->schedule_date));
        $existingSession = $firstSchedule->session;

        $hasSignatureConflict = false;

        // Iterate over all requested schedules to check conditions
        foreach ($requestedSchedules as $scheduleId => $data) {
            $newScheduleDate = $data['schedule_date'];
            $newSession = $data['session'];

            // Check if the new date matches any existing dates
            if (in_array($newScheduleDate, $existingDates)) {
                return response()->json([
                    'status' => 500,
                    'success' => false,
                    'title' => 'Error!',
                    'message' => 'Duplicate schedule date detected: ' . $newScheduleDate,
                ]);
            }

            // Check weekday and session match
            $newWeekDay = date('N', strtotime($newScheduleDate));
            if ($existingWeekDay != $newWeekDay || $existingSession != $newSession) {
                $hasSignatureConflict = true;
            }

            // Update schedule if all checks pass
            $schedule = $this->scheduleService->findOrFail($scheduleId);
            try {
                $this->scheduleService->update($schedule, $data);
            } catch (Exception $e) {
                Log::error("Schedule update failed: {$e->getMessage()}");
                return response()->json([
                    'status' => 500,
                    'title' => 'Error!',
                    'message' => 'Unknown error occurred, try again later!',
                ]);
            }
        }

        if ($hasSignatureConflict) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'isCaution' => true,
                'title' => 'Saved but Caution!',
                'message' => 'Conflict with signature schedule [<strong>' . date('l', strtotime($firstSchedule->schedule_date)) . ($existingSession == 1 ? '-Morning' : ($existingSession == 2 ? '-Afternoon' : '-Evening')) . '</strong>]'
            ]);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'title' => 'Success!',
            'message' => 'Schedule updated!',
            'reloadTarget' => '',  // Optionally specify target if needed
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function updateSignatureSchedule(Request $request)
    {
        $data = $request->input('data');
        $weekday = $data['weekday'];
        $schedule_date = $data['start_date'];
        $session = $data['session'];
        $pRoomId = $data['pRoomId'] ?? null;
        $studentQty1 = $data['studentQty1'] ?? 0;
        $studentQty2 = $data['studentQty2'] ?? 0;
        $studentQty = (int)$studentQty1 * 100 + (int)$studentQty2;
        $pClassId = $request->input('pclassId');
        $sessionId = $this->helper->uniqidReal();

        if (!$weekday || !$schedule_date || !$session) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'title' => 'Error!',
                'message' => 'Unknown error occurred!'
            ]);
        }

        DB::beginTransaction();

        try {
            $existing = $this->scheduleService->find([
                'practice_class_id' => $pClassId,
                'order' => 0
            ])->first();

            if ($existing) {
                $this->scheduleService->update($existing,
                    [
                        'schedule_date' => $schedule_date,
                        'session' => $session,
                        'practice_room_id' => $pRoomId,
                        'student_qty' => $studentQty,
                    ]
                );
            } else {
                $this->scheduleService->create([
                    'practice_class_id' => $pClassId,
                    'schedule_date' => $schedule_date,
                    'practice_room_id' => $pRoomId,
                    'session' => $session,
                    'session_id' => $sessionId,
                    'order' => 0
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'success' => true,
                'title' => 'Success!',
                'message' => 'Signature data set successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Signature data set failed: {$e->getMessage()}");

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