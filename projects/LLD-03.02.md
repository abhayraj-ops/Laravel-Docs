# LLD-03.02: Advanced Controller Middleware in Laravel

## Overview
This project teaches you about advanced controller middleware techniques in Laravel, including the HasMiddleware interface and inline middleware definitions. You'll learn how to apply middleware more efficiently at the controller level.

## Learning Objectives
- Implement the HasMiddleware interface in controllers
- Define controller middleware using the static middleware() method
- Use the Middleware class with only/except options
- Create inline middleware as closures within controllers
- Apply middleware selectively to controller actions

## Prerequisites
- Completed LLD-03 or have a working knowledge of basic controllers and middleware
- Understanding of Laravel's middleware system

## Implementation Outline

### Controllers to Implement
- UserController implementing HasMiddleware interface
- AdminController with selective middleware application
- ReportController with conditional middleware
- DashboardController with multiple middleware layers

### Middleware to Implement
- Authentication middleware applied via HasMiddleware
- Role-based access middleware with only/except options
- Rate limiting middleware for specific actions
- Logging middleware for selected actions

### Functions to Implement
- Static middleware() method in controllers
- Middleware selection using only/except parameters
- Inline middleware closure definitions
- Middleware priority and ordering functions

### Features to Implement
- HasMiddleware interface implementation
- Static middleware method with array return
- Middleware class with only/except options
- Inline closure middleware in controllers
- Selective middleware application to specific methods

## Testing Your Implementation
1. Implement HasMiddleware interface in a controller
2. Define middleware using the static middleware() method
3. Test selective middleware application with only/except options
4. Verify inline closure middleware works correctly
5. Ensure middleware is applied in the correct order

## Conclusion
This project taught you advanced techniques for applying middleware to controllers in Laravel. Using the HasMiddleware interface and related features provides a cleaner way to define controller-level middleware and offers more granular control over which actions receive specific middleware.