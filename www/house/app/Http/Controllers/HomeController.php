<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use APP\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dump(User::id());
        return view('home');
    }
}
