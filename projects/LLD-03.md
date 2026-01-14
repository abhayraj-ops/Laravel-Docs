# LLD-03: Laravel Controllers

## Overview
This project focuses on creating and using controllers in Laravel. You'll learn how to organize your request handling logic using controller classes, implement different types of controllers, and connect them to routes.

## Learning Objectives
- Create basic controllers using Artisan command
- Implement resource controllers for CRUD operations
- Use controller middleware for request filtering
- Pass data from controllers to views with proper dependency injection
- Implement controller methods for different HTTP verbs
- Configure service container bindings for controller dependencies

## Prerequisites
- Completed LLD-01 and LLD-02
- Understanding of routing concepts
- Basic knowledge of PHP classes

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, timestamps
- Posts table with fields: id, title, content, user_id, timestamps

### Tables
- users table for user management
- posts table for post management

### Controllers to Implement
- UserController with methods: index, show, create, store, edit, update, destroy
- PostController as a resource controller with full CRUD methods
- Custom controller with dependency injection capabilities

### Routes to Implement
- GET `/users` mapped to UserController@index
- GET `/users/create` mapped to UserController@create
- POST `/users` mapped to UserController@store
- GET `/users/{id}` mapped to UserController@show
- GET `/users/{id}/edit` mapped to UserController@edit
- PUT `/users/{id}` mapped to UserController@update
- DELETE `/users/{id}` mapped to UserController@destroy
- Resource routes for posts at `/posts` with all seven RESTful methods

### Middleware Implementation
- Authentication middleware for protected routes
- Guest middleware for public routes
- Custom middleware for specific controller actions
- Middleware applied to entire controllers and specific methods

### API Endpoints
- GET `/api/users` - Retrieve all users
- POST `/api/users` - Create a new user
- GET `/api/users/{id}` - Retrieve a specific user
- PUT `/api/users/{id}` - Update a specific user
- DELETE `/api/users/{id}` - Delete a specific user
- Resource endpoints for posts with full CRUD operations

### Functions to Implement
- Controller constructor for middleware setup
- Index method to display resource listings
- Show method to display specific resources
- Create method to show creation forms
- Store method to handle resource creation
- Edit method to show editing forms
- Update method to handle resource updates
- Destroy method to handle resource deletion
- Validation functions for input processing

### Features to Implement
- Basic controller creation with Artisan command
- Resource controller implementation with full CRUD operations
- Controller middleware application to entire controllers and specific methods
- Data passing from controllers to views using various methods
- Dependency injection in controller methods
- Input validation in controller methods
- Service container integration for controller dependencies

## Testing Your Controllers
1. Start the development server
2. Access the routes connected to your controllers
3. Test form submissions and CRUD operations
4. Verify that middleware is working correctly
5. Test API endpoints with appropriate HTTP clients

## Conclusion
This project taught you how to organize your application logic using controllers. Controllers help keep your routes file clean and provide a structured way to handle HTTP requests. You learned about basic controllers, resource controllers, middleware, and passing data to views.