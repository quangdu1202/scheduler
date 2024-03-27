<?php

namespace App\Http\Controllers;

class ModuleController extends Controller
{
    public function index()
    {
        return view('components.modules');
    }
}
