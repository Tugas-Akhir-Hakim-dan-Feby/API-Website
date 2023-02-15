<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function app()
    {
        return view('app');
    }

    public function auth()
    {
        return view('auth');
    }
}
