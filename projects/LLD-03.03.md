# LLD-03.03: Form Request Validation in Laravel Controllers

## Overview
This project teaches you how to use form request classes for validation in Laravel controllers. Form requests provide a convenient way to validate incoming request data and handle authorization checks in dedicated classes.

## Learning Objectives
- Create form request classes for validation
- Define validation rules in form request classes
- Type-hint form requests in controller methods
- Handle authorization in form requests
- Customize validation error messages and attributes

## Prerequisites
- Completed LLD-03 or have a working knowledge of controllers and validation
- Understanding of Laravel's validation system

## Implementation Outline

### Form Request Classes to Implement
- StoreUserRequest with user creation validation rules
- UpdateUserRequest with user update validation rules
- StorePostRequest with post creation validation rules
- ContactFormRequest with contact form validation rules

### Controllers to Implement
- UserController with form request injection
- PostController using form requests for validation
- ContactController handling contact form requests
- ProfileController with profile update validation

### Functions to Implement
- rules() method in form request classes
- authorize() method for access control
- messages() method for custom error messages
- attributes() method for custom attribute names
- validated() method usage in controllers

### Features to Implement
- Basic form request creation with Artisan command
- Validation rules definition in form requests
- Form request injection in controller methods
- Authorization checks in form requests
- Custom error messages and attributes
- Redirect handling with validation errors

## Testing Your Implementation
1. Generate form request classes using php artisan make:request
2. Define validation rules in the rules() method
3. Type-hint form requests in controller methods
4. Test validation with valid and invalid data
5. Verify authorization checks work in form requests
6. Test custom error messages and attributes

## Conclusion
This project taught you how to use form request classes for validation in Laravel controllers. Form requests provide a clean, organized way to handle validation logic separately from your controller code, improving maintainability and reusability.