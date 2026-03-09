<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required', // Since users might log in via phone in POS/Web context
            'password' => 'required',
        ]);

        // Attempt login via email first (for staff/admin)
        if (Auth::attempt(['email' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
        } 
        // Attempt login via phone next
        elseif (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
        } else {
            throw ValidationException::withMessages([
                'phone' => ['Invalid credentials provided.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('branch'),
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('branch'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
