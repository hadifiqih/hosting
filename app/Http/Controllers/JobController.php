<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function search()
    {
        $data = Job::where('job_name', 'LIKE', "%".request('q')."%")->get();
        return response()->json($data);
    }

    public function searchByNama(Request $request)
    {
        $data = Job::where('id', 'LIKE', "%".request('id')."%")->get();
        return response()->json($data);
    }
}
