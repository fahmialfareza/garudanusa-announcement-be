<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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

    // Register
    public function register(Request $request) {
        $this->validate($request, [
			'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|min:8',
		]);

        $user = new User;

        $user->name = $request->input("name");
        $user->username = $request->input("username");
        $user->password = Crypt::encrypt($request->input("password"));
        $user->save();

        $token = Auth::login($user);

        return response()->json(["status" => "OK", 'data' => ["token" => $token]]);
    }

    // Login
    public function login(Request $request) {
        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required',
		]);

        $user = User::where('username', $request->input("username"))->first();

        if (!$user) {
            abort(401, "Username or password is wrong");
        }

        if (Crypt::decrypt($user->password) !=$request->input("password")) {
            abort(401, "Username or password is wrong");
        }

        $token = Auth::login($user);

        return response()->json(["status" => "OK", 'data' => ["token" => $token]]);
    }

    public function me(Request $request) {
        $user = User::find($request->user_id);

        if (!$user) {
            abort(404, "User is not found");
        }

        return response()->json(["status" => "OK", 'data' => $user]);
    }

    public function getAllUsers(Request $request) {
        $users = User::all();

        return response()->json(["status" => "OK", 'data' => $users]);
    }

    public function deleteUser(Request $request, $id) {
        if ($id == 1) {
            abort(403, "Can not delete super user");
        }

        if ($request->user_id == $id) {
            abort(403, "Can not delete current user");
        }

        $user = User::find($id);

        if (!$user) {
            abort(404, "User is not found");
        }

        $user->delete();

        return response()->json(["status" => "OK", 'data' => ["message" => "Success"]]);
    }
}
