# LLD-15: Laravel Utilities and Built-in Features

## Overview
This project explores Laravel's built-in utilities and features that enhance productivity and application functionality. You'll learn about Laravel's helper functions, collection methods, file storage, mail system, and other useful features.

## Learning Objectives
- Use Laravel's helper functions effectively
- Leverage collection methods for data manipulation
- Implement file storage and management
- Set up and use Laravel's mail system
- Explore other built-in Laravel features

## Prerequisites
- Completed LLD-01 through LLD-14
- Understanding of Laravel architecture
- Experience with controllers and models

## Steps to Complete the Task

### 1. Laravel Helper Functions
- Explore and use Laravel's global helper functions (collect, data_get, optional, etc.)
- Implement helper functions in controllers and services for common operations
- Compare helper functions with native PHP equivalents for efficiency

### 2. Collection Methods
- Master Laravel's collection methods (map, filter, reduce, groupBy, etc.)
- Chain collection operations efficiently for complex data processing
- Use collections for complex data transformations and manipulations

### 3. File Storage System
- Configure different file storage disks (local, public, S3)
- Implement secure file upload and management with validation
- Process and manipulate uploaded files with proper security measures

### 4. Mail System
- Configure mail settings for different environments and transports
- Create and send templated emails with proper error handling
- Implement queued mail delivery for performance optimization

### 5. Other Built-in Features
- Use Laravel's Artisan commands for automation and maintenance tasks
- Implement caching strategies with different drivers (Redis, Memcached, etc.)
- Explore Laravel's file system utilities and other advanced features

## Implementation Details

### Step 1: Laravel Helper Functions
Create a helper service to demonstrate various helper functions:
```bash
php artisan make:service HelperDemoService
```

Actually, let's create it manually in `app/Services/HelperDemoService.php`:
```php
<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HelperDemoService
{
    public function demonstrateArrayHelpers()
    {
        $array = [
            ['id' => 1, 'name' => 'John', 'department' => 'IT'],
            ['id' => 2, 'name' => 'Jane', 'department' => 'HR'],
            ['id' => 3, 'name' => 'Bob', 'department' => 'IT'],
        ];

        // Arr::pluck - Extract a single column
        $names = Arr::pluck($array, 'name');
        // Result: ['John', 'Jane', 'Bob']

        // Arr::where - Filter array by callback
        $itEmployees = Arr::where($array, function ($employee) {
            return $employee['department'] === 'IT';
        });

        // Arr::get - Get item from nested array with dot notation
        $firstEmployeeName = Arr::get($array, '0.name', 'Default Name');

        // Arr::except - Remove keys from array
        $withoutIds = Arr::except($array, ['id']);

        return [
            'names' => $names,
            'it_employees' => $itEmployees,
            'first_employee_name' => $firstEmployeeName,
            'without_ids' => $withoutIds,
        ];
    }

    public function demonstrateStringHelpers()
    {
        // Str::studly - Convert to StudlyCase
        $studly = Str::studly('laravel_framework');
        // Result: LaravelFramework

        // Str::camel - Convert to camelCase
        $camel = Str::camel('laravel_framework');
        // Result: laravelFramework

        // Str::kebab - Convert to kebab-case
        $kebab = Str::kebab('Laravel Framework');
        // Result: laravel-framework

        // Str::slug - Create URL-friendly slug
        $slug = Str::slug('Laravel Framework', '-');
        // Result: laravel-framework

        // Str::words - Limit text to specified number of words
        $shortened = Str::words('This is a long text that needs to be shortened', 4, '...');
        // Result: This is a long...

        // Str::mask - Mask part of string
        $masked = Str::mask('john@example.com', '*', 3, 5);
        // Result: joh****@example.com

        return [
            'studly' => $studly,
            'camel' => $camel,
            'kebab' => $kebab,
            'slug' => $slug,
            'shortened' => $shortened,
            'masked' => $masked,
        ];
    }

    public function demonstrateGeneralHelpers()
    {
        // collect() - Create collection from array
        $collection = collect([1, 2, 3, 4, 5]);

        // data_get() - Get item from complex data structure
        $data = ['users' => [['name' => 'John'], ['name' => 'Jane']]];
        $firstUserName = data_get($data, 'users.0.name');
        // Result: John

        // tap() - Call callback on value and return value
        $processed = tap(collect([1, 2, 3]), function ($collection) {
            // Perform some operations
            $collection->push(4);
        })->sum();
        // Result: 10

        // optional() - Safely access properties/methods of nullable objects
        $user = null;
        $name = optional($user)->name ?? 'Guest';
        // Result: Guest

        // retry() - Retry function execution with exponential backoff
        $result = retry(3, function () {
            // Attempt to make HTTP request
            $response = Http::get('https://api.example.com/data');
            if ($response->successful()) {
                return $response->json();
            }
            throw new \Exception('API request failed');
        }, 100); // 100ms delay between retries

        return [
            'collection_count' => $collection->count(),
            'first_user_name' => $firstUserName,
            'processed_sum' => $processed,
            'optional_name' => $name,
            'retry_result' => $result ?? 'Retries exhausted',
        ];
    }
}
```

Create a controller to demonstrate helpers:
```bash
php artisan make:controller HelperDemoController
```

Create `app/Http/Controllers/HelperDemoController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Services\HelperDemoService;
use Illuminate\Http\Request;

class HelperDemoController extends Controller
{
    protected $helperService;

    public function __construct(HelperDemoService $helperService)
    {
        $this->helperService = $helperService;
    }

    public function index()
    {
        $arrayResults = $this->helperService->demonstrateArrayHelpers();
        $stringResults = $this->helperService->demonstrateStringHelpers();
        $generalResults = $this->helperService->demonstrateGeneralHelpers();

        return view('helpers.demo', compact(
            'arrayResults',
            'stringResults',
            'generalResults'
        ));
    }

    public function formatDate(Request $request)
    {
        $date = $request->input('date', 'now');
        $format = $request->input('format', 'Y-m-d H:i:s');
        
        // Using Carbon helper
        $formattedDate = now()->parse($date)->format($format);
        
        return response()->json(['formatted_date' => $formattedDate]);
    }

    public function validateInput(Request $request)
    {
        // Using validate helper in controller
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
        ]);

        return response()->json(['message' => 'Validation passed', 'data' => $validated]);
    }
}
```

### Step 2: Collection Methods
Create a collection demo service:
```bash
php artisan make:service CollectionDemoService
```

Create `app/Services/CollectionDemoService.php`:
```php
<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CollectionDemoService
{
    public function demonstrateBasicMethods()
    {
        $numbers = collect([1, 2, 3, 4, 5]);

        return [
            'count' => $numbers->count(),
            'sum' => $numbers->sum(),
            'avg' => $numbers->avg(),
            'min' => $numbers->min(),
            'max' => $numbers->max(),
            'first' => $numbers->first(),
            'last' => $numbers->last(),
        ];
    }

    public function demonstrateFiltering()
    {
        $users = collect([
            ['name' => 'John', 'age' => 25, 'active' => true],
            ['name' => 'Jane', 'age' => 30, 'active' => false],
            ['name' => 'Bob', 'age' => 35, 'active' => true],
            ['name' => 'Alice', 'age' => 28, 'active' => true],
        ]);

        return [
            // Filter active users
            'active_users' => $users->where('active', true)->toArray(),
            
            // Filter users over 30
            'over_30' => $users->filter(function ($user) {
                return $user['age'] > 30;
            })->toArray(),
            
            // Reject users under 30
            'not_under_30' => $users->reject(function ($user) {
                return $user['age'] < 30;
            })->toArray(),
            
            // Unique by age
            'unique_by_age' => $users->unique('age')->toArray(),
        ];
    }

    public function demonstrateTransformations()
    {
        $products = collect([
            ['name' => 'Laptop', 'price' => 1000, 'category' => 'Electronics'],
            ['name' => 'Book', 'price' => 20, 'category' => 'Education'],
            ['name' => 'Phone', 'price' => 800, 'category' => 'Electronics'],
            ['name' => 'Desk', 'price' => 300, 'category' => 'Furniture'],
        ]);

        return [
            // Map to get just names
            'product_names' => $products->map->name->toArray(),
            
            // Map with transformation
            'with_tax' => $products->map(function ($product) {
                $product['price_with_tax'] = $product['price'] * 1.1;
                return $product;
            })->toArray(),
            
            // Pluck specific values
            'prices' => $products->pluck('price', 'name')->toArray(),
            
            // Group by category
            'by_category' => $products->groupBy('category')->toArray(),
        ];
    }

    public function demonstrateAdvancedOperations()
    {
        $sales = collect([
            ['product' => 'Laptop', 'amount' => 1000, 'region' => 'North'],
            ['product' => 'Phone', 'amount' => 800, 'region' => 'South'],
            ['product' => 'Laptop', 'amount' => 1200, 'region' => 'North'],
            ['product' => 'Tablet', 'amount' => 500, 'region' => 'East'],
        ]);

        return [
            // Reduce to total sales
            'total_sales' => $sales->reduce(function ($carry, $sale) {
                return $carry + $sale['amount'];
            }, 0),
            
            // Group and sum by region
            'sales_by_region' => $sales->groupBy('region')
                                      ->map->sum('amount')
                                      ->toArray(),
            
            // Flatten nested collections
            'nested' => collect([
                ['team' => 'A', 'members' => ['John', 'Jane']],
                ['team' => 'B', 'members' => ['Bob', 'Alice']],
            ])->pluck('members')->flatten()->toArray(),
            
            // Chunk data
            'chunks' => collect([1, 2, 3, 4, 5, 6])->chunk(2)->toArray(),
        ];
    }

    public function demonstrateCollectionChaining()
    {
        // Complex chaining example
        $orders = collect([
            ['id' => 1, 'customer' => 'John', 'amount' => 150, 'status' => 'completed'],
            ['id' => 2, 'customer' => 'Jane', 'amount' => 75, 'status' => 'pending'],
            ['id' => 3, 'customer' => 'Bob', 'amount' => 200, 'status' => 'completed'],
            ['id' => 4, 'customer' => 'Alice', 'amount' => 50, 'status' => 'completed'],
            ['id' => 5, 'customer' => 'Charlie', 'amount' => 300, 'status' => 'cancelled'],
        ]);

        // Chain multiple operations
        $topCustomers = $orders
            ->where('status', 'completed')  // Only completed orders
            ->filter(function ($order) {
                return $order['amount'] >= 100;  // Only orders $100+
            })
            ->sortByDesc('amount')  // Sort by amount descending
            ->take(3)  // Take top 3
            ->pluck('customer')  // Get customer names
            ->values();  // Re-index array

        return [
            'top_customers' => $topCustomers->toArray(),
            'processing_steps' => [
                'original_count' => $orders->count(),
                'completed_orders' => $orders->where('status', 'completed')->count(),
                'high_value_orders' => $orders->where('status', 'completed')
                                              ->filter(fn($o) => $o['amount'] >= 100)
                                              ->count(),
                'final_result_count' => $topCustomers->count(),
            ]
        ];
    }
}
```

### Step 3: File Storage System
Configure file storage in `config/filesystems.php`:
```php
<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
```

Create a file upload controller:
```bash
php artisan make:controller FileUploadController
```

Create `app/Http/Controllers/FileUploadController.php`:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('files.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'disk' => 'sometimes|in:local,public,s3',
        ]);

        $file = $request->file('file');
        $disk = $request->input('disk', 'public');

        // Generate unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs('uploads', $filename, $disk);

        // Get file info
        $fileInfo = [
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'disk' => $disk,
            'url' => $disk === 'public' ? Storage::url($path) : null,
        ];

        return response()->json([
            'message' => 'File uploaded successfully',
            'file_info' => $fileInfo,
        ]);
    }

    public function listFiles(Request $request)
    {
        $disk = $request->input('disk', 'public');
        $directory = $request->input('directory', 'uploads');
        
        $files = Storage::disk($disk)->files($directory);
        
        $fileDetails = collect($files)->map(function ($file) use ($disk) {
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => Storage::disk($disk)->size($file),
                'last_modified' => Storage::disk($disk)->lastModified($file),
                'url' => $disk === 'public' ? Storage::disk($disk)->url($file) : null,
            ];
        });

        return response()->json([
            'files' => $fileDetails,
            'disk' => $disk,
            'directory' => $directory,
        ]);
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'disk' => 'sometimes|in:local,public,s3',
        ]);

        $disk = $request->input('disk', 'public');
        $path = $request->input('path');

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
            
            return response()->json([
                'message' => 'File deleted successfully',
                'path' => $path,
            ]);
        }

        return response()->json([
            'message' => 'File not found',
            'path' => $path,
        ], 404);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/public/uploads/' . $filename);
        
        if (file_exists($path)) {
            return response()->download($path);
        }

        abort(404, 'File not found');
    }
}
```

### Step 4: Mail System
Configure mail settings in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Create a mailable:
```bash
php artisan make:mail Newsletter
```

Create `app/Mail/Newsletter.php`:
```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $unsubscribeLink;

    public function __construct($subject, $content, $unsubscribeLink = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->unsubscribeLink = $unsubscribeLink;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.newsletter')
                    ->with([
                        'content' => $this->content,
                        'unsubscribeLink' => $this->unsubscribeLink,
                    ]);
    }
}
```

Create email template `resources/views/emails/newsletter.blade.php`:
```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background-color: #f4f4f4; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>{{ $subject }}</h2>
    </div>
    
    <div class="content">
        {!! $content !!}
    </div>
    
    <div class="footer">
        @if($unsubscribeLink)
            <p><a href="{{ $unsubscribeLink }}">Unsubscribe</a> from this newsletter</p>
        @endif
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
```

Create a mail controller:
```bash
php artisan make:controller MailController
```

Create `app/Http/Controllers/MailController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Mail\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function showComposeForm()
    {
        return view('mail.compose');
    }

    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        $newsletter = new Newsletter(
            $request->subject,
            $request->content,
            url('/unsubscribe/' . uniqid())
        );

        // Send to each recipient
        foreach ($request->recipients as $recipient) {
            Mail::to($recipient)->queue($newsletter); // Queue for better performance
        }

        return response()->json([
            'message' => 'Newsletter queued for sending',
            'recipients_count' => count($request->recipients),
        ]);
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Mail::raw($request->message, function ($message) use ($request) {
            $message->to($request->email)
                    ->subject($request->subject);
        });

        return response()->json(['message' => 'Test email sent successfully']);
    }
}
```

### Step 5: Artisan Commands
Create a custom Artisan command:
```bash
php artisan make:command GenerateReport
```

Create `app/Console/Commands/GenerateReport.php`:
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateReport extends Command
{
    protected $signature = 'report:generate {type=summary : Type of report to generate}';
    protected $description = 'Generate various reports for the application';

    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("Generating {$type} report...");
        
        switch ($type) {
            case 'summary':
                $this->generateSummaryReport();
                break;
            case 'users':
                $this->generateUserReport();
                break;
            case 'articles':
                $this->generateArticleReport();
                break;
            default:
                $this->error("Unknown report type: {$type}");
                return 1;
        }
        
        $this->info("Report generated successfully!");
        
        return 0;
    }

    private function generateSummaryReport()
    {
        $this->line('=== Application Summary Report ===');
        
        $userCount = DB::table('users')->count();
        $articleCount = DB::table('articles')->count();
        $commentCount = DB::table('comments')->count();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Users', $userCount],
                ['Total Articles', $articleCount],
                ['Total Comments', $commentCount],
            ]
        );
        
        // Save to file
        $reportData = [
            'generated_at' => now()->toISOString(),
            'metrics' => [
                'users' => $userCount,
                'articles' => $articleCount,
                'comments' => $commentCount,
            ]
        ];
        
        $filename = storage_path("app/reports/summary_" . now()->format('Y-m-d_H-i-s') . ".json");
        \Illuminate\Support\Facades\Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        $this->info("Report saved to: {$filename}");
    }

    private function generateUserReport()
    {
        $this->line('=== User Report ===');
        
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $this->table(
            ['ID', 'Name', 'Email', 'Created At'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at,
                ];
            })->toArray()
        );
    }

    private function generateArticleReport()
    {
        $this->line('=== Article Report ===');
        
        $articles = DB::table('articles')
            ->join('users', 'articles.user_id', '=', 'users.id')
            ->select('articles.id', 'articles.title', 'users.name as author', 'articles.views', 'articles.created_at')
            ->orderBy('articles.views', 'desc')
            ->limit(10)
            ->get();
        
        $this->table(
            ['ID', 'Title', 'Author', 'Views', 'Created'],
            $articles->map(function ($article) {
                return [
                    $article->id,
                    \Illuminate\Support\Str::limit($article->title, 30),
                    $article->author,
                    $article->views,
                    $article->created_at,
                ];
            })->toArray()
        );
    }
}
```

### Step 6: Caching Examples
Create a caching service:
```bash
php artisan make:service CacheDemoService
```

Create `app/Services/CacheDemoService.php`:
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CacheDemoService
{
    public function getWeatherData($city)
    {
        return Cache::remember("weather_{$city}", 300, function () use ($city) {
            // Simulate API call
            $response = Http::get("https://api.weather.com/v1/current?city={$city}");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return ['error' => 'Unable to fetch weather data'];
        });
    }

    public function getUserActivity($userId)
    {
        return Cache::remember("user_activity_{$userId}", 600, function () use ($userId) {
            // Expensive database query simulation
            return \DB::table('activities')
                      ->where('user_id', $userId)
                      ->orderBy('created_at', 'desc')
                      ->limit(10)
                      ->get();
        });
    }

    public function getPopularArticles()
    {
        return Cache::remember('popular_articles', 1800, function () {
            return \App\Models\Article::published()
                                     ->orderBy('views', 'desc')
                                     ->limit(5)
                                     ->get();
        });
    }

    public function incrementPageView($pageSlug)
    {
        $key = "page_views_{$pageSlug}";
        return Cache::increment($key);
    }

    public function getPageViews($pageSlug)
    {
        $key = "page_views_{$pageSlug}";
        return Cache::get($key, 0);
    }

    public function clearUserCache($userId)
    {
        Cache::forget("user_activity_{$userId}");
        Cache::forget("user_profile_{$userId}");
    }
}
```

### Step 7: Create Routes for Demos
Add routes to `routes/web.php`:
```php
<?php

use App\Http\Controllers\HelperDemoController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

// Helper demos
Route::get('/helpers', [HelperDemoController::class, 'index'])->name('helpers.demo');
Route::post('/helpers/format-date', [HelperDemoController::class, 'formatDate']);
Route::post('/helpers/validate', [HelperDemoController::class, 'validateInput']);

// File upload
Route::get('/files/upload', [FileUploadController::class, 'showUploadForm'])->name('files.upload.form');
Route::post('/files/upload', [FileUploadController::class, 'upload'])->name('files.upload');
Route::get('/files/list', [FileUploadController::class, 'listFiles'])->name('files.list');
Route::delete('/files/delete', [FileUploadController::class, 'deleteFile'])->name('files.delete');
Route::get('/files/download/{filename}', [FileUploadController::class, 'downloadFile'])->name('files.download');

// Mail
Route::get('/mail/compose', [MailController::class, 'showComposeForm'])->name('mail.compose.form');
Route::post('/mail/send-newsletter', [MailController::class, 'sendNewsletter'])->name('mail.send.newsletter');
Route::post('/mail/test', [MailController::class, 'sendTestMail'])->name('mail.test');

// Additional utility routes can be added here
```

### Step 8: Create Views for Demos
Create `resources/views/helpers/demo.blade.php`:
```html
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Laravel Helper Functions Demo</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Array Helpers -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Array Helpers</h2>
            <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ var_export($arrayResults, true) }}</pre>
        </div>
        
        <!-- String Helpers -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">String Helpers</h2>
            <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ var_export($stringResults, true) }}</pre>
        </div>
        
        <!-- General Helpers -->
        <div class="bg-white p-6 rounded-lg shadow md:col-span-2">
            <h2 class="text-xl font-semibold mb-4">General Helpers</h2>
            <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ var_export($generalResults, true) }}</pre>
        </div>
    </div>
</div>
@endsection
```

## Testing Your Implementation
1. Run the custom Artisan command: `php artisan report:generate`
2. Visit the helper demo page to see helper functions in action
3. Test file upload functionality
4. Try sending test emails
5. Verify caching works properly

## Conclusion
This project explored Laravel's built-in utilities and features that enhance productivity. You learned to use helper functions, collection methods, file storage, mail system, and other useful features. These utilities make Laravel development more efficient and help you write cleaner, more maintainable code.