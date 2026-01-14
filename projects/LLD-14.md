# LLD-14: Laravel Full Stack Application

## Overview
This project integrates all the concepts learned in previous projects to build a complete Laravel application. You'll create a blog system that incorporates authentication, API endpoints, testing, deployment, and advanced features.

## Learning Objectives
- Integrate all Laravel concepts into a cohesive application
- Implement a complete feature set with proper architecture
- Follow Laravel best practices for application structure
- Deploy a complete Laravel application

## Prerequisites
- Completed LLD-01 through LLD-13
- Understanding of all Laravel concepts covered
- Experience with database design and relationships

## Steps to Complete the Task

### 1. Project Planning and Setup
- Plan the application structure with MVC architecture
- Set up the Laravel project with required packages (Laravel Sanctum, etc.)
- Configure development environment with proper .env settings

### 2. Database Design
- Design comprehensive database schema for blog system (users, articles, categories, tags, comments)
- Create migration files for all required tables with proper relationships and indexes
- Implement model relationships with foreign keys and constraints

### 3. Backend Implementation
- Create comprehensive models with relationships, accessors, and mutators
- Implement authentication with Laravel Breeze/Sanctum and authorization policies
- Build full-featured API endpoints with proper validation and error handling

### 4. Frontend Integration
- Create responsive Blade views with proper layouts and components
- Implement JavaScript interactions for enhanced UX (AJAX, forms, etc.)
- Add real-time features using Laravel Echo and broadcasting

### 5. Testing and Deployment
- Write comprehensive tests covering all functionality (unit, feature, API)
- Optimize for production with caching, asset compilation, and security measures
- Deploy the application with proper environment configuration and monitoring

## Implementation Details

### Step 1: Project Planning and Setup
Create a new Laravel project:
```bash
laravel new blog-system
cd blog-system
```

Install necessary packages:
```bash
composer require laravel/sanctum
composer require intervention/image
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProvider"
```

### Step 2: Database Design
Generate migration files:
```bash
php artisan make:migration create_users_table
php artisan make:migration create_categories_table
php artisan make:migration create_articles_table
php artisan make:migration create_tags_table
php artisan make:migration create_article_tag_table
php artisan make:migration create_comments_table
php artisan make:migration create_notifications_table
```

Create `database/migrations/xxxx_xx_xx_create_categories_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
```

Create `database/migrations/xxxx_xx_xx_create_articles_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('shares')->default(0);
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['published', 'published_at']);
            $table->index(['user_id']);
            $table->index(['category_id']);
            $table->index(['slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
```

Create `database/migrations/xxxx_xx_xx_create_tags_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color')->default('#007bff');
            $table->timestamps();
            
            $table->index(['slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
```

Create `database/migrations/xxxx_xx_xx_create_article_tag_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['article_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_tag');
    }
};
```

Create `database/migrations/xxxx_xx_xx_create_comments_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content');
            $table->boolean('approved')->default(false);
            $table->boolean('published')->default(true);
            $table->integer('likes')->default(0);
            $table->timestamps();
            
            $table->index(['article_id']);
            $table->index(['user_id']);
            $table->index(['parent_id']);
            $table->index(['approved', 'published']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
```

Run the migrations:
```bash
php artisan migrate
```

### Step 3: Create Models
Generate models:
```bash
php artisan make:model User -m
php artisan make:model Article -m
php artisan make:model Category -m
php artisan make:model Tag -m
php artisan make:model Comment -m
```

Create `app/Models/User.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedArticles()
    {
        return $this->belongsToMany(Article::class, 'article_likes')
                    ->withTimestamps();
    }

    public function followedCategories()
    {
        return $this->belongsToMany(Category::class, 'category_followers')
                    ->withTimestamps();
    }
}
```

Create `app/Models/Article.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'published',
        'published_at',
        'featured_image',
        'thumbnail_image',
        'user_id',
        'category_id',
        'views',
        'likes',
        'shares',
        'metadata',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'metadata' => 'array',
        'views' => 'integer',
        'likes' => 'integer',
        'shares' => 'integer',
    ];

    protected $dates = ['published_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
```

Create `app/Models/Category.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'category_followers')
                    ->withTimestamps();
    }
}
```

Create `app/Models/Tag.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag')
                    ->withTimestamps();
    }
}
```

Create `app/Models/Comment.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'article_id',
        'parent_id',
        'approved',
        'published',
        'likes',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'published' => 'boolean',
        'likes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
```

### Step 4: Create Controllers
Generate controllers:
```bash
php artisan make:controller ArticleController
php artisan make:controller CategoryController
php artisan make:controller TagController
php artisan make:controller CommentController
php artisan make:controller Api/ArticleController --api
```

Create `app/Http/Controllers/ArticleController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'tags'])
                       ->published()
                       ->orderBy('published_at', 'desc');

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%");
            });
        }

        $articles = $query->paginate(12);

        return view('articles.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = Article::with(['user', 'category', 'tags', 'comments.user'])
                         ->where('slug', $slug)
                         ->firstOrFail();

        // Increment view count
        $article->increment('views');

        return view('articles.show', compact('article'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'published' => 'boolean',
        ]);

        $article = new Article($request->only([
            'title', 'content', 'excerpt', 'category_id', 'published'
        ]));

        $article->user_id = Auth::id();
        $article->slug = Str::slug($request->title);
        $article->published_at = $request->published ? now() : null;

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('articles', 'public');
            $article->featured_image = $path;
        }

        $article->save();

        if ($request->has('tags')) {
            $article->tags()->attach($request->tags);
        }

        return redirect()->route('articles.show', $article->slug)
                         ->with('success', 'Article created successfully!');
    }

    public function edit($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        if ($article->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        if ($article->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'published' => 'boolean',
        ]);

        $article->update($request->only([
            'title', 'content', 'excerpt', 'category_id', 'published'
        ]));

        if ($request->published && !$article->published_at) {
            $article->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('articles', 'public');
            $article->featured_image = $path;
        }

        $article->save();

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('articles.show', $article->slug)
                         ->with('success', 'Article updated successfully!');
    }

    public function destroy($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        if ($article->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $article->delete();

        return redirect()->route('articles.index')
                         ->with('success', 'Article deleted successfully!');
    }
}
```

### Step 5: Create API Controllers
Create `app/Http/Controllers/Api/ArticleController.php`:
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'tags']);

        if ($request->has('published')) {
            $query->where('published', $request->published);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->search . '%');
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $articles = $query->paginate($request->get('per_page', 15));

        return ArticleResource::collection($articles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published' => 'boolean',
        ]);

        $article = new Article($request->only([
            'title', 'content', 'excerpt', 'category_id', 'published'
        ]));

        $article->user_id = Auth::id();
        $article->slug = Str::slug($request->title);
        $article->published_at = $request->published ? now() : null;

        $article->save();

        if ($request->has('tags')) {
            $article->tags()->attach($request->tags);
        }

        return new ArticleResource($article->load(['user', 'category', 'tags']));
    }

    public function show($id)
    {
        $article = Article::with(['user', 'category', 'tags', 'comments.user'])->findOrFail($id);

        return new ArticleResource($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        if ($article->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'excerpt' => 'sometimes|nullable|string|max:500',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'tags' => 'sometimes|array',
            'tags.*' => 'sometimes|exists:tags,id',
            'published' => 'sometimes|boolean',
        ]);

        $article->update($request->only([
            'title', 'content', 'excerpt', 'category_id', 'published'
        ]));

        if ($request->has('published') && $request->published && !$article->published_at) {
            $article->published_at = now();
        }

        $article->save();

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        return new ArticleResource($article->load(['user', 'category', 'tags']));
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully'], 204);
    }
}
```

### Step 6: Create Resources
Generate resources:
```bash
php artisan make:resource ArticleResource
php artisan make:resource ArticleCollection
```

Create `app/Http/Resources/ArticleResource.php`:
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'published' => $this->published,
            'published_at' => $this->published_at?->toISOString(),
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'thumbnail_image' => $this->thumbnail_image ? asset('storage/' . $this->thumbnail_image) : null,
            'views' => $this->views,
            'likes' => $this->likes,
            'shares' => $this->shares,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->color,
                ];
            }),
            'comments_count' => $this->allComments()->count(),
        ];
    }
}
```

### Step 7: Create Views
Create the main layout `resources/views/layouts/app.blade.php`:
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_TOKEN() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles -->
    <style>
        .article-card {
            transition: transform 0.2s;
        }
        .article-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    
    <script>
        // CSRF token for AJAX requests
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>
```

Create navigation partial `resources/views/layouts/navigation.blade.php`:
```html
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.*')">
                        {{ __('Articles') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('articles.create')">
                                {{ __('Write Article') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('articles.create')">
                        {{ __('Write Article') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>
```

### Step 8: Create Routes
Update `routes/web.php`:
```php
<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Article routes
    Route::resource('articles', ArticleController::class);
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

require __DIR__.'/auth.php';
```

Update `routes/api.php`:
```php
<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleController::class);
});
```

### Step 9: Create Factories
Generate factories:
```bash
php artisan make:factory ArticleFactory
php artisan make:factory CategoryFactory
php artisan make:factory TagFactory
php artisan make:factory CommentFactory
```

Create `database/factories/ArticleFactory.php`:
```php
<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'content' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->paragraph,
            'published' => $this->faker->boolean(80),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'views' => $this->faker->numberBetween(0, 10000),
            'likes' => $this->faker->numberBetween(0, 1000),
            'shares' => $this->faker->numberBetween(0, 500),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
                'published_at' => now(),
            ];
        });
    }

    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => false,
                'published_at' => null,
            ];
        });
    }
}
```

### Step 10: Create Tests
Generate tests:
```bash
php artisan make:test ArticleFeatureTest
php artisan make:test ApiArticleTest
```

Create `tests/Feature/ArticleFeatureTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ArticleFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_create_article()
    {
        $response = $this->get('/articles/create');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_article()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();

        $response = $this->post('/articles', [
            'title' => 'Test Article',
            'content' => 'This is the content of the test article',
            'excerpt' => 'Short excerpt',
            'category_id' => $category->id,
            'published' => true,
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'user_id' => $this->user->id,
            'published' => true,
        ]);
    }

    public function test_article_show_page_displays_correctly()
    {
        $article = Article::factory()->published()->create();

        $response = $this->get("/articles/{$article->slug}");

        $response->assertStatus(200)
                 ->assertSee($article->title);
    }

    public function test_article_view_count_increments()
    {
        $article = Article::factory()->published()->create(['views' => 0]);

        $initialViews = $article->views;

        $this->get("/articles/{$article->slug}");

        $article->refresh();
        
        $this->assertEquals($initialViews + 1, $article->views);
    }

    public function test_only_author_can_edit_article()
    {
        $author = $this->user;
        $otherUser = User::factory()->create();
        
        $article = Article::factory()->create(['user_id' => $author->id]);

        // Try to edit as another user
        $this->actingAs($otherUser);
        $response = $this->get("/articles/{$article->slug}/edit");
        
        $response->assertStatus(403);

        // Try to edit as the author
        $this->actingAs($author);
        $response = $this->get("/articles/{$article->slug}/edit");
        
        $response->assertStatus(200);
    }
}
```

## Testing Your Full Stack Application
1. Run all tests: `php artisan test`
2. Start the development server: `php artisan serve`
3. Register a new user account
4. Create and publish articles
5. Test API endpoints with authentication
6. Verify all features work correctly

## Conclusion
This project integrated all Laravel concepts into a complete blog application. You learned to plan and structure a full application, implement complex relationships, create both web and API interfaces, and follow Laravel best practices. This comprehensive project demonstrates your mastery of Laravel development from basic concepts to advanced features.