# LLD-02: Laravel Routing Fundamentals

## Overview
This project teaches you how to define routes in Laravel, including basic routes, route parameters, named routes, and route groups. You'll learn how to handle different HTTP methods and create organized route structures.

## Learning Objectives
- Define basic routes for different HTTP methods
- Use route parameters and constraints
- Create named routes for easy URL generation
- Organize routes using route groups
- Apply middleware to routes

## Prerequisites
- Completed LLD-01 or have a working Laravel application
- Understanding of HTTP methods (GET, POST, PUT, DELETE)

## Implementation Outline

### Database Schema
- No database required for this routing-focused project

### Tables
- No tables required for this routing-focused project

### Routes to Implement
#### Basic Routes
- GET route at `/welcome` returning welcome message
- GET route at `/users` returning all users message
- POST route at `/users` for creating new users
- PUT route at `/users/{id}` for updating users
- DELETE route at `/users/{id}` for deleting users

#### Route Parameters
- GET route with required parameter at `/users/{id}`
- GET route with multiple parameters at `/posts/{post}/comments/{comment}`
- GET route with optional parameter at `/users/{name?}`
- GET route with parameter constraint at `/users/{id}` accepting only numeric IDs
- GET route with multiple parameter constraints at `/users/{id}/{name}` accepting numeric IDs and alphabetic names

#### Named Routes
- Named route called 'profile' at `/users/profile`
- Redirect route at `/redirect-profile` that redirects to the profile route
- Route at `/profile-url` that generates and displays the profile route URL

#### Route Groups
- Admin group with prefix 'admin' containing:
  - GET route at `/users` for admin users page
  - GET route at `/posts` for admin posts page
- Authentication group containing:
  - GET route at `/dashboard` for dashboard page
  - GET route at `/settings` for settings page
- Namespace group using 'Admin' namespace containing:
  - GET route at `/users` resolving to Admin\UserController

### Middleware Implementation
- Custom middleware named 'AgeVerification' that checks if request age is greater than 20
- Register 'age.verify' middleware in route middleware array
- Apply 'age.verify' middleware to route at `/adult-content`

### API Endpoints
- GET `/api/users` - Retrieve all users
- POST `/api/users` - Create a new user
- PUT `/api/users/{id}` - Update a specific user
- DELETE `/api/users/{id}` - Delete a specific user
- GET `/api/users/{id}` - Retrieve a specific user with parameter validation

### Functions to Implement
- Route handler functions for each defined route
- Middleware handle function for age verification logic
- Helper functions for route parameter validation

### Features to Implement
- Basic HTTP method routing (GET, POST, PUT, DELETE)
- Route parameter handling with required, optional, and constrained parameters
- Named route creation and URL generation
- Route grouping with prefixes, middleware, and namespaces
- Custom middleware creation and application
- Route parameter validation and constraints

## Testing Your Routes
1. Start the development server using Artisan command
2. Access each defined route via browser or API testing tool
3. Test all HTTP methods with appropriate tools
4. Verify middleware functionality with different age inputs

## Conclusion
This project taught you the fundamentals of Laravel routing, including parameters, named routes, groups, and middleware. These concepts are essential for organizing your application's URL structure and controlling access to different parts of your application.