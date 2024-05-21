<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Auth;
use Illuminate\Contracts\Support\Renderable;

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
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function test()
    {
        $data = $this->helper->getNextSchedulesOfTeacher(Auth::user()->userable->id);
        return view('test', [
            'data' => json_decode($data)
        ]);
    }
}
