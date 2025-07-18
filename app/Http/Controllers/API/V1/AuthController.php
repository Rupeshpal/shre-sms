<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|confirmed|min:8',
        ]);

    $user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'phone'    => $request->phone,
    'role'     => 'admin',
    'password' => Hash::make($request->password),
]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'version'       => 'v1',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'version'       => 'v1',
            'access_token'  => $token,
            'message'       => 'Login successful',
            'token_type'    => 'Bearer',
            'user'          => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully (v1)']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
