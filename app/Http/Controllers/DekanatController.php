<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DekanatController extends Controller
{
    public function index() {
        return view('dekanatadmin.index');
    }
}
