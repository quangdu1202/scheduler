<?php

namespace App\Helper;

use App\Models\Student\Student;
use App\Services\PracticeClass\PracticeClassService;
use App\Services\Registration\RegistrationService;
use App\Services\Student\StudentService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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

    public function __construct(
        StudentService       $studentService,
        PracticeClassService $practiceClassService,
        RegistrationService  $registrationService
    )
    {
        $this->studentService = $studentService;
        $this->practiceClassService = $practiceClassService;
        $this->registrationService = $registrationService;
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
     * @param int $practice_class_id
     * @return Collection
     */
    public function getStudentsByPracticeClass(int $practice_class_id): Collection
    {
        return Student::whereHas('registrations', function ($query) use ($practice_class_id) {
            $query->where('practice_class_id', $practice_class_id);
        })->with(['registrations' => function ($query) use ($practice_class_id) {
            $query->where('practice_class_id', $practice_class_id);
        }])->get()->map(function ($student) use ($practice_class_id) {
            $student->registration = $student->registrations->firstWhere('practice_class_id', $practice_class_id);
            unset($student->registrations);
            return $student;
        });
    }


}
