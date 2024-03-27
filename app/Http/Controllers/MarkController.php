<?php

namespace App\Http\Controllers;

class MarkController extends Controller
{
    public function index()
    {

    }

    public function markByModule()
    {
        return view('components.mark-module-class');
    }

    public function markByPractice()
    {
        return view('components.mark-practice-class');
    }
}
