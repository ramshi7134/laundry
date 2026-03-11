<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CloudController extends Controller
{
    public function index()
    {
        return view('layouts.cloud');
    }
}
