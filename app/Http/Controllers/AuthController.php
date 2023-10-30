<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Sales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    public function __construct()
    {
        if(Auth::viaRemember()){
            return view('page.dashboard');
        }
    }
    //Menampilkan halaman login
    public function index() {
        return view('auth.login');
    }

    public function create()
    {
        $sales = Sales::all();
        return view('auth.register', compact('sales'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        //cek apakah email dan password benar
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            //menyimpan data pengguna ke dalam session
            $request->session()->put('user', $user);

            if(Auth::attempt($credentials, $remember)){
                $cookie = Cookie::make('user', $user, 1440);
                return view('page.dashboard')->withCookie($cookie);
            }
            //jika email dan password benar
            return view('page.dashboard');
        }
        //jika email dan password salah
        return redirect()->route('auth.login')->with('error', 'Email atau password salah !');

    }

    public function logout(){
        //logout user
        Auth::logout();

        //kembalikan ke halaman login
        return redirect()->route('auth.login')->with('logout', 'Logout berhasil !');
    }

    public function store(Request $request)
    {
        //Membuat rules validasi
        $rules = [
            'nama' => 'required|min:5|max:50',
            'email' => 'required|email|unique:users',
            'telepon' => 'required|min:10|max:13',
            'password' => 'required|min:8|max:35',
            'tahunMasuk' => 'required',
            'divisi' => 'required',
            'lokasi' => 'required',
            'terms' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->route('auth.register')->withErrors($validator)->withInput();
        }else{

        if($request->roleProduksi){
            $role = $request->roleProduksi;
        }else if($request->roleSales){
            $role = $request->roleSales;
        }else if($request->roleDesain){
            $role = $request->roleDesain;
        }else if($request->roleKeuangan){
            $role = $request->roleKeuangan;
        }else if($request->roleLogistik){
            $role = $request->roleLogistik;
        }else if($request->roleManajemen){
            $role = $request->roleManajemen;
        }else{
            $role = null;
        }

        //menentukan lokasi kerja
        $tempatKerja = $request->lokasi;
        if ($tempatKerja == "Surabaya"){
            $tempatKerja = "1";
        }else if ($tempatKerja == "Malang"){
            $tempatKerja = "2";
        }else if ($tempatKerja == "Kediri"){
            $tempatKerja = "3";
        }else if ($tempatKerja == "Sidoarjo"){
            $tempatKerja = "4";
        }
        
        $indexTempatKerja = $request->lokasi;

        //menentukan divisi
        $divisi = $request->divisi;

        //tahun masuk
        $tahunMasuk = $request->tahunMasuk;
        $tahunMasuk = substr($tahunMasuk, -2);

        //membuat user baru
        $user = new User;
        $user->name = ucwords(strtolower($request->nama));
        $user->email = $request->email;
        $user->phone = $request->telepon;
        $user->password = bcrypt($request->password);
        $user->role = $role;
        $user->divisi = $request->divisi;
        $user->save();

        //membuat nip baru dengan mengambil 2 digit terakhir index tempat kerja, 2 digit terakhir tahun masuk, 2 digit terakhir index divisi, dan 2 digit terakhir index user
        $nip = $tempatKerja.$tahunMasuk.$user->id;

        //membut employee baru
        $employee = new Employee;
        $employee->nip = $nip;
        $employee->name = ucwords(strtolower($request->nama));
        $employee->email = $request->email;
        $employee->phone = $request->telepon;
        $employee->division = ucwords($request->divisi);
        $employee->office = $request->lokasi;
        $employee->user_id = $user->id;
        $employee->save();

        //mengubah user_id pada tabel sales dengan id user yang baru dibuat
        if($request->roleSales){
            $sales = Sales::where('id', $request->salesApa)->first();
            $sales->user_id = $user->id;
            $sales->save();
        }
    }
        //jika user berhasil dibuat
        return redirect()->route('auth.login')->with('success-register', 'Registrasi berhasil, silahkan login');
    }

    public function generateToken()
    {
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $userId = "user-" . Auth::user()->id;
        $token = $beamsClient->generateToken($userId);

        $user = User::find(Auth::user()->id);
        $user->beams_token = $token;
        $user->save();

        //Return the token to the client
        return response()->json($token);
    }

}
