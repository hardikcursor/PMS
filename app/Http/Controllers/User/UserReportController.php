<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function userReport()
    {
        return view('User.userreport');
    }
}
