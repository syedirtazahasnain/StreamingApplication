<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
            ]);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_role' => 'user',
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return success_res(200, 'User registered successfully', [
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return error_res(403, 'Validation failed', $e->errors());
        } catch (\Exception $e) {
            return error_res(403, 'Registration failed');
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return error_res(403, 'Invalid credentials');
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return success_res(200, 'Login successful', [
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return error_res(403, 'Validation failed', $e->errors());
        } catch (\Exception $e) {
            return error_res(403, 'Login failed');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return error_res(403, 'Unauthenticated.'); // use your custom response function
            }
            $request->user()->tokens()->delete();
            return success_res(200, 'Logged out successfully');
        } catch (\Exception $e) {
            return error_res(403, 'Login failed');
        }
    }
}
