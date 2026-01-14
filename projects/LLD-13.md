# LLD-13: Laravel Advanced Topics

## Overview
This project explores advanced Laravel concepts including events, jobs, notifications, broadcasting, and other sophisticated features that help build enterprise-grade applications.

## Learning Objectives
- Implement event and listener systems
- Create and process queued jobs
- Send notifications via multiple channels
- Set up real-time broadcasting
- Use Laravel's advanced features

## Prerequisites
- Completed LLD-01 through LLD-12
- Understanding of Laravel fundamentals
- Experience with database operations and APIs

## Steps to Complete the Task

### 1. Event and Listener Systems
- Create custom domain events for business operations (e.g., ArticlePublished, UserRegistered)
- Implement event listeners for handling business logic asynchronously
- Use event subscribers to group related event handlers

### 2. Queued Jobs and Job Processing
- Create queued jobs for time-intensive operations (e.g., ProcessImageUpload, SendEmail)
- Set up job processing with Redis or database queue driver
- Handle job failures with retry mechanisms and dead letter queues

### 3. Notifications
- Send notifications via multiple channels (mail, database, broadcast)
- Create custom notification classes with proper formatting
- Store notifications in database for user dashboards

### 4. Broadcasting
- Set up real-time broadcasting with Pusher or Laravel Echo Server
- Create broadcast events for real-time updates (e.g., NewCommentAdded)
- Integrate broadcasting with front-end JavaScript

### 5. Advanced Features
- Use Laravel's Collections for complex data transformations and manipulations
- Implement custom Artisan commands for administrative tasks and maintenance
- Create service providers and facades for reusable functionality packages

## Implementation Details

### Step 1: Event and Listener Systems
Generate an event:
```bash
php artisan make:event ArticlePublished
```

Create `app/Events/ArticlePublished.php`:
```php
<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticlePublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
```

Generate a listener:
```bash
php artisan make:listener SendArticleNotification --event=ArticlePublished
```

Create `app/Listeners/SendArticleNotification.php`:
```php
<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use App\Notifications\ArticlePublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendArticleNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ArticlePublished $event)
    {
        // Send notification to all followers of the article's author
        $followers = $event->article->user->followers;
        
        foreach ($followers as $follower) {
            $follower->notify(new ArticlePublishedNotification($event->article));
        }
        
        // Log the event
        \Log::info("Article published: {$event->article->title}", [
            'article_id' => $event->article->id,
            'user_id' => $event->article->user_id
        ]);
    }
}
```

Register the event listener in `app/Providers/EventServiceProvider.php`:
```php
<?php

namespace App\Providers;

use App\Events\ArticlePublished;
use App\Listeners\SendArticleNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        ArticlePublished::class => [
            SendArticleNotification::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
```

Create an event subscriber:
```bash
php artisan make:listener UserEventSubscriber
```

Create `app/Listeners/UserEventSubscriber.php`:
```php
<?php

namespace App\Listeners;

class UserEventSubscriber
{
    public function handleUserLogin($event)
    {
        \Log::info("User logged in: {$event->user->email}");
    }

    public function handleUserLogout($event)
    {
        \Log::info("User logged out: {$event->user->email}");
    }

    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            [UserEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            [UserEventSubscriber::class, 'handleUserLogout']
        );
    }
}
```

Register the subscriber in `EventServiceProvider`:
```php
protected $subscribe = [
    UserEventSubscriber::class,
];
```

### Step 2: Queued Jobs and Job Processing
Generate a job:
```bash
php artisan make:job ProcessArticleImages
```

Create `app/Jobs/ProcessArticleImages.php`:
```php
<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ProcessArticleImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120; // Job timeout in seconds
    
    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle()
    {
        // Process featured image
        if ($this->article->featured_image) {
            $imagePath = storage_path('app/public/' . $this->article->featured_image);
            
            if (file_exists($imagePath)) {
                // Create thumbnail
                $thumbnailPath = str_replace('.', '_thumb.', $imagePath);
                Image::make($imagePath)
                    ->resize(300, 200)
                    ->save($thumbnailPath);
                
                // Update article with thumbnail path
                $this->article->update([
                    'thumbnail_image' => str_replace('public/', '', $thumbnailPath)
                ]);
            }
        }
        
        // Process content images
        $this->processContentImages();
        
        \Log::info("Processed images for article: {$this->article->id}");
    }

    private function processContentImages()
    {
        // Extract image URLs from content
        preg_match_all('/<img[^>]+src="([^">]+)"/', $this->article->content, $matches);
        
        foreach ($matches[1] as $imageUrl) {
            // Process each image in content
            // This is a simplified example
            \Log::info("Processing content image: {$imageUrl}");
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error("Job failed for article {$this->article->id}: " . $exception->getMessage());
        
        // Optionally notify administrators
        // \Notification::route('mail', 'admin@example.com')
        //     ->notify(new JobFailedNotification($this, $exception));
    }
}
```

Create another job for sending welcome emails:
```bash
php artisan make:job SendWelcomeEmail
```

Create `app/Jobs/SendWelcomeEmail.php`:
```php
<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Mail::send('emails.welcome', ['user' => $this->user], function (Message $message) {
            $message->to($this->user->email)
                    ->subject('Welcome to Our Platform!');
        });
        
        \Log::info("Welcome email sent to: {$this->user->email}");
    }
}
```

Update the User model to dispatch jobs:
```php
<?php

namespace App\Models;

use App\Events\UserRegistered;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            // Dispatch welcome email job
            SendWelcomeEmail::dispatch($user);
            
            // Fire user registered event
            event(new UserRegistered($user));
        });
    }
}
```

### Step 3: Notifications
Generate a notification:
```bash
php artisan make:notification ArticlePublishedNotification
```

Create `app/Notifications/ArticlePublishedNotification.php`:
```php
<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticlePublishedNotification extends Notification
{
    use Queueable;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Article Published')
                    ->greeting("Hello {$notifiable->name},")
                    ->line("A new article titled '{$this->article->title}' has been published by {$this->article->user->name}.")
                    ->action('Read Article', url("/articles/{$this->article->id}"))
                    ->line('Thank you for using our platform!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'author_name' => $this->article->user->name,
            'message' => "New article published: {$this->article->title}",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'author_name' => $this->article->user->name,
            'message' => "New article published: {$this->article->title}",
            'created_at' => now()->toISOString(),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'author_name' => $this->article->user->name,
            'message' => "New article published: {$this->article->title}",
        ];
    }
}
```

Create a database notification:
```bash
php artisan make:migration create_notifications_table
```

Create the migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
```

Run the migration:
```bash
php artisan migrate
```

### Step 4: Broadcasting
Install Laravel Echo Server for development:
```bash
npm install -g laravel-echo-server
```

Configure broadcasting in `config/broadcasting.php`:
```php
<?php

return [
    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
```

Create a broadcast event:
```bash
php artisan make:event NewCommentAdded --broadcast
```

Create `app/Events/NewCommentAdded.php`:
```php
<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('article.' . $this->comment->article_id);
    }

    public function broadcastWith()
    {
        return [
            'comment' => $this->comment,
            'user' => $this->comment->user,
        ];
    }
}
```

Update the Comment model to broadcast events:
```bash
php artisan make:model Comment -m
```

Create `app/Models/Comment.php`:
```php
<?php

namespace App\Models;

use App\Events\NewCommentAdded;
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
    ];

    protected $dispatchesEvents = [
        'created' => NewCommentAdded::class,
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

### Step 5: Advanced Collections Usage
Create a service that demonstrates advanced collection usage:
```bash
php artisan make:service AnalyticsService
```

Actually, let's create it manually in `app/Services/AnalyticsService.php`:
```php
<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function getUserEngagementStats(): Collection
    {
        $users = User::withCount(['articles as published_articles_count'])
            ->withSum('articles as total_views', 'views')
            ->get();

        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'published_articles' => $user->published_articles_count,
                'total_views' => $user->total_views,
                'average_views_per_article' => $user->published_articles_count > 0 
                    ? round($user->total_views / $user->published_articles_count, 2) 
                    : 0,
                'engagement_score' => $this->calculateEngagementScore($user)
            ];
        })
        ->sortByDesc('engagement_score')
        ->values();
    }

    public function getTopPerformingArticles(int $limit = 10): Collection
    {
        return Article::with('user')
            ->select(['*', \DB::raw('views * shares * likes as performance_score')])
            ->orderBy('performance_score', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'author' => $article->user->name,
                    'views' => $article->views,
                    'shares' => $article->shares,
                    'likes' => $article->likes,
                    'performance_score' => $article->performance_score,
                    'published_date' => $article->created_at->format('Y-m-d'),
                ];
            });
    }

    public function getMonthlyActivityReport(): Collection
    {
        $articles = Article::select([
            '*',
            \DB::raw('YEAR(created_at) as year'),
            \DB::raw('MONTH(created_at) as month')
        ])->get();

        return $articles
            ->groupBy(['year', 'month'])
            ->map(function ($monthlyArticles, $year) {
                return $monthlyArticles->map(function ($articles, $month) {
                    return [
                        'month' => $month,
                        'count' => $articles->count(),
                        'total_views' => $articles->sum('views'),
                        'average_views' => $articles->avg('views'),
                    ];
                });
            });
    }

    private function calculateEngagementScore(User $user): float
    {
        $score = 0;
        
        // Weighted scoring
        $score += $user->published_articles_count * 10; // 10 points per article
        $score += $user->total_views * 0.5; // 0.5 points per view
        $score += $user->articles()->where('published', true)->count() * 5; // Bonus for published articles
        
        return round($score, 2);
    }
}
```

### Step 6: Custom Artisan Commands
Generate a command:
```bash
php artisan make:command GenerateSitemap
```

Create `app/Console/Commands/GenerateSitemap.php`:
```php
<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--path=public/sitemap.xml : Path to save sitemap}';
    protected $description = 'Generate a sitemap for SEO';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $articles = Article::where('published', true)->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($articles as $article) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . URL::to("/articles/{$article->id}") . "</loc>\n";
            $sitemap .= "    <lastmod>{$article->updated_at->toDateString()}</lastmod>\n";
            $sitemap .= "    <changefreq>weekly</changefreq>\n";
            $sitemap .= "    <priority>0.8</priority>\n";
            $sitemap .= "  </url>\n";
        }

        $sitemap .= '</urlset>';

        $path = $this->option('path');
        File::put(public_path($path), $sitemap);

        $this->info("Sitemap generated successfully at {$path}");
    }
}
```

### Step 7: Service Providers
Create a custom service provider:
```bash
php artisan make:provider AnalyticsServiceProvider
```

Create `app/Providers/AnalyticsServiceProvider.php`:
```php
<?php

namespace App\Providers;

use App\Services\AnalyticsService;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public $singletons = [
        AnalyticsService::class => AnalyticsService::class,
    ];

    public function register()
    {
        $this->app->singleton(AnalyticsService::class, function ($app) {
            return new AnalyticsService();
        });
    }

    public function boot()
    {
        // Perform post-registration booting of services
        $this->publishes([
            __DIR__.'/../config/analytics.php' => config_path('analytics.php'),
        ], 'config');
    }
}
```

Register the service provider in `config/app.php`:
```php
'providers' => [
    // ... other providers
    App\Providers\AnalyticsServiceProvider::class,
],
```

### Step 8: Custom Facades
Create a facade class:
```bash
php artisan make:facade Analytics
```

Actually, let's create it manually in `app/Facades/Analytics.php`:
```php
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Analytics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'analytics';
    }
}
```

Bind the facade in a service provider. Update `AnalyticsServiceProvider`:
```php
<?php

namespace App\Providers;

use App\Services\AnalyticsService;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('analytics', function ($app) {
            return new AnalyticsService();
        });
    }

    public function boot()
    {
        //
    }
}
```

### Step 9: Queue Workers and Supervisors
Configure queue workers in `config/queue.php`:
```php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],
],
```

Create a queue worker supervisor configuration in `.env`:
```
QUEUE_CONNECTION=redis
REDIS_QUEUE=default
WORKER_PROCESSES=3
```

Create a deployment script for queue workers:
```bash
#!/bin/bash

# Start queue workers
php artisan queue:work redis --daemon --sleep=3 --tries=3 --max-jobs=1000 &

# Start failed job retry worker
php artisan queue:retry --daemon &

echo "Queue workers started"
```

### Step 10: Advanced Testing
Create a test for the event system:
```bash
php artisan make:test EventSystemTest
```

Create `tests/Feature/EventSystemTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Events\ArticlePublished;
use App\Listeners\SendArticleNotification;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EventSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_published_event_is_dispatched()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id, 'published' => true]);

        Event::assertDispatched(ArticlePublished::class, function ($event) use ($article) {
            return $event->article->is($article);
        });
    }

    public function test_send_article_notification_listener_works()
    {
        Notification::fake();
        
        $author = User::factory()->create();
        $follower = User::factory()->create();
        
        // Create relationship (assuming you have a followers table)
        // $author->followers()->attach($follower->id);
        
        $article = Article::factory()->create(['user_id' => $author->id]);
        
        $listener = new SendArticleNotification();
        $event = new ArticlePublished($article);
        
        $listener->handle($event);
        
        Notification::assertSentTo(
            $follower,
            \App\Notifications\ArticlePublishedNotification::class,
            function ($notification) use ($article) {
                return $notification->article->is($article);
            }
        );
    }

    public function test_job_is_dispatched_when_article_is_created()
    {
        $this->expectsJobs(\App\Jobs\ProcessArticleImages::class);
        
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        
        \App\Jobs\ProcessArticleImages::dispatch($article);
    }
}
```

## Testing Your Advanced Implementation
1. Run the event tests: `php artisan test --filter=EventSystemTest`
2. Test queued jobs by starting a queue worker: `php artisan queue:work`
3. Verify notifications are sent correctly
4. Test custom commands: `php artisan sitemap:generate`
5. Verify broadcasting works with your front-end

## Conclusion
This project taught you advanced Laravel concepts including events, jobs, notifications, broadcasting, and other sophisticated features. These advanced topics help you build more responsive, scalable, and maintainable applications. Mastering these concepts allows you to handle complex business logic and create enterprise-grade applications.