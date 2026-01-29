<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'check.age'])->group(function () {

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

Route::get('/data', function () {

    return response()->json(['success' => 'You have access to data'], 200);

})->middleware(['throttle:api-data']);


