<?php

namespace App\Helper;

use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\Schedule\Schedule;
use App\Models\Student\Student;
use App\Models\StudentModuleClass\StudentModuleClass;
use App\Models\Teacher\Teacher;
use App\Services\PracticeClass\PracticeClassService;
use App\Services\Registration\RegistrationService;
use App\Services\Student\StudentService;
use App\Services\Teacher\TeacherService;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use function MongoDB\BSON\toJSON;

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
     * @param $teacherId
     * @return \Illuminate\Support\Collection
     */
    public function getModulesByTeacherId($teacherId): \Illuminate\Support\Collection
    {
        return ModuleClass::whereHas('teacher', function ($query) use ($teacherId) {
            $query->where('id', $teacherId);
        })->with('module')
            ->get()
            ->map(function ($moduleClass) {
                return $moduleClass->module;
            })
            ->unique('id')
            ->values();
    }

    /**
     * @param $studentId
     * @return \Illuminate\Support\Collection
     */
    public function getModulesByStudentId($studentId): \Illuminate\Support\Collection
    {
        $student = Student::with(['moduleClasses.module'])->find($studentId);

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

    public function getNextSchedulesOfTeacher($teacherId)
    {
        /**@var Teacher $teacher*/
        $teacher = $this->teacherService->findOrFail($teacherId);

        $practiceClasses = $teacher->practiceClasses;

        $responseData = [];

        foreach ($practiceClasses as $pClass) {
            /**@var Schedule[] $schedules*/
            $schedules = $pClass->schedules
                ->where('order', '!=', 0)
                ->where('shift', '=', 1)
                ->map(function ($schedule) {
                    return [
                        'schedule_date' => $schedule->schedule_date,
                        'session' => $schedule->session
                    ];
                })
            ;

            foreach ($schedules as $schedule) {
                $time = match ($schedule['session']) {
                    1 => '07:00:00', 2 => '12:30:00', 3 => '17:55:00',
                };

                $responseData[] = [
                    'className' => $pClass->practice_class_name,
                    'classTime' => $schedule['schedule_date'] . ' ' . $time,
                ];
            }
        }

        return Json::encode($responseData);
    }
}
