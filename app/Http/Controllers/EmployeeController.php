<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MProf;
use App\User;
use App\OfficeLocat;
class EmployeeController extends Controller
{
    //
    public function index(Request $request)
    {
        $get_data_prof = MProf::where('status', 1)->get();
        return view('list_employee')->with('get_data_prof', $get_data_prof);
    }
}
