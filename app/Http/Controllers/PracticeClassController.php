<?php

namespace App\Http\Controllers;

use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use Illuminate\Http\Request;

class PracticeClassController extends Controller
{
    /**
     * @var PracticeClassServiceInterface
     */
    protected PracticeClassServiceInterface $practiceClass;

    public function __construct(PracticeClassServiceInterface $practiceClass)
    {
        $this->practiceClass = $practiceClass;
    }

    public function index(Request $request, $filter = null)
    {
        $practiceClasses = $this->practiceClass->getAll();

        return view('components.practice-classes', [
            'practiceClasses' => $practiceClasses
        ]);
    }
}
