<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['web','check.age'])->group(function () {

    Route::get('/adult-content', function () {
        return response()->json(['success' => 'You are old enough'], 200);
    });

    Route::get('/movies', function () {
        return response()->json(['success' => 'You are old enough to access movies'], 200);
    });

    Route::get('/events', function () {
        return response()->json(['success' => 'You are old enough events'], 200);
    });

    Route::get('/premium-content', function () {
        return response()->json(['success' => 'You are have access to premium content'], 200);
    })->middleware(['auth:sanctum', 'auth.user']);

});

Route::get('/api/data', function () {

})->middleware(['log.request']);


