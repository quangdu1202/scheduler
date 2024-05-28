<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Auth;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * @var Helper
     */
    protected Helper $helper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Helper $helper
    )
    {
//        $this->middleware('auth');
        $this->helper = $helper;
    }

    /**
     * Show the application dashboard.
     *
     * @return RedirectResponse
     */
    public function index()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on user type
            if ($user->isAdmin()) {
                return redirect()->route('practice-classes.index');
            } elseif ($user->isTeacher()) {
                return redirect()->route('teacher.manage-classes');
            } elseif ($user->isStudent()) {
                return redirect()->route('student.manage-classes');
            }
        }

        // If not authenticated, redirect to login
        return redirect()->route('login');
    }

    public function test()
    {
        $data = $this->helper->getNextSchedulesOfTeacher(Auth::user()->userable->id);
        return view('test', [
            'data' => $data
        ]);
    }
}
