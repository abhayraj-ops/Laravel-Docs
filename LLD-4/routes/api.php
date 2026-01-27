<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['check.age'])->group(function () {

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

    })->middleware('auth.user');

});

Route::get('/premium-content', function () {

})->middleware(['auth.user', 'check.role']);


Route::get('/api/data', function () {

})->middleware(['log.request']);

Route::post('/login', function () { });

Route::post('/signup', function () { });

