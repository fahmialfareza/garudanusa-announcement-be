<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
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

    public function setEvent(Request $request)
    {
        $this->validate($request, [
            'countdown' => 'required|date',
            'event_name' => 'required',
            'header_footer_name' => 'required',
            'selection_phase' => 'required',
            'note' => 'required'
        ]);

        $key = "lastevent";
        $seconds = 1000;

        $lastEvent = Event::orderBy('id', 'DESC')->first();

        $event = new Event;

        if ($request->hasFile('desktop_photo')) {
            $file_size = $request->file('desktop_photo')->getSize();
            if ($file_size > 1024000) {
                abort(400, "Background Desktop tidak boleh lebih dari 1 MB!");
            }

            $file_mime = $request->file('desktop_photo')->getClientMimeType();
            print_r($file_mime);
            if ($file_mime == "image/jpeg" || $file_mime == "image/png" || $file_mime == "image/webp") {
                $original_filename = $request->file('desktop_photo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/images/';
                $desktop_photo = 'U-' . time() . '.' . $file_ext;

                if ($request->file('desktop_photo')->move($destination_path, $desktop_photo)) {
                    $event->desktop_photo = '/upload/images/' . $desktop_photo;
                }
            } else {
                abort(400, "Background Desktop harus berupa gambar!");
            }
        } else {
            if ($lastEvent) {
                $event->desktop_photo = $lastEvent->desktop_photo;
            }
        }

        if ($request->hasFile('mobile_photo')) {
            $file_size = $request->file('mobile_photo')->getSize();
            if ($file_size > 1024000) {
                abort(400, "Background Mobile tidak boleh lebih dari 1 MB!");
            }

            $file_mime = $request->file('mobile_photo')->getClientMimeType();
            if ($file_mime != "image/jpeg" || $file_mime != "image/png" || $file_mime != "image/webp") {
                $original_filename = $request->file('mobile_photo')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/images/';
                $mobile_photo = 'U-' . time() . '.' . $file_ext;

                if ($request->file('mobile_photo')->move($destination_path, $mobile_photo)) {
                    $event->mobile_photo = '/upload/images/' . $mobile_photo;
                }
            } else {
                abort(400, "Background Mobile harus berupa gambar!");
            }
        } else {
            if ($lastEvent) {
                $event->mobile_photo = $lastEvent->mobile_photo;
            }
        }

        $event->date = $request->input("countdown");
        $event->event_name = $request->input("event_name");
        $event->header_footer_name = $request->input("header_footer_name");
        $event->selection_phase = $request->input("selection_phase");
        $event->note = $request->input('note');
        $event->save();

        app('redis')->set($key, $event);
        app('redis')->expire($key, $seconds);

        return response()->json(["status" => "OK", 'data' => $event]);
    }

    public function getEvent(Request $request)
    {
        $key = "lastevent";
        $seconds = 1000;
        $event = null;

        if (app('redis')->exists($key)) {
            $eventData = app("redis")->get($key);
            $event = json_decode($eventData);
            return response()->json(["status" => "OK", 'data' => $event]);
        }

        $event = Event::orderBy('id', 'DESC')->first();
        if ($event) {
            app('redis')->set($key, $event);
            app('redis')->expire($key, $seconds);
        }

        return response()->json(["status" => "OK", 'data' => $event]);
    }
}