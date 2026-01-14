# LLD-03.04: API Resource Routes in Laravel

## Overview
This project teaches you how to use API resource routes in Laravel, which automatically exclude the create and edit routes typically used for HTML forms. You'll learn to create resource controllers optimized for API endpoints.

## Learning Objectives
- Use Route::apiResource() for API-specific routes
- Generate API resource controllers with the --api flag
- Understand the difference between regular and API resource routes
- Implement API resource controllers without HTML-specific methods

## Prerequisites
- Completed LLD-03 or have a working knowledge of resource controllers
- Understanding of RESTful API concepts

## Implementation Outline

### Controllers to Implement
- ApiUserController generated with --api flag
- ApiPostController for post API endpoints
- ApiProductController for product API endpoints
- ApiOrderController for order API endpoints

### Routes to Implement
- API resource routes for users using Route::apiResource()
- API resource routes for posts
- Multiple API resource routes using apiResources()
- Nested API resource routes

### Functions to Implement
- Standard resource methods (index, store, show, update, destroy)
- JSON response formatting methods
- API error handling methods
- Resource transformation methods

### Features to Implement
- Basic API resource route registration
- API resource controller generation with --api flag
- Multiple API resource registration
- API resource route parameter binding
- API resource route model binding

## Testing Your Implementation
1. Generate API resource controllers using php artisan make:controller --api
2. Register API resource routes using Route::apiResource()
3. Verify that create and edit routes are excluded
4. Test all API resource endpoints (GET, POST, PUT, DELETE)
5. Confirm that routes return appropriate JSON responses

## Conclusion
This project taught you how to use API resource routes in Laravel, which are optimized for API endpoints by excluding the create and edit routes typically used for HTML forms. This simplifies API development by focusing on the essential CRUD operations for API resources.