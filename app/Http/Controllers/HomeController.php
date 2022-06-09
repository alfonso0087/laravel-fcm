<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    return view('home');
  }

  public function saveToken(Request $request)
  {
    auth()->user()->update([
      'device_token' => $request->token,
    ]);
    return response()->json(['Token tersimpan']);
  }

  public function sendNotification(Request $request)
  {
    $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
    // return $firebaseToken;

    // Ambil dari cloud messaging API (Legacy)
    $SERVER_API_KEY = 'AAAA_Tlwi58:APA91bHa4ZKNHGDRASMEODc3XehoD0qjMVlRuy4v5M5I5Mj1igPS69f2ao4-PXuRLKMSlgxQD8c1iw1Uqs4aZJ5yTIlmCc7Ph4MxIlaug6yh0uoY4WuRLXs9YWrO8AwYiIQVctPEeruM';

    $title = 'Notifikasi dari Laravel';
    $body = 'Request by : ' . auth()->user()->name . 'Waktu : ' . now();
    $data = [
      "registration_ids" => $firebaseToken,
      "notification" => [
        "title" => $title,
        "body" => $body,
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
