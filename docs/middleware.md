# Middleware

## Introduction

Middleware provide a mechanism for inspecting and filtering HTTP requests in Laravel. They act as layers that requests must pass through before reaching the application logic.

## Types of Middleware

### 1. Global Middleware
- Runs on every HTTP request
- Registered in `bootstrap/app.php`
- Example: `EnsureTokenIsValid`

### 2. Route-Specific Middleware
- Assigned to specific routes
- Example: `Authenticate` for `/profile`

### 3. Middleware Groups
- Group multiple middleware under a single key
- Example: `admin` group for admin routes

## Key Methods

### `handle` Method
- Required method in every middleware
- Parameters: `Request $request`, `Closure $next`
- Returns: `Response`

### `terminate` Method
- Optional method for post-response tasks
- Parameters: `Request $request`, `Response $response`

## Registration

### Global Middleware
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->append(EnsureTokenIsValid::class);
})
```

### Route Middleware
```php
Route::get('/profile', function () {
    // ...
})->middleware(Authenticate::class);
```

### Middleware Groups
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->group('admin', [
        EnsureTokenIsValid::class,
        CheckAdmin::class,
    ]);
})
```

### Multiple Middleware on a Route
```php
Route::get('/dashboard', function () {
    // ...
})->middleware([
    Authenticate::class,
    CheckAdmin::class,
    LogRequests::class,
]);
```

## How Multiple Middleware Work

When multiple middleware are assigned to a route, they execute in the order they are defined. Each middleware processes the request and passes it to the next middleware using the `$next` closure. The response flows back through the middleware in reverse order.

### Example Flow
1. **Request Flow**: `Authenticate` → `CheckAdmin` → `LogRequests` → Controller
2. **Response Flow**: Controller → `LogRequests` → `CheckAdmin` → `Authenticate` → Client

### Code Example
```php
// Authenticate Middleware
public function handle($request, $next) {
    if (!$request->user()) {
        return redirect('/login');
    }
    return $next($request);
}

// CheckAdmin Middleware
public function handle($request, $next) {
    if (!$request->user()->isAdmin()) {
        abort(403);
    }
    return $next($request);
}

// LogRequests Middleware
public function handle($request, $next) {
    $response = $next($request);
    Log::info('Request processed');
    return $response;
}
```

## Important Variables

- `$request`: Instance of `Illuminate\Http\Request`
- `$next`: Closure to pass the request to the next layer
- `$response`: Instance of `Symfony\Component\HttpFoundation\Response`

## Best Practices

1. Keep middleware focused on a single responsibility
2. Use dependency injection for middleware dependencies
3. Handle errors gracefully with appropriate responses
4. Document middleware functionality and parameters
5. Test middleware behavior thoroughly

## Conclusion

Middleware provide a powerful way to filter HTTP requests in Laravel applications. By understanding the types, key methods, and registration processes, you can effectively use middleware to add layers of functionality to your application.