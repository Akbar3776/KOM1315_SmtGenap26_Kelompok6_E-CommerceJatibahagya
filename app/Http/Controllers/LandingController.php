<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function chat()
    {
        return view('pages.chat');
    }
}
