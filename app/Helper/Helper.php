<?php

namespace App\Helper;

use App\Models\PracticeClass\PracticeClass;
use App\Models\Schedule\Schedule;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Services\PracticeClass\PracticeClassService;
use App\Services\Registration\RegistrationService;
use App\Services\Student\StudentService;
use App\Services\Teacher\TeacherService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Helper
{
    /**
     * @var StudentService
     */
    protected StudentService $studentService;

    /**
     * @var PracticeClassService
     */
    protected PracticeClassService $practiceClassService;

    /**
     * @var RegistrationService
     */
    protected RegistrationService $registrationService;

    /**
     * @var TeacherService
     */
    protected TeacherService $teacherService;

    public function __construct(
        StudentService       $studentService,
        PracticeClassService $practiceClassService,
        RegistrationService  $registrationService,
        TeacherService       $teacherService,
    )
    {
        $this->studentService = $studentService;
        $this->practiceClassService = $practiceClassService;
        $this->registrationService = $registrationService;
        $this->teacherService = $teacherService;
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function uniqidReal(int $length = 13): string
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }

    /**
     * @param $studentId
     * @return Collection
     */
    public function getModulesByStudentId($studentId): Collection
    {
        /**@var Student $student */
        $student = $this->studentService->findOrFail($studentId);

        return $student->moduleClasses->map(function ($moduleClass) {
            return $moduleClass->module;
        });
    }

    /**
     * @param $dateString
     * @return string
     */
    public function dateToFullCharsWeekday($dateString): string
    {
        // Create a Carbon instance from the date string
        $date = Carbon::parse($dateString);

        // Format the date to get the 3-letter weekday
        // Output will be 'SUN' for the example date
        return $date->format('l');
    }

    /**
     * @param $dateString
     * @return string
     */
    public function dateStringToWeekdayInt($dateString): string
    {
        $date = Carbon::parse($dateString);

        return $date->format('N');
    }

    /**
     * @param PracticeClass $practiceClass
     * @return array
     */
    public function getMaxStudentOfShifts(PracticeClass $practiceClass): array
    {
        $signatureSchedule = $practiceClass->getSignatureSchedule();
        $studentQty = $signatureSchedule->student_qty ?? 0;

        return [
            'studentQty1' => intval($studentQty / 100) ?? 0,
            'studentQty2' => $studentQty % 100 ?? 0,
        ];
    }

    /**
     * @param $teacherId
     * @return array
     */
    public function getNextSchedulesOfTeacher($teacherId): array
    {
        /**@var Teacher $teacher */
        $teacher = $this->teacherService->findOrFail($teacherId);

        $practiceClasses = $teacher->practiceClasses;

        $responseData = [];

        foreach ($practiceClasses as $pClass) {
            /**@var Schedule[] $schedules */
            $schedules = $pClass->schedules
                ->where('order', '!=', 0)
                ->where('shift', '=', 1)
                ->map(function ($schedule) {
                    $pRoom = $schedule->practiceRoom ?? null;
                    return [
                        'schedule_date' => $schedule->schedule_date,
                        'session' => $schedule->session,
                        'room_location' => $schedule->practiceRoom->location ?? 'No room info',
                        'room_name' => $schedule->practiceRoom->name ?? 'No room info',
                    ];
                });

            foreach ($schedules as $schedule) {
                $time = match ($schedule['session']) {
                    1 => '07:00:00',
                    2 => '12:30:00',
                    3 => '17:55:00',
                };

                $responseData[] = [
                    'className' => $pClass->practice_class_name,
                    'classTime' => $schedule['schedule_date'] . ' ' . $time,
                    'roomLocation' => $schedule['room_location'],
                    'roomName' => $schedule['room_name'],
                ];
            }
        }

        return $responseData;
    }

    /**
     * @param $studentId
     * @return array
     */
    public function getNextSchedulesOfStudent($studentId): array
    {
        /**@var Student $student */
        $student = $this->studentService->findOrFail($studentId);

        $practiceClasses = $student->practiceClasses;

        $responseData = [];

        foreach ($practiceClasses as $pClass) {
            /**@var Schedule[] $schedules */
            $schedules = $pClass->schedules
                ->where('order', '!=', 0)
                ->where('shift', '=', 1)
                ->map(function ($schedule) {
                    return [
                        'schedule_date' => $schedule->schedule_date,
                        'session' => $schedule->session,
                        'room_location' => $schedule->practiceRoom->location ?? 'No room info',
                        'room_name' => $schedule->practiceRoom->name ?? 'No room info',
                    ];
                });

            foreach ($schedules as $schedule) {
                $time = match ($schedule['session']) {
                    1 => '07:00:00',
                    2 => '12:30:00',
                    3 => '17:55:00',
                };

                $responseData[] = [
                    'className' => $pClass->practice_class_name,
                    'classTime' => $schedule['schedule_date'] . ' ' . $time,
                    'roomLocation' => $schedule['room_location'],
                    'roomName' => $schedule['room_name'],
                ];
            }
        }

        return $responseData;
    }

    /**
     * @param $scheduleDate
     * @param $session
     * @param $shift
     * @param $pRoomId
     * @return bool
     */
    public function isPracticeRoomAvailable($scheduleDate, $session, $shift, $pRoomId): bool
    {
        return !Schedule::where('schedule_date', $scheduleDate)
            ->where('session', $session)
            ->where('shift', $shift)
            ->where('practice_room_id', $pRoomId)
            ->exists();
    }

}
