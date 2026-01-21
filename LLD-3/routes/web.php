<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('api/users/create', [UserController::class, 'create']);



