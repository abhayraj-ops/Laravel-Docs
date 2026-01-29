# Laravel Rate Limiting -- Practical Guide

This document explains how to implement **rate limiting in Laravel
(v12+)** using middleware, authentication, and sessions.

------------------------------------------------------------------------

## 1. What is Rate Limiting?

Rate limiting restricts how many requests a client can make within a
given time window.

Common use cases: - Prevent brute-force login attempts - Protect APIs
from abuse - Control traffic per user or per IP

Laravel provides this via: - `RateLimiter` - `throttle` middleware

------------------------------------------------------------------------

## 2. Where Rate Limiters Are Defined

All custom rate limiters are defined in:

    app/Providers/AppServiceProvider.php

Inside the `boot()` method.

------------------------------------------------------------------------

## 3. Basic Auth + IP Based Rate Limiter

``` php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api-data', function (Request $request) {
    return Limit::perMinute(60)
        ->by($request->user()?->id ?: $request->ip());
});
```

### Behavior

-   Authenticated users → limited by `user_id`
-   Guests → limited by IP
-   60 requests per minute

------------------------------------------------------------------------

## 4. Applying Rate Limiting via Middleware

``` php
Route::middleware([
    'auth',
    'throttle:api-data'
])->post('/api/data', [DataController::class, 'store']);
```

Middleware order matters: 1. Session (`web`) 2. Authentication (`auth`)
3. Rate limiting (`throttle`)

------------------------------------------------------------------------

## 5. Login Rate Limiting (Security Critical)

``` php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

``` php
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');
```

Prevents brute-force attacks.

------------------------------------------------------------------------

## 6. Role-Based Rate Limiting

``` php
RateLimiter::for('user-actions', function (Request $request) {
    if ($request->user()?->is_admin) {
        return Limit::none();
    }

    return Limit::perMinute(30)->by($request->user()->id);
});
```

------------------------------------------------------------------------

## 7. Custom Rate Limit Response

``` php
RateLimiter::for('custom', function (Request $request) {
    return Limit::perMinute(10)->response(function () {
        return response()->json([
            'message' => 'Too many requests. Please try again later.'
        ], 429);
    });
});
```

------------------------------------------------------------------------

## 8. Global API Rate Limiting

``` php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)
        ->by($request->user()?->id ?: $request->ip());
});
```

Automatically applied when using the `api` middleware group.

------------------------------------------------------------------------

## 9. Common Mistakes

### ❌ Wrong Request Import

``` php
use Illuminate\Http\Client\Request; // WRONG
```

### ✅ Correct Import

``` php
use Illuminate\Http\Request;
```

------------------------------------------------------------------------

## 10. Summary

-   Define rules with `RateLimiter::for()`
-   Enforce rules with `throttle:name`
-   Auth enables per-user limits
-   Guests fall back to IP-based limits
-   Always check middleware order

------------------------------------------------------------------------

**Status:** Production Ready\
**Laravel Version:** 12+
