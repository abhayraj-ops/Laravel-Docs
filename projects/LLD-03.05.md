# LLD-03.05: Nested Resource Routes in Laravel

## Overview
This project teaches you how to create and use nested resource routes in Laravel. Nested resources allow you to define relationships between resources in your routing structure, such as photos.comments or posts.tags.

## Learning Objectives
- Define nested resource routes using dot notation
- Create controllers for nested resources
- Handle route model binding in nested resources
- Implement proper database relationships for nested resources

## Prerequisites
- Completed LLD-03 or have a working knowledge of resource controllers
- Understanding of database relationships and Eloquent models

## Implementation Outline

### Controllers to Implement
- PhotoCommentController for handling photo comments
- PostTagController for managing post tags
- CategoryProductController for category-product relationships
- UserOrderItemController for user order items

### Routes to Implement
- Nested resource routes using dot notation (photos.comments)
- Multiple levels of nested resources
- Nested API resource routes
- Nested resources with custom route names

### Models to Implement
- Photo and Comment models with proper relationships
- Post and Tag models with proper relationships
- Category and Product models
- User, Order, and OrderItem models

### Functions to Implement
- Nested resource route handling methods
- Parent-child relationship enforcement
- Authorization checks for nested resources
- Proper model binding in nested routes

### Features to Implement
- Basic nested resource route definition
- Multi-level nesting (e.g., user.posts.comments)
- Nested resource route parameter binding
- Relationship validation in nested resources
- Nested resource authorization

## Testing Your Implementation
1. Define nested resource routes using dot notation
2. Create appropriate controllers for nested resources
3. Test nested route access with proper parent-child relationships
4. Verify route model binding works correctly
5. Ensure authorization checks are in place for nested resources

## Conclusion
This project taught you how to create and use nested resource routes in Laravel. Nested resources provide a clean way to represent relationships between resources in your routing structure, making your API more intuitive and organized.