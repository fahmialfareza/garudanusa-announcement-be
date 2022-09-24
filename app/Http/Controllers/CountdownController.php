<?php

namespace App\Http\Controllers;

use App\Models\Countdown;
use Illuminate\Http\Request;

class CountdownController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    public function setCountdown(Request $request) {
        $this->validate($request, [
            'countdown' => 'required|date',
        ]); 

        $countdown = new Countdown;

        $countdown->date = $request->input("countdown");
        $countdown->save();

        return response()->json(["status" => "OK", 'data' => $countdown]);
    }

    public function getCountdown(Request $request) {
        $countdown = Countdown::orderBy('id', 'DESC')->first();

        return response()->json(["status" => "OK", 'data' => $countdown]);
    }
}