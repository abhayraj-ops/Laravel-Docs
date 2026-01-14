# LLD-16: Laravel Mastery - Complete Application Architecture

## Overview
This final project synthesizes all the concepts learned in the previous projects to create a comprehensive understanding of Laravel application architecture. You'll learn how to architect complex applications, implement best practices, and follow Laravel conventions for scalable development.

## Learning Objectives
- Architect complex Laravel applications
- Implement SOLID principles in Laravel
- Follow Laravel best practices
- Create maintainable and scalable code
- Understand advanced Laravel patterns

## Prerequisites
- Completed LLD-01 through LLD-15
- Deep understanding of all Laravel concepts
- Experience with application architecture

## Steps to Complete the Task

### 1. Application Architecture Planning
- Plan the layered application structure (Domain, Application, Infrastructure, Presentation)
- Design the domain layer with entities and value objects
- Create service layer architecture with application services

### 2. Domain-Driven Design Implementation
- Implement domain models with business logic encapsulation
- Create value objects for data integrity (Email, Uuid, etc.)
- Define domain services for complex business operations

### 3. Repository Pattern
- Create repository interfaces for data access abstraction
- Implement concrete repository classes with Eloquent models
- Use dependency injection to wire repositories to services

### 4. Advanced Patterns and Practices
- Implement custom middleware for cross-cutting concerns
- Create service providers for dependency injection configuration
- Use Laravel's advanced features (events, queues, caching) effectively

### 5. Performance Optimization
- Implement caching strategies with decorators and cache invalidation
- Optimize database queries with eager loading and indexing
- Use queue systems effectively for background processing

## Implementation Details

### Step 1: Application Architecture Planning
Create the domain layer structure:
```
app/
├── Domains/
│   ├── User/
│   │   ├── Models/
│   │   ├── ValueObjects/
│   │   ├── Services/
│   │   └── Repositories/
│   ├── Article/
│   │   ├── Models/
│   │   ├── ValueObjects/
│   │   ├── Services/
│   │   └── Repositories/
│   └── Shared/
│       ├── ValueObjects/
│       ├── Exceptions/
│       └── Interfaces/
├── Application/
│   ├── Services/
│   ├── DTOs/
│   └── Interfaces/
├── Infrastructure/
│   ├── Repositories/
│   ├── Services/
│   └── Adapters/
└── Presentation/
    ├── Controllers/
    ├── Requests/
    ├── Resources/
    └── Middleware/
```

Create the domain model for User:
```bash
mkdir -p app/Domains/User/Models
mkdir -p app/Domains/User/ValueObjects
mkdir -p app/Domains/User/Services
mkdir -p app/Domains/User/Repositories
```

Create `app/Domains/User/Models/UserDomainModel.php`:
```php
<?php

namespace App\Domains\User\Models;

use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Uuid;
use App\Domains\User\ValueObjects\Username;
use DateTimeImmutable;

class UserDomainModel
{
    private Uuid $id;
    private Username $username;
    private Email $email;
    private string $password;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private ?DateTimeImmutable $deletedAt;

    public function __construct(
        Uuid $id,
        Username $username,
        Email $email,
        string $password,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ?DateTimeImmutable $deletedAt = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function updateEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        $clone->updatedAt = new DateTimeImmutable();
        return $clone;
    }

    public function updateUsername(Username $username): self
    {
        $clone = clone $this;
        $clone->username = $username;
        $clone->updatedAt = new DateTimeImmutable();
        return $clone;
    }
}
```

Create shared value objects:
```bash
mkdir -p app/Domains/Shared/ValueObjects
mkdir -p app/Domains/Shared/Exceptions
mkdir -p app/Domains/Shared/Interfaces
```

Create `app/Domains/Shared/ValueObjects/Uuid.php`:
```php
<?php

namespace App\Domains\Shared\ValueObjects;

use InvalidArgumentException;

class Uuid
{
    private string $value;

    public function __construct(string $value)
    {
        if (!$this->isValidUuid($value)) {
            throw new InvalidArgumentException('Invalid UUID format');
        }
        
        $this->value = $value;
    }

    private function isValidUuid(string $uuid): bool
    {
        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        ) === 1;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

Create `app/Domains/Shared/ValueObjects/Email.php`:
```php
<?php

namespace App\Domains\Shared\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        
        $this->value = strtolower(trim($value));
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

Create `app/Domains/User/ValueObjects/Username.php`:
```php
<?php

namespace App\Domains\User\ValueObjects;

use InvalidArgumentException;

class Username
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 3 || strlen($value) > 50) {
            throw new InvalidArgumentException('Username must be between 3 and 50 characters');
        }
        
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            throw new InvalidArgumentException('Username can only contain letters, numbers, underscores, and hyphens');
        }
        
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

### Step 2: Repository Pattern Implementation
Create repository interfaces:
```bash
mkdir -p app/Application/Interfaces
```

Create `app/Application/Interfaces/UserRepositoryInterface.php`:
```php
<?php

namespace App\Application\Interfaces;

use App\Domains\User\Models\UserDomainModel;
use App\Domains\Shared\ValueObjects\Uuid;
use App\Domains\Shared\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function findById(Uuid $id): ?UserDomainModel;
    
    public function findByEmail(Email $email): ?UserDomainModel;
    
    public function save(UserDomainModel $user): void;
    
    public function update(UserDomainModel $user): void;
    
    public function delete(Uuid $id): void;
    
    public function exists(Email $email): bool;
}
```

Create infrastructure repository implementation:
```bash
mkdir -p app/Infrastructure/Repositories
```

Create `app/Infrastructure/Repositories/EloquentUserRepository.php`:
```php
<?php

namespace App\Infrastructure\Repositories;

use App\Application\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\UserDomainModel;
use App\Domains\Shared\ValueObjects\Uuid;
use App\Domains\Shared\ValueObjects\Email;
use App\Models\User as UserModel;
use DateTimeImmutable;
use Illuminate\Support\Str;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(Uuid $id): ?UserDomainModel
    {
        $userModel = UserModel::find($id->getValue());
        
        if (!$userModel) {
            return null;
        }
        
        return $this->toDomainModel($userModel);
    }

    public function findByEmail(Email $email): ?UserDomainModel
    {
        $userModel = UserModel::where('email', $email->getValue())->first();
        
        if (!$userModel) {
            return null;
        }
        
        return $this->toDomainModel($userModel);
    }

    public function save(UserDomainModel $user): void
    {
        $userModel = new UserModel();
        $userModel->id = $user->getId()->getValue();
        $userModel->name = $user->getUsername()->getValue();
        $userModel->email = $user->getEmail()->getValue();
        $userModel->password = $user->getPassword();
        $userModel->email_verified_at = now();
        $userModel->save();
    }

    public function update(UserDomainModel $user): void
    {
        $userModel = UserModel::find($user->getId()->getValue());
        
        if (!$userModel) {
            throw new \Exception('User not found');
        }
        
        $userModel->name = $user->getUsername()->getValue();
        $userModel->email = $user->getEmail()->getValue();
        $userModel->updated_at = now();
        $userModel->save();
    }

    public function delete(Uuid $id): void
    {
        $userModel = UserModel::find($id->getValue());
        
        if ($userModel) {
            $userModel->delete();
        }
    }

    public function exists(Email $email): bool
    {
        return UserModel::where('email', $email->getValue())->exists();
    }

    private function toDomainModel(UserModel $userModel): UserDomainModel
    {
        return new UserDomainModel(
            new Uuid($userModel->id),
            new \App\Domains\User\ValueObjects\Username($userModel->name),
            new Email($userModel->email),
            $userModel->password,
            new DateTimeImmutable($userModel->created_at->format('Y-m-d H:i:s')),
            new DateTimeImmutable($userModel->updated_at->format('Y-m-d H:i:s')),
            $userModel->deleted_at ? new DateTimeImmutable($userModel->deleted_at->format('Y-m-d H:i:s')) : null
        );
    }
}
```

### Step 3: Application Services
Create application services:
```bash
mkdir -p app/Application/Services
```

Create `app/Application/Services/UserService.php`:
```php
<?php

namespace App\Application\Services;

use App\Application\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\UserDomainModel;
use App\Domains\Shared\ValueObjects\Uuid;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\User\ValueObjects\Username;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $username, string $email, string $password): UserDomainModel
    {
        $emailVO = new Email($email);
        
        if ($this->userRepository->exists($emailVO)) {
            throw new \Exception('User with this email already exists');
        }

        $user = new UserDomainModel(
            new Uuid(Str::uuid()),
            new Username($username),
            $emailVO,
            Hash::make($password),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );

        $this->userRepository->save($user);

        return $user;
    }

    public function updateUser(Uuid $userId, ?string $username = null, ?string $email = null): ?UserDomainModel
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return null;
        }

        if ($username !== null) {
            $user = $user->updateUsername(new Username($username));
        }

        if ($email !== null) {
            $user = $user->updateEmail(new Email($email));
        }

        $this->userRepository->update($user);

        return $user;
    }

    public function deleteUser(Uuid $userId): bool
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return false;
        }

        $this->userRepository->delete($userId);
        
        return true;
    }

    public function getUserById(Uuid $userId): ?UserDomainModel
    {
        return $this->userRepository->findById($userId);
    }

    public function getUserByEmail(Email $email): ?UserDomainModel
    {
        return $this->userRepository->findByEmail($email);
    }
}
```

### Step 4: Dependency Injection and Service Provider
Create a service provider for domain services:
```bash
php artisan make:provider DomainServiceProvider
```

Create `app/Providers/DomainServiceProvider.php`:
```php
<?php

namespace App\Providers;

use App\Application\Interfaces\UserRepositoryInterface;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Application\Services\UserService;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public array $singletons = [
        UserRepositoryInterface::class => EloquentUserRepository::class,
        UserService::class => UserService::class,
    ];

    public function register()
    {
        // Bindings are registered in $singletons array
    }

    public function boot()
    {
        //
    }
}
```

Register the service provider in `config/app.php`:
```php
'providers' => [
    // ... other providers
    App\Providers\DomainServiceProvider::class,
],
```

### Step 5: Presentation Layer - Controllers and Requests
Create DTOs:
```bash
mkdir -p app/Application/DTOs
```

Create `app/Application/DTOs/CreateUserDTO.php`:
```php
<?php

namespace App\Application\DTOs;

class CreateUserDTO
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['username'],
            $data['email'],
            $data['password']
        );
    }
}
```

Create presentation controller:
```bash
php artisan make:controller Api/V2/UserController --api
```

Create `app/Http/Controllers/Api/V2/UserController.php`:
```php
<?php

namespace App\Http\Controllers\Api\V2;

use App\Application\Services\UserService;
use App\Application\DTOs\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $dto = CreateUserDTO::fromArray($request->all());
            
            $user = $this->userService->createUser(
                $dto->username,
                $dto->email,
                $dto->password
            );

            return (new UserResource($user))->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $uuid = new \App\Domains\Shared\ValueObjects\Uuid($id);
            $user = $this->userService->getUserById($uuid);
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'username' => 'sometimes|string|min:3|max:50',
            'email' => 'sometimes|email|unique:users,email,' . $id,
        ]);

        try {
            $uuid = new \App\Domains\Shared\ValueObjects\Uuid($id);
            
            $user = $this->userService->updateUser(
                $uuid,
                $request->username,
                $request->email
            );
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $uuid = new \App\Domains\Shared\ValueObjects\Uuid($id);
            $result = $this->userService->deleteUser($uuid);
            
            if (!$result) {
                return response()->json(['message' => 'User not found'], 404);
            }
            
            return response()->json(['message' => 'User deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
```

Create the UserResource:
```bash
php artisan make:resource UserResource
```

Create `app/Http/Resources/UserResource.php`:
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        // Note: This is simplified for demonstration
        // In a real implementation, you'd map the domain model properly
        return [
            'id' => $this->getId()->getValue(),
            'username' => $this->getUsername()->getValue(),
            'email' => $this->getEmail()->getValue(),
            'created_at' => $this->getCreatedAt()->format('c'),
            'updated_at' => $this->getUpdatedAt()->format('c'),
        ];
    }
}
```

### Step 6: Advanced Middleware
Create custom middleware for rate limiting specific to domain actions:
```bash
php artisan make:middleware DomainActionRateLimit
```

Create `app/Http/Middleware/DomainActionRateLimit.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class DomainActionRateLimit
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, string $action = 'general', int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request, $action);
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    protected function resolveRequestSignature($request, string $action): string
    {
        $userIdentifier = $request->user()?->id ?: $request->ip();
        
        return sha1($action . $userIdentifier . $request->getMethod() . $request->getUri());
    }

    protected function buildResponse($key, $maxAttempts)
    {
        $response = response()->json([
            'message' => 'Too many attempts.',
            'available_in_seconds' => $this->limiter->availableIn($key),
        ], 429);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            0,
            $this->limiter->availableIn($key)
        );
    }

    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, int $retryAfter = null)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        if (!is_null($retryAfter)) {
            $response->headers->add(['Retry-After' => $retryAfter, 'X-RateLimit-Reset' => time() + $retryAfter]);
        }

        return $response;
    }

    protected function calculateRemainingAttempts($key, $maxAttempts)
    {
        return $maxAttempts - $this->limiter->attempts($key);
    }
}
```

### Step 7: Event-Driven Architecture
Create domain events:
```bash
mkdir -p app/Domains/User/Events
```

Create `app/Domains/User/Events/UserCreated.php`:
```php
<?php

namespace App\Domains\User\Events;

use App\Domains\User\Models\UserDomainModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public UserDomainModel $user;

    public function __construct(UserDomainModel $user)
    {
        $this->user = $user;
    }
}
```

Create event listeners:
```bash
mkdir -p app/Infrastructure/Listeners
```

Create `app/Infrastructure/Listeners/SendWelcomeEmailListener.php`:
```php
<?php

namespace App\Infrastructure\Listeners;

use App\Domains\User\Events\UserCreated;
use App\Notifications\UserWelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserCreated $event)
    {
        // Send welcome email notification
        $event->user->getEmail()->getValue(); // Get email from domain model
        // In a real implementation, you'd convert the domain model to an Eloquent model
        // or use a mapper to send the notification
        
        \Log::info("Welcome email sent to user: " . $event->user->getId()->getValue());
    }
}
```

Register events in `EventServiceProvider`:
```php
protected $listen = [
    // ... other events
    \App\Domains\User\Events\UserCreated::class => [
        \App\Infrastructure\Listeners\SendWelcomeEmailListener::class,
    ],
];
```

### Step 8: Performance Optimization
Create a caching decorator for repository:
```bash
mkdir -p app/Infrastructure/Decorators
```

Create `app/Infrastructure/Decorators/CachedUserRepositoryDecorator.php`:
```php
<?php

namespace App\Infrastructure\Decorators;

use App\Application\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\UserDomainModel;
use App\Domains\Shared\ValueObjects\Uuid;
use App\Domains\Shared\ValueObjects\Email;
use Illuminate\Support\Facades\Cache;

class CachedUserRepositoryDecorator implements UserRepositoryInterface
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById(Uuid $id): ?UserDomainModel
    {
        $cacheKey = "user_{$id->getValue()}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->repository->findById($id);
        });
    }

    public function findByEmail(Email $email): ?UserDomainModel
    {
        $cacheKey = "user_email_{$email->getValue()}";
        
        return Cache::remember($cacheKey, 3600, function () use ($email) {
            return $this->repository->findByEmail($email);
        });
    }

    public function save(UserDomainModel $user): void
    {
        $this->repository->save($user);
        
        // Clear related cache
        Cache::forget("user_{$user->getId()->getValue()}");
        Cache::forget("user_email_{$user->getEmail()->getValue()}");
    }

    public function update(UserDomainModel $user): void
    {
        $this->repository->update($user);
        
        // Clear related cache
        Cache::forget("user_{$user->getId()->getValue()}");
        Cache::forget("user_email_{$user->getEmail()->getValue()}");
    }

    public function delete(Uuid $id): void
    {
        $user = $this->findById($id);
        if ($user) {
            $this->repository->delete($id);
            
            // Clear related cache
            Cache::forget("user_{$id->getValue()}");
            Cache::forget("user_email_{$user->getEmail()->getValue()}");
        }
    }

    public function exists(Email $email): bool
    {
        // Don't cache this as it's a simple DB query
        return $this->repository->exists($email);
    }
}
```

Update the service provider to use the cached decorator:
```php
public array $singletons = [
    UserRepositoryInterface::class => function ($app) {
        $repository = $app->make(EloquentUserRepository::class);
        return new CachedUserRepositoryDecorator($repository);
    },
    UserService::class => UserService::class,
];
```

### Step 9: Testing the Architecture
Create a comprehensive test:
```bash
php artisan make:test DomainArchitectureTest
```

Create `tests/Feature/DomainArchitectureTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Application\Services\UserService;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_through_domain_service()
    {
        $userService = $this->app->make(UserService::class);

        $user = $userService->createUser(
            'testuser',
            'test@example.com',
            'password123'
        );

        $this->assertInstanceOf(Uuid::class, $user->getId());
        $this->assertEquals('test@example.com', $user->getEmail()->getValue());
        $this->assertEquals('testuser', $user->getUsername()->getValue());
    }

    public function test_user_can_be_retrieved_by_id()
    {
        $userService = $this->app->make(UserService::class);

        // Create a user first
        $createdUser = $userService->createUser(
            'testuser2',
            'test2@example.com',
            'password123'
        );

        // Retrieve the user by ID
        $retrievedUser = $userService->getUserById($createdUser->getId());

        $this->assertNotNull($retrievedUser);
        $this->assertEquals($createdUser->getId()->getValue(), $retrievedUser->getId()->getValue());
        $this->assertEquals($createdUser->getEmail()->getValue(), $retrievedUser->getEmail()->getValue());
    }

    public function test_user_can_be_updated()
    {
        $userService = $this->app->make(UserService::class);

        // Create a user first
        $user = $userService->createUser(
            'originaluser',
            'original@example.com',
            'password123'
        );

        // Update the user
        $updatedUser = $userService->updateUser(
            $user->getId(),
            'updateduser',
            'updated@example.com'
        );

        $this->assertNotNull($updatedUser);
        $this->assertEquals('updateduser', $updatedUser->getUsername()->getValue());
        $this->assertEquals('updated@example.com', $updatedUser->getEmail()->getValue());
    }

    public function test_user_can_be_deleted()
    {
        $userService = $this->app->make(UserService::class);

        // Create a user first
        $user = $userService->createUser(
            'deleteuser',
            'delete@example.com',
            'password123'
        );

        // Verify user exists
        $existingUser = $userService->getUserById($user->getId());
        $this->assertNotNull($existingUser);

        // Delete the user
        $result = $userService->deleteUser($user->getId());
        $this->assertTrue($result);

        // Verify user no longer exists
        $nonExistentUser = $userService->getUserById($user->getId());
        $this->assertNull($nonExistentUser);
    }

    public function test_duplicate_email_validation()
    {
        $userService = $this->app->make(UserService::class);

        // Create first user
        $userService->createUser(
            'user1',
            'duplicate@example.com',
            'password123'
        );

        // Try to create another user with same email
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User with this email already exists');

        $userService->createUser(
            'user2',
            'duplicate@example.com',
            'password123'
        );
    }
}
```

### Step 10: API Documentation and Best Practices
Update the routes to use the new architecture:
```php
// In routes/api.php
use App\Http\Controllers\Api\V2\UserController;

Route::prefix('v2')->group(function () {
    Route::apiResource('users', UserController::class);
});
```

## Testing Your Complete Architecture
1. Run the domain architecture tests: `php artisan test --filter=DomainArchitectureTest`
2. Test the API endpoints with the new architecture
3. Verify that caching works properly
4. Check that events are dispatched correctly
5. Ensure dependency injection is working as expected

## Conclusion
This project demonstrated advanced Laravel application architecture following Domain-Driven Design principles. You learned to:

1. Separate concerns using domain, application, infrastructure, and presentation layers
2. Implement value objects for data integrity
3. Use repository patterns for data access abstraction
4. Apply dependency injection for loose coupling
5. Implement event-driven architecture
6. Add caching decorators for performance
7. Follow SOLID principles in Laravel applications

This architecture provides a solid foundation for building scalable, maintainable, and testable Laravel applications that can grow with your business needs.