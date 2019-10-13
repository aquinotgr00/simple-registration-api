<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function profile()
    {
        return response()->json(Auth::user());
    }

    public function login(Request $request)
    {
        // validate the request
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        return response()->json($request->all());
    }

    public function register(Request $request)
    {
        // validate the request
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'dob' => 'date',
        ]);

        // generate random api token & password for user.
        $api_token = Str::random(40);
        $password = Hash::make('secret');

        $request->merge([
            'api_token' => $api_token,
            'password' => $password,
        ]);

        // store new user
        $user = User::insert($request->all());

        return response()->json($user);
    }
}
