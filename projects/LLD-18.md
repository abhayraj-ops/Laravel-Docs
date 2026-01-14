# LLD-18: Laravel Documentation Implementation Guide

## Overview
This project bridges the gap between Laravel documentation and practical implementation. It translates concepts from the raw documentation files into actionable implementation steps for real-world applications.

## Implementation Based on Raw Documentation

### From Eloquent ORM Documentation

#### Model Generation and Conventions
- Use `php artisan make:model ModelName` for basic models
- Use `php artisan make:model ModelName --migration` to create model with migration
- Use `php artisan make:model ModelName --all` for complete scaffolding (migration, factory, seeder, controller, etc.)
- Follow Laravel's naming conventions: singular model names, plural table names
- Customize table names with `$table` property if needed
- Set custom primary keys with `$primaryKey` property
- Configure UUID keys with `$keyType = 'string'` and `$incrementing = false`

#### Eloquent Querying Techniques
- Use `Model::all()` for all records
- Use `Model::find($id)` for specific records
- Use `Model::where()` for filtered queries
- Implement chunking with `chunk()` for large datasets
- Use lazy collections with `lazy()` for memory-efficient processing
- Apply cursor pagination with `cursorPaginate()` for large datasets
- Use eager loading with `with()` to prevent N+1 queries

#### Model Relationships
- Define one-to-many with `hasMany()` and `belongsTo()`
- Set up many-to-many with `belongsToMany()`
- Use `hasOne()` and `hasOneThrough()` for one-to-one relationships
- Implement polymorphic relationships when needed
- Use relationship constraints in queries

#### Query Scopes
- Create local scopes with `scope` prefix: `scopePublished($query)`
- Implement global scopes for automatic filtering
- Use scopes to encapsulate common query logic

### From Pagination Documentation

#### Basic Pagination
- Use `Model::paginate(15)` for standard pagination
- Use `Model::simplePaginate(15)` for simple next/previous links
- Use `Model::cursorPaginate(15)` for efficient large dataset pagination
- Customize pagination links with `onEachSide(5)` method
- Append query parameters with `appends()` method
- Add hash fragments with `fragment()` method

#### Advanced Pagination
- Create manual paginators with `LengthAwarePaginator`
- Customize pagination paths with `withPath()` method
- Use cursor pagination for large datasets with indexed columns
- Implement infinite scroll with cursor pagination

### From Seeding Documentation

#### Seeder Creation
- Generate seeders with `php artisan make:seeder SeederName`
- Use `DatabaseSeeder` as the main entry point
- Call additional seeders with `$this->call([SeederClass::class])`
- Use `WithoutModelEvents` trait to prevent event dispatching during seeding

#### Factory Integration
- Create factories with `php artisan make:factory FactoryName`
- Use factories in seeders: `Model::factory()->count(50)->create()`
- Chain factory methods: `hasPosts(1)->create()`
- Use `createMany()` for related models

#### Seeding Execution
- Run all seeders: `php artisan db:seed`
- Run specific seeder: `php artisan db:seed --class=UserSeeder`
- Fresh migration with seeding: `php artisan migrate:fresh --seed`
- Force seeding in production: `php artisan db:seed --force`

### From Testing Documentation

#### Test Organization
- Place unit tests in `tests/Unit/` directory
- Place feature tests in `tests/Feature/` directory
- Generate tests with `php artisan make:test TestName`
- Use `--unit` flag for unit tests: `php artisan make:test --unit`

#### Testing Techniques
- Use `RefreshDatabase` trait for clean database state
- Mock external dependencies with `Mockery`
- Test HTTP requests with `get()`, `post()`, `put()`, `delete()` methods
- Assert responses with `assertStatus()`, `assertSee()`, `assertJson()`
- Use `assertDatabaseHas()` and `assertDatabaseMissing()` for database assertions

#### Advanced Testing
- Run tests in parallel: `php artisan test --parallel`
- Generate coverage reports: `php artisan test --coverage`
- Set minimum coverage thresholds: `php artisan test --coverage --min=80.3`
- Profile slow tests: `php artisan test --profile`
- Use `WithCachedConfig` trait for faster test execution

## Practical Implementation Examples

### Eloquent Implementation
```php
// Creating a model with all related files
php artisan make:model Article --all

// Using scopes in a model
class Article extends Model
{
    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->whereNotNull('published_at');
    }
}

// Using the scope
$publishedArticles = Article::published()->get();

// Eager loading to prevent N+1
$articles = Article::with(['user', 'comments'])->get();
```

### Pagination Implementation
```php
// In a controller
public function index()
{
    $articles = Article::paginate(15);
    return view('articles.index', compact('articles'));
}

// In a view
@foreach($articles as $article)
    <!-- Display article -->
@endforeach

{{ $articles->links() }}
```

### Seeding Implementation
```php
// Creating a seeder
php artisan make:seeder ArticleSeeder

// In the seeder
public function run()
{
    Article::factory()->count(50)->create();
}

// Calling the seeder
$this->call([ArticleSeeder::class]);
```

### Testing Implementation
```php
// Creating a test
php artisan make:test ArticleTest

// In the test file
public function test_articles_can_be_listed()
{
    Article::factory()->count(3)->create();
    
    $response = $this->get('/articles');
    
    $response->assertStatus(200)
             ->assertSee('Article Title');
}
```

## Best Practices from Documentation

### Eloquent Best Practices
- Always use mass assignment protection with `$fillable` or `$guarded`
- Use accessors and mutators for attribute formatting
- Implement model events for automatic operations
- Use soft deletes when data recovery is needed
- Apply proper indexing for frequently queried columns

### Pagination Best Practices
- Use cursor pagination for large datasets
- Implement proper ordering for consistent results
- Cache paginated results when appropriate
- Consider using simple pagination for basic navigation

### Seeding Best Practices
- Use factories for realistic test data
- Organize seeders logically by domain
- Use `WithoutModelEvents` when events shouldn't trigger
- Keep seeders idempotent (safe to run multiple times)

### Testing Best Practices
- Write both unit and feature tests
- Use descriptive test method names
- Test edge cases and error conditions
- Keep tests fast and independent
- Use factories instead of hardcoded data

This guide provides practical implementation steps based on Laravel's official documentation, bridging the gap between theoretical knowledge and real-world application development.