# Authentication Guide for Laravel API

This guide outlines the steps taken to enable authentication in a Laravel API using Laravel Sanctum. Follow these steps to implement authentication in your Laravel application.

## Table of Contents
- [Installation of Laravel Sanctum](#installation-of-laravel-sanctum)
- [Configuration](#configuration)
- [User Model Setup](#user-model-setup)
- [Middleware Configuration](#middleware-configuration)
- [API Routes Setup](#api-routes-setup)
- [Login Route Implementation](#login-route-implementation)
- [Using the Token in Postman](#using-the-token-in-postman)
- [Testing the Authentication](#testing-the-authentication)

## Installation of Laravel Sanctum

To enable API authentication, Laravel Sanctum was installed. This package provides a simple way to authenticate API requests using tokens.

1. **Install Sanctum**:
   Run the following command to install Laravel Sanctum:
   ```bash
   composer require laravel/sanctum
   ```

2. **Publish Sanctum Configuration and Migrations**:
   Publish the Sanctum configuration and migration files:
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. **Run Migrations**:
   Run the migrations to create the necessary tables for Sanctum:
   ```bash
   php artisan migrate
   ```

## Configuration

1. **Sanctum Configuration**:
   Ensure that the Sanctum configuration file (`config/sanctum.php`) is properly set up. The default configuration should work for most use cases.

2. **Auth Configuration**:
   Verify that the `config/auth.php` file is configured to use the `api` guard for API routes:
   ```php
   'guards' => [
       'api' => [
           'driver' => 'sanctum',
           'provider' => 'users',
       ],
   ],
   ```

## User Model Setup

1. **Add HasApiTokens Trait**:
   Update the `App\Models\User` model to include the `HasApiTokens` trait. This trait provides the `createToken()` method for generating API tokens:
   ```php
   <?php

   namespace App\Models;

   use Illuminate\Foundation\Auth\User as Authenticatable;
   use Illuminate\Notifications\Notifiable;
   use Laravel\Sanctum\HasApiTokens;

   class User extends Authenticatable
   {
       use HasApiTokens, Notifiable;

       // Your model code here
   }
   ```

## Middleware Configuration

1. **Add Sanctum Middleware**:
   Add Sanctum's middleware to your `app/Http/Kernel.php` file under the `$middlewareAliases` array:
   ```php
   'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
   ```

## API Routes Setup

1. **Protect API Routes**:
   Ensure that your API routes are protected with the `auth:sanctum` middleware. This middleware will automatically validate the token included in the `Authorization` header:
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       Route::get('/users', [UserController::class, 'index']);
       Route::get('/users/{id}', [UserController::class, 'show']);
       Route::post('/users', [UserController::class, 'store']);
       Route::put('/users/{id}', [UserController::class, 'update']);
       Route::delete('/users/{id}', [UserController::class, 'destroy']);
   });
   ```

## Login Route Implementation

1. **Create Login Route**:
   Implement a login route to authenticate users and issue API tokens:
   ```php
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;

   Route::post('/login', function (Request $request) {
       $credentials = $request->only('email', 'password');

       if (Auth::attempt($credentials)) {
           $user = Auth::user();
           $token = $user->createToken('API Token')->plainTextToken;

           return response()->json(['token' => $token]);
       }

       return response()->json(['error' => 'Unauthorized'], 401);
   });
   ```

## Using the Token in Postman

1. **Obtain the Token**:
   - Make a POST request to the login endpoint (`/api/login`) with the user's credentials.
   - Copy the token from the response.

2. **Use the Token in Requests**:
   - Include the token in the `Authorization` header of subsequent API requests:
     ```plaintext
     Authorization: Bearer {your-token-here}
     ```

## Testing the Authentication

1. **Test Login**:
   - Use Postman or any HTTP client to test the login endpoint. Ensure that a valid token is returned for correct credentials.

2. **Test Protected Routes**:
   - Make requests to protected API endpoints with the token in the `Authorization` header. Verify that the endpoints return the expected data for authenticated requests.

3. **Test Unauthorized Access**:
   - Attempt to access protected endpoints without a token or with an invalid token. Ensure that a `401 Unauthorized` response is returned.

## Conclusion

By following these steps, you have successfully enabled authentication in your Laravel API using Laravel Sanctum. This setup ensures that your API endpoints are protected and accessible only to authenticated users.