<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
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
 
		return response()->json(["status" => "OK", 'data' => ["message" => "Success to import"]]);
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
            abort(404, "Phone number is not found");
        }

        return response()->json(["status" => "OK", 'data' => $data]);
    }

    public function deleteAllAnnouncement(Request $request) {
        Announcement::where("deleted_at", null)->delete();

        return response()->json(["status" => "OK", 'data' => ["message" => "Success"]]);
    }
}
