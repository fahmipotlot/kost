<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|confirmed|string|min:8',
            'type' => 'required|numeric|min:0|max:2'
        ]);

        // set user credit
        if ($request->type == 0) {
            $credit = 20;
        } elseif ($request->type == 1) {
            $credit = 20;
        } else {
            $credit = 0;
        }

        // create user data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'credit' => $credit,
            'password' => Hash::make($request->password)
        ];

        $user = User::create($data);

        return response()->json([
            'message' => 'You have successfully register'
        ], 200);
    }

    public function login(Request $request)
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereRaw('LOWER(email) = ?', strtolower(request()->email))->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }

        $authenticated = Auth::attempt([
            'email' => $user->email,
            'password' => request()->password
        ]);

        if ($authenticated) {
            $user->tokens()->delete();
            $user = Auth::user();
            $user->token = $user->createToken('auth_token')->plainTextToken;;

            return $user;
        } else {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }
    }

    public function profile()
    {
        return Auth::user();
    }
}
