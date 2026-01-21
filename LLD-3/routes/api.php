<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;


Route::put('/users/update/{id}', [UserController::class, 'update']);

Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

Route::get('/users/{id}', [UserController::class, 'show']);

Route::get('/users', [UserController::class, 'index']);

Route::post('users/create', [UserController::class, 'create']);


Route::get('posts', [PostController::class, 'index']);

Route::get('posts/{id}', [PostController::class, 'show']);

Route::post('posts/create', [PostController::class, 'create']);

Route::put('posts/update/{id}', [PostController::class, 'update']);

Route::delete('posts/delete/{id}', [PostController::class, 'destroy']);


Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('dashboard',function ()
    {
        return "success";
    });
    
    Route::get('/users', [UserController::class, 'index']);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
