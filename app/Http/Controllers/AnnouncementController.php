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

    public function importFromExcel(Request $request) {
		$this->validate($request, [
			'announcement' => 'required|mimes:csv,xls,xlsx'
		]);

        $announcement = $request->file('announcement');
        
        Excel::import(new AnnouncementImport, $announcement);
 
		return response()->json(["status" => "OK", 'data' => ["message" => "Berhasil mengimport data!"]]);
    }

    public function updateAnnouncement(Request $request, $id) {
        $request['id'] = $id;

        $this->validate($request, [
            'phone' => 'required|numeric|digits_between:6,13',
            'name' => 'required',
            'city_of_birth' => 'required',
            'date_of_birth' => 'required',
            'address_from' => 'required',
            'school' => 'required',
            'result' => 'required',
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
            "result" => $request->input("result"),
            "total_score" => $request->input("total_score"),
        ]);

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function getAnnouncement(Request $request, $id) {
        $request['id'] = $id;
        
        $data = Announcement::where('id', $request->input("id"))->first();
        if (!$data) {
            abort(404, "Data tidak ditemukan!");
        }

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function getAllAnnouncements(Request $request) {
        $data = Announcement::paginate(20);
        $count = count($data);
        $start = 1;
        if ($request->page == null || $request->page == "1") {
            $start = 1;
        } else {
            $start = 20 * ($request->page - 1) + 1;
        }

        if ($count > 0) {
            foreach ($data as $key=>$value) {
                $value->number = $start;
                $start++;
            }
        }

        return response()->json(["status" => "OK", 'data' => $data, 'page' => $start]);
    }

    public function resultByPhoneNumber(Request $request, $phone) {
        $request['phone'] = $phone;

        $this->validate($request, [
            'phone' => 'required|numeric|digits_between:6,13',
		]);

        $data = Announcement::where('phone', $request->input("phone"))->first();

        if (!$data) {
            abort(404, "Nomor telepon tidak ditemukan!");
        }

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function deleteAllAnnouncement(Request $request) {
        Announcement::where("deleted_at", null)->delete();

        return response()->json(["status" => "OK", 'data' => ["message" => "Sukses!"]]);
    }
}
