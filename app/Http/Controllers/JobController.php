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
        $search = $request->term;
        $data = Job::where('job_name', 'LIKE', "%".$search."%")->get();
        return response()->json($data);
    }

    public function searchByCategory(Request $request)
    {
        $search = $request->kategoriProduk;
        $hasilKetik = $request->q;
        if(isset($search) && !isset($hasilKetik)){
            $data = Job::where('kategori_id','=', $search)->get();
        }elseif(isset($search) && isset($hasilKetik)){
            $data = Job::where('kategori_id','=', $search)->where('job_name', 'LIKE', "%".$hasilKetik."%")->get();
        }else{
            $data = Job::where('job_name', 'LIKE', "%".$hasilKetik."%")->get();
        }

        return response()->json($data);
    }
}
