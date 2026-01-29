<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

Route::get('/premium-content', function () {
    return response()->json(['success' => 'You are have access to premium content'], 200);
})->middleware(['auth:sanctum', 'auth.user','check.age']);

Route::post('/submit-form', function () {
    return response()->json(['success' => 'You have posted something'], 201);
});

Route::get('/admin-panel', function () {
    return response()->json(['success' => 'You are admin, welcom to /admin-panel'], 200);
})->middleware(['auth:sanctum','auth.user', 'check.role']);

Route::post('api/secure-endpoint', function () {
    return response()->json(['success' => 'You have connected to secure endpoint.'], 201);
})->middleware(['auth:sanctum', 'auth.user', 'check.role']);;


Route::post('/login', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        session()->put('role', Auth::user()->role);
        session()->put('age', 19);

        return response()->json(['token' => $token]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
});

Route::post('/signup', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        'password' => 'required|string|min:8'
    ]);

    $validated['password'] = bcrypt($validated['password']);
    $user = User::create($validated);

    return response()->json([
        'message' => 'User Successfully Created!',
        'data' => $user
    ]);

});