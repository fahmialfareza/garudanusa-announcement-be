<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AnnouncementImport;

class AnnouncementController extends Controller
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

    public function importFromExcel(Request $request)
    {
        $this->validate($request, [
            'announcement' => 'required|mimes:csv,xls,xlsx'
        ]);

        $announcement = $request->file('announcement');

        Excel::import(new AnnouncementImport, $announcement);

        return response()->json(["status" => "OK", 'data' => ["message" => "Berhasil mengimport data!"]]);
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $request['id'] = $id;
        $key = 'announcement:' . $id;
        $seconds = 1000;

        $this->validate($request, [
            'phone' => 'required',
            'name' => 'required',
            'city_of_birth' => 'required',
            'date_of_birth' => 'required',
            'address_from' => 'required',
            'school' => 'required',
            'status_id' => 'required',
            'total_score' => 'required|integer',
        ]);

        $data = Announcement::where('id', $request->input("id"))->first();
        if (!$data) {
            abort(404, "Data tidak ditemukan!");
        }

        $data->update([
            "phone" => $request->input("phone"),
            "name" => $request->input("name"),
            "city_of_birth" => $request->input("city_of_birth"),
            "date_of_birth" => $request->input("date_of_birth"),
            "address_from" => $request->input("address_from"),
            "school" => $request->input("school"),
            "status_id" => $request->input("status_id"),
            "total_score" => $request->input("total_score"),
        ]);

        app('redis')->set($key, $data);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function getAnnouncement(Request $request, $id)
    {
        $request['id'] = $id;
        $key = 'announcement:' . $id;
        $seconds = 1000;

        if (app('redis')->exists($key)) {
            $redisdata = app("redis")->get($key);
            $data = json_decode($redisdata);
            return response()->json(["status" => "OK", 'data' => $data]);
        }

        $data = Announcement::where('id', $request->input("id"))->with('status')->first();
        if (!$data) {
            abort(404, "Data tidak ditemukan!");
        }

        app('redis')->set($key, $data);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function getAllAnnouncements(Request $request)
    {
        $data = Announcement::with('status')->get();
        $count = count($data);
        $start = 1;
        if ($count > 0) {
            foreach ($data as $key => $value) {
                $value->number = $start;
                $start++;
            }
        }

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function resultByPhoneNumber(Request $request, $phone)
    {
        $request['phone'] = $phone;
        $key = 'result:phone:' . $phone;
        $seconds = 1000;

        $this->validate($request, [
            'phone' => 'required',
        ]);

        if (app('redis')->exists($key)) {
            $redisdata = app("redis")->get($key);
            $data = json_decode($redisdata);
            return response()->json(["status" => "OK", 'data' => $data]);
        }

        $data = Announcement::where('phone', $request->input("phone"))
            ->with('status')
            ->first();
        if (!$data) {
            abort(404, "Nomor telepon tidak ditemukan!");
        }

        app('redis')->set($key, $data);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function deleteAllAnnouncement(Request $request)
    {
        Announcement::where("deleted_at", null)->delete();

        return response()->json(["status" => "OK", 'data' => ["message" => "Sukses!"]]);
    }
}
