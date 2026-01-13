# Laravel 8.x Cheat Sheet

## Introduction

### What is Laravel?
Laravel is a web application framework with expressive, elegant syntax. A web framework provides a structure and starting point for creating your application, allowing you to focus on creating something amazing while we sweat the details.

### Key Features
- **Dependency Injection**: Robust and flexible dependency management
- **Eloquent ORM**: Expressive database abstraction layer
- **Queues & Jobs**: Background job processing
- **Testing**: Built-in support for unit and integration testing
- **Real-time Events**: WebSocket integration
- **Security**: Built-in protection against common vulnerabilities

### Progressive Framework
- **For Beginners**: Extensive documentation, step-by-step guides, video tutorials, gentle learning curve
- **For Experts**: Advanced features, scalable architecture, performance optimizations

## Installation

### Prerequisites
- PHP 8.4+
- Composer
- Laravel Installer
- Node/NPM or Bun

### Install Laravel
```bash
composer global require laravel/installer
```

### Create New Application
```bash
laravel new example-app
```

Once created, start development server:
```bash
cd example-app
npm install && npm run build
composer run dev
```

### Environment Configuration
- `.env` - Actual environment file (NOT in source control)
- `.env.example` - Template file (in source control)

## Project Structure

### Root Directory
```
📁 your-project/
├── 📁 app/                  # Core application code
├── 📁 bootstrap/           # Framework bootstrapping
├── 📁 config/              # Configuration files
├── 📁 database/            # Migrations, seeds, factories
├── 📁 public/              # Public assets & entry point
├── 📁 resources/           # Views, raw assets
├── 📁 routes/              # Route definitions
├── 📁 storage/             # Logs, caches, compiled files
├── 📁 tests/               # Test files
├── 📁 vendor/              # Composer dependencies
├── 📄 .env                 # Environment configuration
├── 📄 artisan              # Laravel CLI tool
├── 📄 composer.json        # PHP dependencies
└── 📄 README.md            # Project documentation
```

### App Directory (Most Important!)
The `app` directory contains the core code of your application.

```
📁 app/
├── 📁 Console/             # Artisan commands
├── 📁 Exceptions/          # Custom exception handlers
├── 📁 Http/                # Controllers, middleware, requests
├── 📁 Models/              # Eloquent models
├── 📁 Providers/           # Service providers
└── 📁 ...                  # Other generated directories
```

#### Http Directory
- **Controllers**: Handle HTTP requests and return responses
- **Middleware**: Filter HTTP requests
- **Requests**: Form request validation

#### Models Directory
- **Eloquent Models**: Database interaction using ActiveRecord pattern
- **Relationships**: Define table relationships
- **Business Logic**: Core application logic

#### Providers Directory
- **Service Providers**: Bootstrap application services
- **AppServiceProvider**: Main service provider
- **Custom Providers**: Add your own service providers

### Other Important Directories

#### 📁 config/
Contains all application configuration files. Key files:
- `app.php` - Core application settings
- `database.php` - Database connections
- `mail.php` - Email configuration
- `auth.php` - Authentication settings

#### 📁 database/
```
📁 database/
├── 📁 migrations/      # Database migration files
├── 📁 seeders/        # Database seeders
└── 📁 factories/      # Model factories
```

#### 📁 routes/
```
📁 routes/
├── 📄 web.php         # Web routes (with session, CSRF)
├── 📄 api.php         # API routes (stateless)
├── 📄 console.php     # Artisan commands
└── 📄 channels.php    # Broadcasting channels
```

#### 📁 public/
- Entry point (`index.php`)
- Assets (CSS, JS, images)
- Compiled frontend assets

#### 📁 resources/
```
📁 resources/
├── 📁 views/          # Blade templates
├── 📁 lang/           # Language files
├── 📁 js/             # JavaScript files
└── 📁 css/            # CSS files
```

#### 📁 storage/
```
📁 storage/
├── 📁 app/            # Application files
├── 📁 framework/      # Framework generated files
├── 📁 logs/           # Log files
└── 📁 public/         # Publicly accessible files
```

## Configuration

### Configuration Files Location
All configuration files are stored in the `config` directory. Each option is documented.

### Important Configuration Commands
```bash
php artisan about                    # Overview of app config
php artisan about --only=environment # Filter specific section
php artisan config:show database     # Show specific config file
```

### Environment Configuration
```
📄 .env.example      # Template file (should be in source control)
📄 .env             # Actual environment file (should NOT be in source control)
📄 .env.staging     # Optional staging environment file
📄 .env.production  # Optional production environment file
```

⚠️ **IMPORTANT**: Your `.env` file should **NOT** be committed to source control!

### Environment File Encryption
```bash
# Encrypt environment file
php artisan env:encrypt

# Encrypt with custom key
php artisan env:encrypt --key=3UVsEgGVK36XN82KKeyLFMhvosbZN1aF

# Encrypt specific environment
php artisan env:encrypt --env=staging

# Encrypt with readable variable names
php artisan env:encrypt --readable

# Decrypt environment file
php artisan env:decrypt

# Decrypt with custom key
php artisan env:decrypt --key=3UVsEgGVK36XN82KKeyLFMhvosbZN1aF

# Decrypt specific environment
php artisan env:decrypt --env=staging

# Force overwrite existing .env file
php artisan env:decrypt --force
```

### Environment Variable Types
- `true` → (bool) true
- `false` → (bool) false
- empty → (string) ''
- `null` → (null) null
- `"quoted"` → (string) "quoted"

### Retrieving Environment Configuration
```php
// Using env() function with default value
'debug' => (bool) env('APP_DEBUG', false),

// Determining current environment
use Illuminate\Support\Facades\App;

$environment = App::environment();

if (App::environment('local')) {
    // The environment is local
}

if (App::environment(['local', 'staging'])) {
    // The environment is either local OR staging
}
```

### Accessing Configuration Values
```php
use Illuminate\Support\Facades\Config;

// Get configuration value
$value = Config::get('app.timezone');

// Using global helper
$value = config('app.timezone');

// With default value
$value = config('app.timezone', 'Asia/Seoul');
```

### Setting Configuration at Runtime
```php
// Set single configuration value
Config::set('app.timezone', 'America/Chicago');

// Set multiple values
config(['app.timezone' => 'America/Chicago']);
```

### Typed Configuration Access
```php
// String value
Config::string('config-key');

// Integer value
Config::integer('config-key');

// Float value
Config::float('config-key');

// Boolean value
Config::boolean('config-key');

// Array value
Config::array('config-key');

// Collection value
Config::collection('config-key');
```

### Configuration Caching
```bash
# Cache all configuration files
php artisan config:cache

# Clear cached configuration
php artisan config:clear
```

⚠️ **Note**: After caching, `.env` file won't be loaded. Only use `env()` in config files!

### Configuration Publishing
```bash
# Publish specific configuration files
php artisan config:publish

# Publish all configuration files
php artisan config:publish --all
```

### Debug Mode
```bash
# Enable debug mode (for development only!)
APP_DEBUG=true

# Disable debug mode (for production)
APP_DEBUG=false
```

### Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# With auto-refresh after 15 seconds
php artisan down --refresh=15

# With retry-after header
php artisan down --retry=60

# Enable with secret token
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

# Generate secret token automatically
php artisan down --with-secret

# Disable maintenance mode
php artisan up
```

## Request Lifecycle

### Simple Overview
Understanding Laravel's request lifecycle helps you become a more confident developer. This document explains how Laravel processes incoming requests from start to finish in a simple, visual way.

### The Request Journey (6 Simple Steps)

#### 1. Entry Point
**All requests start at `public/index.php`** - this is your application's single entry point.

**What happens:**
1. Web server (Apache/Nginx) directs all requests to `public/index.php`
2. Composer autoloader loads all framework classes
3. Laravel application instance is created

#### 2. HTTP Kernel
**The HTTP Kernel orchestrates everything** - it's like the traffic controller.

**What happens:**
1. Bootstrappers configure the environment
2. Middleware stack processes the request
3. Each middleware can modify or reject the request

#### 3. Service Providers (Most Important!)
**Service Providers bootstrap Laravel's features** - they're the heart of Laravel.

**What happens:**
1. Laravel loads all service providers
2. Calls `register()` on each provider
3. Calls `boot()` on each provider
4. All Laravel features become available

#### 4. Routing
**The Router matches requests to code** - like a traffic director.

**What happens:**
1. Router finds the matching route
2. Route-specific middleware runs
3. Controller method or route closure executes
4. Response is generated

#### 5. Response Processing
**Middleware processes the response** - final checks before sending.

**What happens:**
1. Response travels back through middleware
2. Middleware can modify the response
3. Final response is prepared

#### 6. Send to Browser
**Final step - send response to user**

**What happens:**
1. HTTP Kernel returns response object to Application Instance
2. Application Instance calls `send()` method
3. Response is sent to User's Browser

### Key Points to Remember
1. **Single Entry Point**: All requests go through `public/index.php`
2. **HTTP Kernel**: Manages bootstrapping and middleware
3. **Service Providers**: Most important - they bootstrap all Laravel features
4. **Routing**: Matches URLs to controller methods or routes
5. **Middleware**: Filters both incoming requests and outgoing responses
6. **Response Flow**: Response goes back through middleware before sending

## Service Container

### Introduction
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. Dependency injection is a technique where class dependencies are "injected" into the class via the constructor or, in some cases, "setter" methods.

### Type-Hinting
Type-hinting is a simple way to tell Laravel what dependencies your class needs. Instead of manually creating objects, you just specify the class type in your constructor or method parameters, and Laravel automatically provides the right instance.

### Simple Example
```php
public function __construct(protected AppleMusic $apple)
{
    // Laravel automatically creates and injects AppleMusic
}
```

### Zero Configuration Resolution
If a class has no dependencies or only depends on other concrete classes (not interfaces), the container does not need to be instructed on how to resolve that class.

**How Zero Configuration Works:**
1. Reflection Analysis: The container examines the class constructor using reflection
2. Dependency Detection: It identifies all type-hinted dependencies in the constructor
3. Recursive Resolution: For each dependency, the container repeats the process
4. Instantiation: Once all dependencies are resolved, the class is instantiated

### When to Use Manual Container Interaction
Manual container interaction is needed when:
- Working with interfaces that need to be bound to implementations
- Using abstract classes that need concrete implementations
- Needing to configure how a class should be instantiated
- Requiring singleton or scoped instances

## Binding Basics

### Simple Bindings
```php
// Simple binding
$this->app->bind(Class::class, function ($app) {
    return new Class($app->make(Dependency::class));
});
```

### Singleton Bindings
```php
// Singleton binding (one instance for entire app)
$this->app->singleton(Service::class, function ($app) {
    return new Service();
});
```

### Instance Binding
```php
// Instance binding (existing object)
$service = new Service();
$this->app->instance(Service::class, $service);
```

### Interface Binding
```php
// Interface binding
$this->app->bind(Interface::class, Implementation::class);
```

### Contextual Binding
```php
// Contextual binding
$this->app->when(PhotoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
              return Storage::disk('local');
          });
```

### Binding Primitives
```php
// Binding primitive values
$this->app->when(UserController::class)
          ->needs('$maxUsers')
          ->give(100);

$this->app->when(UserController::class)
          ->needs('$timezone')
          ->giveConfig('app.timezone');
```

### Tagging
```php
// Tag services
$this->app->tag([CpuReport::class, MemoryReport::class], 'reports');

// Resolve tagged services
$reports = $app->tagged('reports');
```

### Extending Bindings
```php
// Extending bindings
$this->app->extend(OriginalService::class, function (OriginalService $service, Application $app) {
    $logger = $app->make(LoggerInterface::class);
    return new LoggingService($service, $logger);
});
```

### Conditional Bindings
```php
// Conditional bindings
$this->app->bindIf(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});

$this->app->singletonIf(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

### Attribute-Based Bindings
```php
// Singleton attribute
#[Singleton]
class Transistor
{
    // This class will automatically be treated as a singleton
}

// Scoped attribute
#[Scoped]
class Transistor
{
    // This class will automatically be scoped to the request lifecycle
}
```

### Resolving Services
```php
// Basic resolution
$transistor = $this->app->make(Transistor::class);

// Resolution with parameters
$transistor = $this->app->makeWith(Transistor::class, [
    'id' => 1,
    'name' => 'Main'
]);

// Check if bound
if ($this->app->bound(Transistor::class)) {
    $service = $this->app->make(Transistor::class);
}
```

### Container Events
```php
// Container events
$this->app->resolving(DatabaseService::class, function (DatabaseService $service, Application $app) {
    $service->setConnection(DB::connection());
    $service->setLogger(Log::channel('database'));
});
```

### Rebinding
```php
// Rebinding
$this->app->rebinding(PodcastPublisher::class, function (Application $app, PodcastPublisher $newInstance) {
    $newInstance->setup();
});
```

### PSR-11 Compatibility
Laravel's service container implements PSR-11 interface:
```php
use Psr\Container\ContainerInterface;

$transistor = $container->get(Transistor::class);
$exists = $container->has(Transistor::class);
```

## Service Providers

### Introduction
Service providers are the central place of all Laravel application bootstrapping. Your own application, as well as all of Laravel's core services, are bootstrapped via service providers.

What do we mean by "bootstrapped"? In general, we mean registering things, including:
- Registering service container bindings
- Event listeners
- Middleware
- Routes

### Basic Service Provider Structure
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind services to container only
        // Don't register event listeners, routes, or other functionality here
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners, routes, middleware, etc.
        // Can access all other services here
    }
}
```

### Creating Service Providers
```bash
php artisan make:provider RiakServiceProvider
```

### Register Method
In the `register` method, you should only bind things into the service container. Never attempt to register event listeners, routes, or other functionality within the `register` method.

```php
public function register(): void
{
    $this->app->singleton(Connection::class, function (Application $app) {
        return new Connection(config('riak'));
    });
}
```

### Bindings and Singletons Properties
For simple bindings, use the `bindings` and `singletons` properties:

```php
class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ServerProvider::class => DigitalOceanServerProvider::class,
    ];

    public $singletons = [
        DowntimeNotifier::class => PingdomDowntimeNotifier::class,
    ];
}
```

### Boot Method
In the `boot` method, you can register functionality that requires access to all other services registered by Laravel:

```php
public function boot(): void
{
    View::composer('view', function () {
        // Register your view composer logic here
    });
}
```

### Boot Method Dependency Injection
You can request dependencies in the `boot` method:

```php
use Illuminate\Contracts\Routing\ResponseFactory;

public function boot(ResponseFactory $response): void
{
    $response->macro('serialized', function (mixed $value) {
        // Add a custom response macro
    });
}
```

### Registering Providers
All service providers are listed in `bootstrap/providers.php`:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\CustomServiceProvider::class,
];
```

### Deferred Providers
Deferred providers are loaded only when needed, improving performance:

```php
use Illuminate\Contracts\Support\DeferrableProvider;

class RiakServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(Connection::class, function (Application $app) {
            return new Connection($app['config']['riak']);
        });
    }

    public function provides(): array
    {
        return [Connection::class];
    }
}
```

### Service Provider Flow
```
Application Startup → Load Service Providers → Register Bindings → Boot Services
                                    ↓              ↓              ↓
                              Service Container → Event Listeners, Routes, Middleware
```

## Facades

### Introduction
Facades provide a "static" interface to classes that are available in the application's service container. Laravel ships with many facades which provide access to almost all of Laravel's features.

Laravel facades serve as "static proxies" to underlying classes in the service container, providing the benefit of a terse, expressive syntax while maintaining more testability and flexibility than traditional static methods.

### Using Facades
All of Laravel's facades are defined in the `Illuminate\Support\Facades` namespace:

```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/cache', function () {
    return Cache::get('key');
});
```

### Helper Functions
Laravel offers global "helper functions" that complement facades:

```php
// Instead of facade
use Illuminate\Support\Facades\Response;
return Response::json([...]);

// Use helper function
return response()->json([...]);  // No import needed
```

Common helper functions: `view`, `response`, `url`, `config`, `redirect`, `auth`, etc.

### When to Utilize Facades
**Benefits:**
- Terse, memorable syntax
- Don't need to remember long class names
- Easy to test
- Expressive code

**Potential Issues:**
- Class "scope creep" - easy to overuse in single class
- Pay attention to class size when using many facades
- Consider splitting large classes into smaller ones

### Facades vs. Dependency Injection
Facades can be tested just like injected classes:

```php
use Illuminate\Support\Facades\Cache;

// Test that Cache::get was called with expected argument
Cache::shouldReceive('get')->once()->with('key');
```

### How Facades Work
Facades use the `__callStatic()` magic method to defer calls to objects resolved from the service container:

```php
class Cache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cache';  // Service container binding name
    }
}
```

When calling `Cache::get()`, Laravel:
1. Resolves the `cache` binding from the service container
2. Runs the requested method (`get`) against that object

### Real-Time Facades
Treat any class as if it was a facade by prefixing with `Facades`:

```php
use Facades\App\Contracts\Publisher;

// Instead of injecting Publisher $publisher
// Access via service container directly
Publisher::publish($podcast);
```

### Common Facades
| Facade | Description |
|--------|-------------|
| `App` | The application container |
| `Auth` | Authentication services |
| `Cache` | Cache services |
| `Config` | Configuration values |
| `DB` | Database component |
| `Event` | Event dispatcher |
| `Log` | Log services |
| `Request` | HTTP request |
| `Route` | Route registration |
| `Storage` | File storage |
| `Validator` | Data validator |

### Facade Class Reference (Selected)
| Facade | Class | Binding |
|--------|-------|---------|
| App | `Illuminate\Foundation\Application` | `app` |
| Auth | `Illuminate\Auth\AuthManager` | `auth` |
| Cache | `Illuminate\Cache\CacheManager` | `cache` |
| DB | `Illuminate\Database\DatabaseManager` | `db` |
| Event | `Illuminate\Events\Dispatcher` | `events` |
| Log | `Illuminate\Log\LogManager` | `log` |
| Request | `Illuminate\Http\Request` | `request` |
| Route | `Illuminate\Routing\Router` | `router` |
| Storage | `Illuminate\Filesystem\FilesystemManager` | `filesystem` |
```

// Singleton binding (one instance for entire app)
$this->app->singleton(Service::class, function ($app) {
    return new Service();
});

// Instance binding (existing object)
$service = new Service();
$this->app->instance(Service::class, $service);

// Interface binding
$this->app->bind(Interface::class, Implementation::class);

// Contextual binding
$this->app->when(PhotoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
              return Storage::disk('local');
          });
```

### Tagging
```php
// Tag services
$this->app->tag([CpuReport::class, MemoryReport::class], 'reports');

// Resolve tagged services
$reports = $app->tagged('reports');
## Routing

### Basic Route Structure
```php
Route::method('uri', callback);
```

### Examples
```php
Route::get('/greeting', function () {
    return 'Hello World';
});

Route::post('/submit', function () {
    return 'Form submitted successfully!';
});
```

### Available Router Methods
```php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);

// Multiple methods
Route::match(['get', 'post'], '/', $callback);
Route::any('/', $callback);
```

### Route Parameters
```php
// Required parameter
Route::get('/user/{id}', function ($id) {
    return 'User '.$id;
});

// Optional parameter
Route::get('/user/{name?}', function ($name = null) {
    return $name;
});

// Regular expression constraints
Route::get('/user/{id}', function ($id) {
    // Only matches if {id} is numeric
})->where('id', '[0-9]+');

// Using helper constraints
Route::get('/user/{id}', function ($id) {
    // Only matches if {id} is numeric
})->whereNumber('id');

Route::get('/user/{name}', function ($name) {
    // Only matches if {name} contains alphabetic characters
})->whereAlpha('name');

Route::get('/category/{category}', function ($category) {
    // Only matches if {category} is one of the specified values
})->whereIn('category', ['movie', 'song', 'painting']);
```

### Named Routes
```php
// Assign name to route
Route::get('/user/profile', function () {
    // Route logic here
})->name('profile');

// Generate URL to named route
$url = route('profile');
return redirect()->route('profile');

// With parameters
Route::get('/user/{id}/profile', function ($id) {
    // Route logic here
})->name('profile');

$url = route('profile', ['id' => 1]);
```

### Route Groups
```php
// Middleware group
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second middleware
    });
    Route::get('/user/profile', function () {
        // Uses first & second middleware
    });
});

// Controller group
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders/{id}', 'show');
    Route::post('/orders', 'store');
});

// Prefix group
Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // Matches The "/admin/users" URL
    });
});

// Name prefix group
Route::name('admin.')->group(function () {
    Route::get('/users', function () {
        // Route assigned name "admin.users"
    })->name('users');
});
```

### Route Model Binding
```php
// Implicit binding
Route::get('/users/{user}', function (User $user) {
    return $user->email;
});

// Custom key
Route::get('/posts/{post:slug}', function (Post $post) {
    return $post;
});

// Override getRouteKeyName method in model
class Post extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

// Soft deleted models
Route::get('/users/{user}', function (User $user) {
    return $user->email;
})->withTrashed();
```

### Resource Controllers
```bash
php artisan make:controller PhotoController --resource
```

```php
Route::resource('photos', PhotoController::class);

// Actions handled by resource controllers:
// GET /photos (index) - photos.index
// GET /photos/create (create) - photos.create
// POST /photos (store) - photos.store
// GET /photos/{photo} (show) - photos.show
// GET /photos/{photo}/edit (edit) - photos.edit
// PUT/PATCH /photos/{photo} (update) - photos.update
// DELETE /photos/{photo} (destroy) - photos.destroy

// Partial resource routes
Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

// API resource (excludes create/edit)
Route::apiResource('photos', PhotoController::class);
```

### Rate Limiting
```php
// Define rate limiter in AppServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Attach to routes
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/api/users', function () {
        // ...
    });
});
```

### Redirect Routes
```php
// Simple redirect
Route::redirect('/here', '/there');

// With status code
Route::redirect('/here', '/there', 301);

// Permanent redirect
Route::permanentRedirect('/here', '/there');
```

### View Routes
```php
// Simple view route
Route::view('/welcome', 'welcome');
Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
```

### Form Method Spoofing
```html
<!-- For PUT, PATCH, DELETE in HTML forms -->
<form action="/example" method="POST">
    @method('PUT')
    @csrf
</form>
```

### Route Caching
```bash
php artisan route:cache    # Cache all routes
php artisan route:clear    # Clear route cache
php artisan route:list     # List all routes
```

## Middleware

### Creating Middleware
```bash
php artisan make:middleware EnsureTokenIsValid
```

### Basic Middleware Structure
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('token') !== 'my-secret-token') {
            return redirect('/home');
        }

        return $next($request);
    }
}
```

### Registering Middleware
```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'token' => \App\Http\Middleware\EnsureTokenIsValid::class,
    ]);

    $middleware->group('admin', [
        \App\Http\Middleware\EnsureTokenIsValid::class,
        \App\Http\Middleware\CheckAdmin::class,
    ]);
});
```

### Assigning Middleware
```php
// To specific route
Route::get('/admin', function () {
    //
})->middleware('auth');

// Multiple middleware
Route::get('/', function () {
    //
})->middleware([First::class, Second::class]);

// Middleware with parameters
Route::put('/post/{id}', function ($id) {
    // The current user may update the post...
})->middleware('role:editor');
```

### Before vs After Middleware
```php
// Before middleware (runs before application logic)
public function handle(Request $request, Closure $next): Response
{
    // Code here runs BEFORE the application
    return $next($request); // Immediately pass to next layer
}

// After middleware (runs after application logic)
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request); // First let application run
    // Code here runs AFTER the application
    return $response; // Then return modified response
}
```

### Middleware Groups
```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->group('admin', [
        \App\Http\Middleware\EnsureTokenIsValid::class,
        \App\Http\Middleware\CheckAdmin::class,
    ]);
});

// Use middleware group
Route::get('/admin/dashboard', function () {
    // Uses admin middleware group
})->middleware('admin');
```

### Excluding Middleware
```php
// Exclude from specific route
Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/', function () {
        // ...
    });

    Route::get('/profile', function () {
        // ...
    })->withoutMiddleware([EnsureTokenIsValid::class]);
});
```

### Terminable Middleware
```php
class TerminableMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        // Perform post-response tasks
        // For example: Log the final response, clean up resources, etc.
    }
}
```

### Common Built-in Middleware
- `auth` - Authentication
- `csrf` - CSRF protection
- `throttle` - Rate limiting
- `bindings` - Route model binding
- `can` - Authorization
- `guest` - Redirect authenticated users
- `auth.basic` - HTTP Basic authentication
- `password.confirm` - Password confirmation

## CSRF Protection

### Preventing CSRF Requests
Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token verifies that the authenticated user is the person actually making the requests to the application.

### CSRF Tokens in Forms
```html
<!-- Include in all POST, PUT, PATCH, DELETE HTML forms -->
<form method="POST" action="/profile">
    @csrf
    <!-- Equivalent to -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
</form>
```

### CSRF Tokens in AJAX Requests
```html
<!-- Add meta tag to layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

```javascript
// Use in JavaScript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Or use X-XSRF-TOKEN header (automatically handled by Axios)
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### Accessing the CSRF Token
```php
// Via request session
$token = $request->session()->token();

// Via helper function
$token = csrf_token();
```

### Excluding URIs From CSRF Protection
```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->validateCsrfTokens(except: [
        'stripe/*',
        'http://example.com/foo/bar',
    ]);
});
```

### CSRF Middleware
CSRF protection is provided by the `VerifyCsrfToken` middleware, which is included in the web middleware group by default.

## Controllers

### Creating Controllers
```bash
# Basic controller
php artisan make:controller UserController

# Resource controller
php artisan make:controller PhotoController --resource

# Invokable controller (single action)
php artisan make:controller ProvisionServer --invokable

# Controller with model
php artisan make:controller PhotoController --model=Photo --resource

# API resource controller (excludes create/edit)
php artisan make:controller PhotoController --api
```

### Basic Controller Structure
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function show(string $id): View
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}
```

### Route to Controller
```php
use App\Http\Controllers\UserController;

Route::get('/user/{id}', [UserController::class, 'show']);
```

### Single Action Controllers
```php
class ProvisionServer extends Controller
{
    public function __invoke()
    {
        // Single action logic
    }
}

// Route registration
Route::post('/server', ProvisionServer::class);
```

### Resource Controllers
```php
Route::resource('photos', PhotoController::class);

// Actions handled by resource controllers:
// GET /photos (index) - photos.index
// GET /photos/create (create) - photos.create
// POST /photos (store) - photos.store
// GET /photos/{photo} (show) - photos.show
// GET /photos/{photo}/edit (edit) - photos.edit
// PUT/PATCH /photos/{photo} (update) - photos.update
// DELETE /photos/{photo} (destroy) - photos.destroy

// Partial resource routes
Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

// API resource (excludes create/edit)
Route::apiResource('photos', PhotoController::class);
```

### Controller Middleware
```php
// In route file
Route::get('/profile', [UserController::class, 'show'])->middleware('auth');

// In controller using HasMiddleware interface
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('log', only: ['index']),
            new Middleware('subscribed', except: ['store']),
        ];
    }
}
```

### Dependency Injection in Controllers
```php
class UserController extends Controller
{
    public function __construct(
        protected UserRepository $users,
        protected NotificationService $notifications
    ) {}

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return redirect()->route('users.show', $user->id);
    }
}
```

### Method Injection
```php
public function store(Request $request): RedirectResponse
{
    $name = $request->input('name');
    return redirect('/users');
}

// With route parameters
public function update(Request $request, string $id): RedirectResponse
{
    // $request is injected, $id comes from route
    return redirect('/users');
}
```

### Form Request Validation
```bash
php artisan make:request StoreUserRequest
```

```php
class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }
}

// In controller
public function store(StoreUserRequest $request): RedirectResponse
{
    $user = User::create($request->validated());
    return redirect()->route('users.show', $user->id);
}
## Request

### Accessing the Request
```php
// Via dependency injection
public function store(Request $request): RedirectResponse
{
    $name = $request->input('name');
    return redirect('/users');
}

// In route closure
Route::get('/', function (Request $request) {
    // ...
});
```

### Request Information
```php
// Path information
$uri = $request->path();
if ($request->is('admin/*')) {
    // ...
}

// URL information
$url = $request->url();
$urlWithQuery = $request->fullUrl();
$fullUrlWithQuery = $request->fullUrlWithQuery(['type' => 'phone']);

// Request method
$method = $request->method();
if ($request->isMethod('post')) {
    // ...
}

// Host information
$request->host();
$request->httpHost();
$request->schemeAndHttpHost();
```

### Request Headers
```php
// Get header value
$value = $request->header('X-Header-Name');
$value = $request->header('X-Header-Name', 'default');

// Check header presence
if ($request->hasHeader('X-Header-Name')) {
    // ...
}

// Get bearer token
$token = $request->bearerToken();
```

### Request IP Address
```php
$ipAddress = $request->ip();
$ipAddresses = $request->ips();
```

### Input Retrieval
```php
// All input
$input = $request->all();

// Specific input
$name = $request->input('name');
$name = $request->input('name', 'Sally');
$name = $request->input('products.0.name');  // Nested array
$names = $request->input('products.*.name');  // Array of values

// Query string
$name = $request->query('name');
$name = $request->query('name', 'Helen');

// Dynamic properties
$name = $request->name;

// Specific type conversion
$perPage = $request->integer('per_page');
$archived = $request->boolean('archived');
$versions = $request->array('versions');
$birthday = $request->date('birthday');
$name = $request->string('name')->trim();

// Enum values
use App\Enums\Status;
$status = $request->enum('status', Status::class);
```

### Input Presence
```php
// Check if input exists
if ($request->has('name')) {
    // ...
}
if ($request->has(['name', 'email'])) {
    // ...
}
if ($request->hasAny(['name', 'email'])) {
    // ...
}

// Check if input is filled (not empty)
if ($request->filled('name')) {
    // ...
}
if ($request->isNotFilled('name')) {
    // ...
}
if ($request->anyFilled(['name', 'email'])) {
    // ...
}

// Check if input is missing
if ($request->missing('name')) {
    // ...
}

// Conditional handling
$request->whenHas('name', function (string $input) {
    // ...
});
$request->whenFilled('name', function (string $input) {
    // ...
});
$request->whenMissing('name', function () {
    // ...
}, function () {
    // ...
});
```

### Input Manipulation
```php
// Get portion of input
$input = $request->only(['username', 'password']);
$input = $request->except(['credit_card']);

// Merge additional input
$request->merge(['votes' => 0]);
$request->mergeIfMissing(['votes' => 0]);
```

### Old Input (for form repopulation)
```php
// Flash input to session
$request->flash();
$request->flashOnly(['username', 'email']);
$request->flashExcept('password');

// Flash and redirect
return redirect('/form')->withInput();
return redirect()->route('user.create')->withInput();
return redirect('/form')->withInput($request->except('password'));

// Retrieve old input
$username = $request->old('username');
// In Blade: {{ old('username') }}
```

### File Uploads
```php
// Get uploaded file
$file = $request->file('photo');
$file = $request->photo;

// Check if file exists
if ($request->hasFile('photo')) {
    // ...
}

// Validate successful upload
if ($request->file('photo')->isValid()) {
    // ...
}

// File info
$path = $request->photo->path();
$extension = $request->photo->extension();

// Store uploaded file
$path = $request->photo->store('images');
$path = $request->photo->store('images', 's3');
$path = $request->photo->storeAs('images', 'filename.jpg');
$path = $request->photo->storeAs('images', 'filename.jpg', 's3');
```

### Content Negotiation
```php
$contentTypes = $request->getAcceptableContentTypes();
if ($request->accepts(['text/html', 'application/json'])) {
    // ...
}
$preferred = $request->prefers(['text/html', 'application/json']);
if ($request->expectsJson()) {
    // ...
}
```

## Routing

### Basic Route Structure
```php
Route::method('uri', callback);
```

### Examples
```php
Route::get('/greeting', function () {
    return 'Hello World';
});

Route::post('/submit', function () {
    return 'Form submitted successfully!';
});
```

### Available Methods
```php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);

// Multiple methods
Route::match(['get', 'post'], '/', $callback);
Route::any('/', $callback);
```

### Route Parameters
```php
// Required parameter
Route::get('/user/{id}', function ($id) {
    return 'User '.$id;
});

// Optional parameter
Route::get('/user/{name?}', function ($name = null) {
    return $name;
});

// Regular expression constraints
Route::get('/user/{id}', function ($id) {
    // Only matches if {id} is numeric
})->where('id', '[0-9]+');

// Using helper constraints
Route::get('/user/{id}', function ($id) {
    // Only matches if {id} is numeric
})->whereNumber('id');

Route::get('/user/{name}', function ($name) {
    // Only matches if {name} contains alphabetic characters
})->whereAlpha('name');

Route::get('/category/{category}', function ($category) {
    // Only matches if {category} is one of the specified values
})->whereIn('category', ['movie', 'song', 'painting']);
```

### Named Routes
```php
// Assign name to route
Route::get('/user/profile', function () {
    // Route logic here
})->name('profile');

// Generate URL to named route
$url = route('profile');
return redirect()->route('profile');

// With parameters
Route::get('/user/{id}/profile', function ($id) {
    // Route logic here
})->name('profile');

$url = route('profile', ['id' => 1]);
```

### Route Groups
```php
// Middleware group
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second middleware
    });
    Route::get('/user/profile', function () {
        // Uses first & second middleware
    });
});

// Controller group
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders/{id}', 'show');
    Route::post('/orders', 'store');
});

// Prefix group
Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // Matches The "/admin/users" URL
    });
});

// Name prefix group
Route::name('admin.')->group(function () {
    Route::get('/users', function () {
        // Route assigned name "admin.users"
    })->name('users');
});
```

### Route Model Binding
```php
// Implicit binding
Route::get('/users/{user}', function (User $user) {
    return $user->email;
});

// Custom key
Route::get('/posts/{post:slug}', function (Post $post) {
    return $post;
});

// Override getRouteKeyName method in model
class Post extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

// Soft deleted models
Route::get('/users/{user}', function (User $user) {
    return $user->email;
})->withTrashed();
```

### Resource Controllers
```bash
php artisan make:controller PhotoController --resource
```

```php
Route::resource('photos', PhotoController::class);

// Actions handled by resource controllers:
// GET /photos (index) - photos.index
// GET /photos/create (create) - photos.create
// POST /photos (store) - photos.store
// GET /photos/{photo} (show) - photos.show
// GET /photos/{photo}/edit (edit) - photos.edit
// PUT/PATCH /photos/{photo} (update) - photos.update
// DELETE /photos/{photo} (destroy) - photos.destroy

// Partial resource routes
Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

// API resource (excludes create/edit)
Route::apiResource('photos', PhotoController::class);
```

### Rate Limiting
```php
// Define rate limiter in AppServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Attach to routes
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/api/users', function () {
        // ...
    });
});
```

## Middleware

### Create Middleware
```bash
php artisan make:middleware EnsureTokenIsValid
```

### Middleware Structure
```php
public function handle(Request $request, Closure $next): Response
{
    if ($request->input('token') !== 'my-secret-token') {
        return redirect('/home');
    }

    return $next($request);
}
```

### Registering Middleware
```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'token' => \App\Http\Middleware\EnsureTokenIsValid::class,
    ]);

    $middleware->group('admin', [
        \App\Http\Middleware\EnsureTokenIsValid::class,
        \App\Http\Middleware\CheckAdmin::class,
    ]);
});
```

### Assigning Middleware
```php
// To specific route
Route::get('/admin', function () {
    //
})->middleware('auth');

// Multiple middleware
Route::get('/', function () {
    //
})->middleware([First::class, Second::class]);

// Middleware with parameters
Route::put('/post/{id}', function ($id) {
    // The current user may update the post...
})->middleware('role:editor');
```

### Before vs After Middleware
```php
// Before middleware (runs before application logic)
public function handle(Request $request, Closure $next): Response
{
    // Code here runs BEFORE the application
    return $next($request); // Immediately pass to next layer
}

// After middleware (runs after application logic)
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request); // First let application run
    // Code here runs AFTER the application
    return $response; // Then return modified response
}
```

## CSRF Protection

### Prevention
Laravel automatically generates CSRF tokens for each active user session to verify authenticated users are making requests.

### Forms
Include CSRF token in forms:
```html
@csrf
<!-- Equivalent to -->
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
```

### AJAX/XHR Requests
```html
<!-- Add meta tag to layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Use in JavaScript -->
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Or use X-XSRF-TOKEN header (automatically handled by Axios)
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### Excluding URIs From CSRF Protection
```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->validateCsrfTokens(except: [
        'stripe/*',
        'http://example.com/foo/bar',
    ]);
});
```

## Controllers

### Create Controller
```bash
php artisan make:controller UserController
```

### Basic Controller
```php
class UserController extends Controller
{
    public function index()
    {
        // Return view or data
    }
}
```

### Single Action Controller
```bash
php artisan make:controller ProvisionServer --invokable
```

```php
class ProvisionServer extends Controller
{
    public function __invoke()
    {
        // Server provisioning logic
    }
}

// Route registration
Route::post('/server', ProvisionServer::class);
```

### Controller Middleware
```php
// In route file
Route::get('/profile', [UserController::class, 'show'])->middleware('auth');

// In controller
class UserController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('log', only: ['index']),
            new Middleware('subscribed', except: ['store']),
        ];
    }
}
```

### Dependency Injection in Controllers
```php
class UserController extends Controller
{
    public function __construct(
        protected UserRepository $users,
        protected NotificationService $notifications
    ) {}

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return redirect()->route('users.show', $user->id);
    }
}
```

## Request

### Access Request via Dependency Injection
```php
public function store(Request $request): RedirectResponse
{
    $name = $request->input('name');
    return redirect('/users');
}
```

### Request Information
```php
// Path and URL
$path = $request->path();
$url = $request->url();
$fullUrl = $request->fullUrl();

// Check path
if ($request->is('admin/*')) {
    // ...
}

// Request method
$method = $request->method();
if ($request->isMethod('post')) {
    // ...
}

// Request headers
$value = $request->header('X-Header-Name');
if ($request->hasHeader('X-Header-Name')) {
    // ...
}

// IP address
$ipAddress = $request->ip();
```

### Input Retrieval
```php
// Basic input
$name = $request->input('name');
$name = $request->input('name', 'Sally');

// All input
$input = $request->all();

// Query string
$name = $request->query('name');
$name = $request->query('name', 'Helen');

// Array input
$names = $request->input('products.*.name');

// Specific type conversion
$perPage = $request->integer('per_page');
$archived = $request->boolean('archived');
$versions = $request->array('versions');
```

### Input Validation
```php
// Check presence
if ($request->has('name')) {
    // ...
}
if ($request->has(['name', 'email'])) {
    // ...
}

// Check if filled (not empty)
if ($request->filled('name')) {
    // ...
}

// Conditional handling
$request->whenHas('name', function (string $input) {
    // ...
});
```

### File Uploads
```php
// Check for file
if ($request->hasFile('photo')) {
    // ...
}

// Validate successful upload
if ($request->file('photo')->isValid()) {
    // ...
}

// Store uploaded file
$path = $request->photo->store('images');
$path = $request->photo->storeAs('images', 'filename.jpg');
```

## Response

### Basic Responses
```php
// String response
return 'Hello World';

// Array response (auto-converted to JSON)
return [1, 2, 3];

// Eloquent model response (auto-converted to JSON)
return User::find(1);
```

### Response Objects
```php
// Custom response with status and headers
return response('Hello World', 200)
    ->header('Content-Type', 'text/plain');

// Chainable methods
return response('Custom content')
    ->header('Content-Type', 'text/html')
    ->header('X-Custom-Header', 'Custom Value');
```

### Headers
```php
// Single header
return response($content)
    ->header('Content-Type', $type)
    ->header('X-Header-One', 'Header Value');

// Multiple headers
return response($content)
    ->withHeaders([
        'Content-Type' => $type,
        'X-Header-One' => 'Header Value',
    ]);
```

### Cookies
```php
// Add cookie to response
return response('Hello World')->cookie(
    'name', 'value', 60  // name, value, duration in minutes
);

// Queue cookie
Cookie::queue('name', 'value', 60);
return response('Hello World');

// Remove cookie
return response('Hello World')->withoutCookie('name');
```

### Redirects
```php
// Basic redirect
return redirect('/home/dashboard');

// Redirect back
return back()->withInput();

// Redirect to named route
return redirect()->route('profile', ['id' => 1]);

// Redirect to controller action
return redirect()->action([UserController::class, 'index']);

// Redirect with flashed session data
return redirect('/dashboard')->with('status', 'Profile updated!');

// Redirect with input
return back()->withInput();

// External redirect
return redirect()->away('https://www.google.com');
```

### JSON Responses
```php
// JSON response
return response()->json([
    'name' => 'Abigail',
    'state' => 'CA',
]);

// JSONP response
return response()
    ->json(['name' => 'Abigail', 'state' => 'CA'])
    ->withCallback($request->input('callback'));
```

### File Responses
```php
// Download file
return response()->download($pathToFile);

// Show file in browser
return response()->file($pathToFile);

// Stream response
return response()->stream(function (): void {
    foreach (['developer', 'admin'] as $string) {
        echo $string;
        ob_flush();
        flush();
    }
});
```

## Views

### Basic View
```php
return view('greeting', ['name' => 'James']);
```

### View Location
- Stored in `resources/views` directory
- Written using Blade templating language

### View Composers
```php
// In service provider
View::composer('view', function ($view) {
    $view->with('key', 'value');
});
```

## Blade Templates

### Basic Syntax
```html
{{ $name }}                    <!-- Echo variable -->
{!! $name !!}                  <!-- Echo unescaped -->
@verbatim                      <!-- Display literal strings -->
@endverbatim
```

### Control Structures
```blade
@if (condition)
    // code
@endif

@for ($i = 0; $i < 10; $i++)
    // code
@endfor

@foreach ($items as $item)
    // code
@endforeach

@while (condition)
    // code
@endwhile

@unless (condition)
    // code
@endunless
```

### Loops
```blade
@foreach ($users as $user)
    @if ($loop->first)
        <p>This is the first iteration.</p>
    @endif

    <p>{{ $user->name }}</p>

    @if ($loop->last)
        <p>This is the last iteration.</p>
    @endif
@endforeach

// Count of iterations
@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>No users found.</p>
@endforelse
```

### Includes
```blade
@include('shared.errors')
@include('view.name', ['some' => 'data'])
```

### Template Inheritance
```blade
@extends('layout.app')

@section('title', 'Page Title')

@section('content')
    <p>Page content</p>
@endsection

@yield('content')  <!-- In layout -->
```

### Components
```blade
{{-- Create component --}}
php artisan make:component Alert

{{-- Use component --}}
<x-alert type="error" :message="$message" />

{{-- Component with slots --}}
<x-alert>
    <x-slot name="title">
        Server Error
    </x-slot>
    <strong>Whoops!</strong> Something went wrong!
</x-alert>
```

## Database: Migrations

### Create Migration
```bash
php artisan make:migration create_users_table
```

### Migration Structure
```php
public function up(): void
{
    // Apply changes
}

public function down(): void
{
    // Revert changes
}
```

### Common Schema Methods
```php
$table->id();                   // Auto-incrementing ID
$table->uuid();                 // UUID
$table->string('name');         // VARCHAR equivalent
$table->integer('votes');       // INTEGER equivalent
$table->bigInteger('votes');    // BIGINT equivalent
$table->unsignedBigInteger('user_id'); // UNSIGNED BIGINT
$table->text('description');    // TEXT equivalent
$table->boolean('active');      // BOOLEAN equivalent
$table->enum('level', ['easy', 'hard']); // ENUM equivalent
$table->date('created_at');     // DATE equivalent
$table->dateTime('created_at'); // DATETIME equivalent
$table->timestamp('added_on');  // TIMESTAMP equivalent
$table->timestamps();           // created_at and updated_at
$table->softDeletes();          // deleted_at column for soft deletes
```

### Migration Commands
```bash
php artisan migrate              # Run all outstanding migrations
php artisan migrate:rollback    # Rollback last migration batch
php artisan migrate:refresh     # Reset and re-run all migrations
php artisan migrate:status      # Show migration status
php artisan migrate:reset       # Rollback all migrations
php artisan migrate:install     # Create migrations table
```

## Eloquent ORM

### Basic Model
```php
class User extends Model
{
    protected $fillable = ['name', 'email'];
    protected $guarded = ['id'];  // All except id
    protected $hidden = ['password'];  // Hide from JSON
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
```

### Basic Operations
```php
// Create
User::create(['name' => 'John', 'email' => 'john@example.com']);
$user = new User();
$user->name = 'John';
$user->save();

// Read
User::all();
User::find(1);
User::findOrFail(1);  // Throws exception if not found
User::where('active', 1)->get();
User::first();
User::firstOrFail();  // Throws exception if not found

// Update
$user = User::find(1);
$user->name = 'Jane';
$user->save();

// Mass update
User::where('active', 1)->update(['name' => 'John']);

// Delete
User::find(1)->delete();
User::destroy(1);  // Delete by ID
User::where('active', 0)->delete();  // Delete multiple
```

### Relationships
```php
// One to One
class User extends Model
{
    public function phone()
    {
        return $this->hasOne(Phone::class);
    }
}

// One to Many
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class User extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// Many to Many
class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

// Accessing relationships
$user = User::with('posts')->get();  // Eager loading
$post = Post::find(1);
foreach ($post->comments as $comment) {
    // Process comment
}
```

### Query Scopes
```php
class User extends Model
{
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}

// Usage
$users = User::active()->get();
$admins = User:: ofType('admin')->get();
```

## Artisan Commands

### Common Commands
```bash
php artisan list                 # List all commands
php artisan tinker              # Interactive REPL
php artisan serve               # Start development server
php artisan make:controller     # Create controller
php artisan make:model          # Create model
php artisan make:migration      # Create migration
php artisan make:middleware     # Create middleware
php artisan migrate             # Run migrations
php artisan db:seed             # Seed database
php artisan route:list          # List all routes
php artisan config:cache        # Cache configuration
php artisan cache:clear         # Clear application cache
php artisan route:clear         # Clear route cache
php artisan view:clear          # Clear compiled views
php artisan optimize            # Optimize application
```

### Custom Commands
```bash
php artisan make:command SendEmails
```

```php
class SendEmails extends Command
{
    protected $signature = 'email:send {user}';
    protected $description = 'Send email to user';

    public function handle()
    {
        $user = $this->argument('user');
        // Send email to user
    }
}
```

## Validation

### Form Request Validation
```bash
php artisan make:request StoreBlogPostRequest
```

```php
class StoreBlogPostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ];
    }
}

// In controller
public function store(StoreBlogPostRequest $request)
{
    // Validation has passed...
    $validated = $request->validated();
}
```

### Inline Validation
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // Or with custom messages
    $validated = $request->validate([
        'title' => 'required|unique:posts|max:255',
    ], [
        'title.required' => 'A title is required',
    ]);
}
```

## Authentication

### Built-in Authentication
```bash
composer require laravel/ui
php artisan ui vue --auth
```

### Manual Authentication
```php
// Login
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    // Authentication passed...
    return redirect()->intended('dashboard');
}

// Logout
Auth::logout();

// Check authentication
if (Auth::check()) {
    // The user is logged in...
}

// Get authenticated user
$user = Auth::user();
$id = Auth::id();
```

## Authorization

### Gates
```php
// In service provider
Gate::define('update-post', function ($user, $post) {
    return $user->id == $post->user_id;
});

// In controller
if (Gate::allows('update-post', $post)) {
    // The user can update the post...
}
```

### Policies
```bash
php artisan make:policy PostPolicy --model=Post
```

```php
class PostPolicy
{
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}

// In controller
$this->authorize('update', $post);
```

## Caching

### Cache Operations
```php
use Illuminate\Support\Facades\Cache;

// Get value
$value = Cache::get('key');

// Get with default
$value = Cache::get('key', 'default');

// Store value
Cache::put('key', 'value', 60);  // 60 seconds

// Store if not exists
Cache::add('key', 'value', 60);

// Store permanently
Cache::forever('key', 'value');

// Remove
Cache::forget('key');
Cache::flush();  // Remove all items
```

## Sessions

### Session Operations
```php
use Illuminate\Support\Facades\Session;

// Get value
$value = session('key');
$value = session()->get('key');

// Set value
session(['key' => 'value']);
session()->put('key', 'value');

// Flash data (available for next request)
session()->flash('status', 'Task was successful!');

// Remove
session()->forget('key');
session()->flush();  // Remove all
```

## Mail

### Sending Mail
```bash
php artisan make:mail WelcomeMail
```

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

// Send mail
Mail::to('example@example.com')->send(new WelcomeMail($user));

// Send with multiple recipients
Mail::to($user)->cc($moreUsers)->bcc($evenMoreUsers)->send(new WelcomeMail);
```

## Queues

### Job Creation
```bash
php artisan make:job ProcessPodcast
```

```php
class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Process the job...
    }
}

// Dispatch job
ProcessPodcast::dispatch($podcast);
```

### Queue Commands
```bash
php artisan queue:work              # Start queue worker
php artisan queue:listen            # Restart worker on file changes
php artisan queue:restart           # Restart queue workers
php artisan queue:failed            # List failed jobs
php artisan queue:flush             # Flush failed jobs
```

## Testing

### Create Test
```bash
php artisan make:test UserTest
php artisan make:test --unit UserTest
```

### Testing Example
```php
class ExampleTest extends TestCase
{
    public function test_basic_test()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

// API Testing
public function test_get_all_projects()
{
    $response = $this->json('GET', '/api/projects', []);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => []
        ]);
}
```

### Database Testing
```php
// Refresh database for each test
use RefreshDatabase;

// Or use transactions
use DatabaseTransactions;
```

## Security

### Hashing Passwords
```php
use Illuminate\Support\Facades\Hash;

// Hash password
$password = Hash::make('plaintext');

// Verify password
if (Hash::check('plaintext', $hashedPassword)) {
    // Passwords match...
}
```

### Encryption
```php
use Illuminate\Support\Facades\Crypt;

// Encrypt value
$encrypted = Crypt::encrypt('value');

// Decrypt value
$decrypted = Crypt::decrypt($encrypted);
```

## Deployment

### Optimization Commands
```bash
php artisan config:cache        # Cache configuration
php artisan route:cache         # Cache routes
php artisan view:cache          # Cache views
php artisan event:cache         # Cache events
```

### Environment Variables
```bash
APP_NAME=Laravel
APP_ENV=production
APP_KEY=           # Generate with php artisan key:generate
APP_DEBUG=false
APP_URL=http://localhost
```