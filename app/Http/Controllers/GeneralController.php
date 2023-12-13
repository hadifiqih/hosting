<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getProvinsi()
    {
        //Mengambil data provinsi dari API
        $provinsi = Http::get('https://sipedas.pertanian.go.id/api/wilayah/list_pro?thn=2023')->json();

        //Mengembalikan data provinsi dalam bentuk JSON
        return response()->json($provinsi);
    }

    public function getKota(Request $request)
    {
        //Mengambil data kota dari API
        $kota = Http::get('https://sipedas.pertanian.go.id/api/wilayah/list_kab?thn=2023&lvl=11&pro=' . $request->provinsi)->json();

        //Mengembalikan data kota dalam bentuk JSON
        return response()->json($kota);
    }
}
