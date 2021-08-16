<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;


class UserController extends Controller
{
    public function userRegistration(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $randomId = mt_rand(100000, 999999);
        $user = new User();
        $user['email'] = $request->email;
        $user['password'] = Hash::make($request->password);
        $user['registration_pin'] = $randomId;
        $user->save();

        Mail::send('emails.confirmPin', ['user' => $user], function ($m) use ($user) {
            $m->from('vaibhav@7technosoftsolutions.com', 'Your Application');
            $m->to($user['email'])->subject('Confirm Pin');
        });

        return response()->json([
            'message' => 'Email Sent Successfully',
        ]);
    }

    public function confirmPin(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user['registration_pin'] == $request->pin) {
            return response()->json([
                'message' => 'Registration Completed',
            ]);
        } else {
            return response()->json([
                'message' => 'Pin is Wrong',
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Unauthorized'
            ]);
        }
        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        $user->name = $request->name;
        $user->user_name = $request->user_name;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $imageName = time() . '.' . $request->avatar->extension();

            $request->avatar->move(public_path('images'), $imageName);
            $user->avatar = $imageName;
        }

        $user->save();
        return response()->json([
            'message' => 'User Profile Updated Successfully',
        ]);
    }
}
