<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('auth.superadmin', compact('users'));
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //Memproses data yang dikirim dari form edit
        $user = User::find($id);

        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('user.index')->with('success', 'Data berhasil diubah !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editDesainer(Request $request)   
    {
        //ambil data desainer dari table user dengan can_design = 1
        $desainer = User::where('can_design', '1')->get();

        return Datatables()->of($desainer)
        ->addIndexColumn()
        ->addColumn('nama_desainer', function($data){
            return $data->name;
        })
        ->addColumn('jumlah_desain', function($data){
            return $data->design_load;
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';
            $button .= '<button href="javascript:void(0)" onclick="ubahDesainer('. $data->id .')" class="btn btn-sm btn-dark btnDesainer"><i class="fas fa-user"></i> Pilih</button>';
            $button .= '</div>';
            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
