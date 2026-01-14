# LLD-02.10: Dependency Injection in Laravel Routes

## Overview
This project teaches you how to use dependency injection directly in Laravel route closures. You'll learn to type-hint dependencies in route signatures and have Laravel automatically resolve and inject them from the service container.

## Learning Objectives
- Type-hint dependencies in route closures
- Understand Laravel's automatic dependency injection in routes
- Inject common services like Request, Session, and Auth
- Use dependency injection for cleaner route handlers

## Prerequisites
- Completed LLD-02 or have a working knowledge of basic routing
- Understanding of Laravel's service container and dependency injection

## Implementation Outline

### Routes to Implement
- Routes with Request dependency injection
- Routes with Session dependency injection
- Routes with custom service dependencies
- Routes with multiple injected dependencies

### Controllers to Implement
- ServiceInjectionController for comparison with route injection
- DependencyDemoController for dependency examples

### Functions to Implement
- Route dependency injection methods
- Service resolution in route closures
- Multiple dependency injection patterns
- Custom service injection examples

### Features to Implement
- Basic dependency injection in routes
- Request object injection
- Session and Auth facade injection
- Custom service injection
- Multiple dependency injection
- Comparison with controller-based injection

## Testing Your Implementation
1. Create routes with Request dependency injection
2. Test routes with Session dependency injection
3. Implement routes with custom service dependencies
4. Verify that dependencies are properly resolved
5. Compare route injection with controller injection
6. Test multiple dependency injection in a single route

## Conclusion
This project taught you how to use dependency injection directly in Laravel route closures. This feature allows you to keep simple route handlers clean by having Laravel automatically resolve and inject the dependencies you need, similar to how dependency injection works in controllers.