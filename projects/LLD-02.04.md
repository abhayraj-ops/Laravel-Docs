# LLD-02.04: Route Name Prefixes in Laravel

## Overview
This project teaches you how to use route name prefixes in Laravel, allowing you to organize your named routes with hierarchical naming conventions. You'll learn to apply prefixes to route groups and understand how they affect route name generation.

## Learning Objectives
- Apply name prefixes to route groups
- Generate hierarchical route names
- Reference prefixed routes in your application
- Understand the benefits of organized route naming

## Prerequisites
- Completed LLD-02 or have a working knowledge of named routes
- Understanding of route groups and route generation

## Implementation Outline

### Routes to Implement
- Group of routes with 'admin.' name prefix
- Group of API routes with 'api.v1.' name prefix
- Nested groups with cumulative name prefixes
- Routes with complex hierarchical naming

### Controllers to Implement
- PrefixedRouteDemoController for demonstrating name prefixes
- AdminSectionController for admin route examples
- ApiVersionController for API versioning examples

### Functions to Implement
- Route name prefix application methods
- Hierarchical route name generation
- Route name resolution functions
- Prefixed route URL generation helpers

### Features to Implement
- Basic route name prefixes in groups
- Cumulative prefixes in nested groups
- Referencing prefixed routes in redirects and links
- Route name prefix best practices

## Testing Your Implementation
1. Create route groups with name prefixes
2. Verify that route names are generated with correct prefixes
3. Test referencing prefixed routes using route() helper
4. Verify that redirects work with prefixed route names
5. Test nested groups with cumulative prefixes

## Conclusion
This project taught you how to implement route name prefixes in Laravel, helping you organize your named routes with hierarchical naming conventions. This technique improves maintainability and makes it easier to understand the relationship between different routes in your application.