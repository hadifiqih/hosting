<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Job;


class DaruratController extends Controller
{
    
    public function create()
    {
        $employees = Employee::where('division', 'Stempel')->get();
        $jobs = Job::all();

        return view('darurat', compact('employees', 'jobs'));
    }
}
