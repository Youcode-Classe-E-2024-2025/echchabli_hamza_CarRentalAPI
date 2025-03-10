<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
      


    public function login(Request $request)
    {
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();
            
            // Create the token
            $token = $user->createToken('authToken')->plainTextToken;
    
            // Return the user and token
            return response()->json(['user' => $user, 'token' => $token], 200);
    }
}

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);

        return 'logout';
    }


    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
      

    }
}
