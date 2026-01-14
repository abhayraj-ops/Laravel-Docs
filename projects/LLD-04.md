# LLD-04: Laravel Middleware

## Overview
This project teaches you how to create and use middleware in Laravel. Middleware acts as a filter between HTTP requests and responses, allowing you to modify requests, check permissions, validate tokens, and more.

## Learning Objectives
- Create custom middleware using Artisan
- Apply middleware to routes and route groups
- Understand the middleware execution flow
- Implement authentication and authorization middleware
- Use Laravel's built-in middleware

## Prerequisites
- Completed LLD-01, LLD-02, and LLD-03
- Understanding of routing and controllers
- Basic knowledge of HTTP requests and responses

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, role, timestamps
- Sessions table for session management

### Tables
- users table for user authentication and authorization
- sessions table for managing user sessions

### Middleware to Implement
- CheckAge middleware to validate user age requirements
- AuthenticateUser middleware to verify user authentication
- CheckRole middleware to enforce role-based access control
- LogRequests middleware to log incoming requests and responses

### Routes to Implement
- GET `/adult-content` with age check middleware
- GET `/movies` and `/events` routes in age-restricted group
- GET `/premium-content` with chained age and auth middleware
- GET `/admin-panel` with authentication and role-based middleware
- POST `/submit-form` with CSRF protection
- GET `/api/data` with rate limiting middleware

### Middleware Registration
- Register custom middleware in app/Http/Kernel.php route middleware array
- Configure middleware aliases for easy reference
- Set middleware priority in the kernel configuration

### API Endpoints
- GET `/api/data` with rate limiting (60 requests per minute)
- POST `/api/secure-endpoint` with authentication and authorization
- Various endpoints with different middleware combinations

### Functions to Implement
- Handle method in each middleware class for request processing
- Request validation and filtering logic in middleware
- Response modification capabilities in middleware
- Parameter acceptance in middleware for flexible usage

### Features to Implement
- Custom middleware creation using Artisan command
- Middleware application to individual routes
- Middleware application to route groups
- Multiple middleware chaining on routes
- Parameterized middleware for dynamic behavior
- Integration with Laravel's built-in middleware (CSRF, auth, throttle)
- Middleware priority configuration
- Request and response logging functionality

## Testing Your Middleware
1. Start the development server
2. Test routes with different middleware configurations
3. Verify that middleware is correctly filtering requests
4. Check logs for request/response logging middleware
5. Test authentication and authorization flows

## Conclusion
This project taught you how to create and use middleware in Laravel. Middleware is a powerful feature that allows you to filter HTTP requests entering your application. You learned to create custom middleware, apply it to routes and groups, and use Laravel's built-in middleware for common tasks like authentication and rate limiting.