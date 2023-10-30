<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    //
    public function __construct(){
        $this->middleware(['auth']);
    }

    public function index(){
        return view('home');
    }

    public function saveToken(Request $request){
        $user = auth()->user();
        $user->device_token = $request->token;
        $user->save();

        return response()->json([
            'token saved successfully.'
        ]);
    }

    public function sendNotification(Request $request){

        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAk1U4Cv4:APA91bHWY1Vri2IsbUkrP8qizMQb0w69EsULqFUpqNj3OGibhLZnYrt55H2KGz9ilhyFmzKUUg3s5TCI2PaT1f1qHSylwDiJPb2kOgHDtfqk4SPw0eangljAB1rYCU2qBb37LXrRm4zj';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "icon" => 'https://cdn.pixabay.com/photo/2016/05/24/16/48/mountains-1412683_960_720.png',
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }
}
