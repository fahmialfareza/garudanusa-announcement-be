<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
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

    public function getAllStatuses(Request $request)
    {
        $statuses = Status::all();
        return response()->json(["status" => "OK", 'data' => $statuses]);
    }

    public function getStatus($id)
    {
        $statuses = Status::where('id', $id)->first();
        return response()->json(["status" => "OK", 'data' => $statuses]);
    }

    public function createStatus(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'message' => 'required'
        ]);

        $status = new Status;

        $status->status = $request->input("status");
        $status->message = $request->input("message");
        $status->save();

        $key = "status:" . $status->id;
        $seconds = 1000;
        app('redis')->set($key, $status);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $status]);
    }

    public function updateStatus($id, Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'message' => 'required'
        ]);

        $status = Status::where('id', $id)->first();

        $status->status = $request->input('status');
        $status->message = $request->input('message');
        $status->save();

        $key = "status:" . $status->id;
        $seconds = 1000;
        app('redis')->set($key, $status);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $status]);
    }

    public function destroyStatus($id)
    {
        $status = Status::where('id', $id)->first();
        $status->delete();

        $key = 'status' . $status->id;
        app('redis')->del($key);

        return response()->json(["status" => "OK", 'data' => null]);
    }
}