<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page.
     */
    public function index()
    {
        return view('welcome');
    }
}
