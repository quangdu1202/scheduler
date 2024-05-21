<?php

namespace App\Http\Controllers\StudentMark;

use Illuminate\Routing\Controller;

/**
 * Class StudentMarkController
 */
class StudentMarkController extends Controller
{
//    /**
//     * @var StudentMarkServiceInterface
//     */
//    protected StudentMarkServiceInterface $studentMarkService;
//
//    /**
//     * @var StudentService
//     */
//    protected StudentService $studentService;
//
//    /**
//     * @var ModuleClassService
//     */
//    protected ModuleClassService $moduleClassService;
//
//    /**
//     * @var StudentModuleClassService
//     */
//    protected StudentModuleClassService $studentModuleClassService;
//
//    /**
//     * @var PracticeClassService
//     */
//    protected PracticeClassService $practiceClassService;
//
//    /**
//     * @var RegistrationService
//     */
//    protected RegistrationService $registrationService;
//
//    /**
//     * @var MarkTypeService
//     */
//    protected MarkTypeService $markTypeService;
//
//    /**
//     * @var Helper
//     */
//    protected Helper $helper;
//
//    /**
//     * @param StudentMarkServiceInterface $studentMarkService
//     * @param StudentService $studentService
//     * @param ModuleClassService $moduleClassService
//     * @param StudentModuleClassService $studentModuleClassService
//     * @param PracticeClassService $practiceClassService
//     * @param RegistrationService $registrationService
//     * @param MarkTypeService $markTypeService
//     * @param Helper $helper
//     */
//    public function __construct(
//        StudentMarkServiceInterface $studentMarkService,
//        StudentService              $studentService,
//        ModuleClassService          $moduleClassService,
//        StudentModuleClassService   $studentModuleClassService,
//        PracticeClassService        $practiceClassService,
//        RegistrationService         $registrationService,
//        MarkTypeService             $markTypeService,
//        Helper                      $helper
//    )
//    {
//        $this->studentMarkService = $studentMarkService;
//        $this->studentService = $studentService;
//        $this->moduleClassService = $moduleClassService;
//        $this->studentModuleClassService = $studentModuleClassService;
//        $this->practiceClassService = $practiceClassService;
//        $this->registrationService = $registrationService;
//        $this->markTypeService = $markTypeService;
//        $this->helper = $helper;
//    }
//
//    /**
//     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
//     */
//    public function index()
//    {
//        $studentMarks = $this->studentMarkService->getAll();
//
//        $students = $this->studentService->getAll();
//
//        $practiceClasses = $this->practiceClassService->getAll();
//
//        return view('student_mark.mark-practice-class', [
//            'studentMarks' => $studentMarks,
//            'students' => $students,
//            'practiceClasses' => $practiceClasses
//        ]);
//    }
//
//    /**
//     * @param StudentMark $studentMark
//     *
//     */
//    public function show(StudentMark $studentMark)
//    {
//    }
//
//    /**
//     * @param Request $request
//     */
//    public function store(Request $request)
//    {
//    }
//
//    /**
//     * @param StudentMark $studentMark
//     * @param Request $request
//     */
//    public function update(StudentMark $studentMark, Request $request)
//    {
//        return;
//    }
//
//    /**
//     * @param StudentMark $studentMark
//     *
//     * @return JsonResponse
//     * @throws Exception
//     */
//    public function destroy(StudentMark $studentMark): JsonResponse
//    {
//        $this->studentMarkService->delete($studentMark);
//
//        return Response::json(null, ResponseStatus::HTTP_NO_CONTENT);
//    }
//
//    /**
//     * @param int $practice_class_id
//     * @return JsonResponse
//     */
//    public function getMarkJsonDataByPracticeClass(int $practice_class_id)
//    {
//        $students = $this->helper->getStudentsByPracticeClass($practice_class_id);
//
//        $markTypes = $this->markTypeService->getAll()->whereIn('type', ['TX', 'GK', 'CK']);
//
//        $responseData = $students->map(function ($student) use ($markTypes) {
//
//            $marks = $this->studentMarkService->find(['student_id' => $student->id]);
//
//            /** @var Registration $registration*/
//            $registration = $this->registrationService->findOrFail($student->id);
//
//            if ($marks->count() == 0) {
//
//                $marks = $markTypes->map(function ($markType) use ($registration) {
//                    return [
//                        'module_id' => $registration->moduleClass->module_id . '___' . $registration->practiceClass->module_id,
//                        'module_class_id' => $registration->module_class_id,
//                        'practice_class_id' => $registration->practice_class_id,
//                        'student_id' => $registration->student_id,
//                        'mark_type_id' => $markType->id,
//                        'mark_value' => 0,
//                    ];
//                });
//            }
//
//            return $marks;
//        });
//
//        return response()->json($responseData);
//    }
}