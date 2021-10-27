<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return User::get();
    }

    public function register(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();
        $token = $user->createToken('myToken')->plainTextToken; // login in token

        return response()->json([
            'user' => $user,
            'token' => $token
            ]);
    }
    public function logout(Request $request)
    {
        // auth()->user()->tokens()->delete();
        
        return response()->json(['sms' => 'Logout out']);
    }

    // Loging function
    public function login(Request $request)
    {
       
        $user = User::where('email', $request->email)->first();
        
        $token = $user->createToken('myToken')->plainTextToken; // login in token
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Bad login'], 401);
        }
        return response()->json([
            'user' => $user,
            'token' => $token
            ]);
    }
}
