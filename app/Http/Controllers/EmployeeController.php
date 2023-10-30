<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Employee;


class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $profil = User::find($id);
        $employee = Employee::where('email', Auth::user()->email)->first();

        return view('auth.profile-show', compact('profil', 'employee'));
    }

    public function uploadFoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false]);
        }else{
            try{
                if($request->photo){
                    $image_parts = explode(";base64,", $request->photo);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $filename = uniqid() . '.'.$image_type;
                    $path = 'profile/' . $filename;
                    Storage::disk('public')->put($path, $image_base64);

                    $employee = Employee::where('email', Auth::user()->email)->first();
                    $employee->photo = $filename;
                    $employee->save();

                    Session::flash('success', 'Foto berhasil diupload');
                    return response()->json(['success' => true]);
                }

            }
            catch(\Exception $e){
                Session::flash('error', $e->getMessage());
                return response()->json(['success' => false]);
            }
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::where('user_id', $id)->first();
        $employee->name = $request->nama;
        $employee->email = $request->email;
        $employee->phone = $request->telepon;
        $employee->division = $request->divisi;
        $employee->where_born = $request->tempat_lahir;
        $employee->date_of_birth = $request->tanggal_lahir;
        $employee->jenis_kelamin = $request->jenis_kelamin;
        $employee->address = $request->alamat;
        $employee->office = $request->office;
        $employee->joining_date = $request->tanggalMulaiKerja;
        $employee->bank_name = $request->bank_name;
        $employee->bank_account = $request->nomerRekening;
        $employee->save();

        return redirect()->route('employee.show', $id)->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
