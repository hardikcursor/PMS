<?php

namespace App\Http\Controllers\companyAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function dailyreport()
    {
        return view('company-admin.Reports.dailyreport');
    }
}
