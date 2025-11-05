<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PassReportController extends Controller
{
    public function passReport()
    {
        return view('User.passreport');
    }
}
