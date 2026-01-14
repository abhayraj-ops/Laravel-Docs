# LLD-02.01: Advanced Named Routes in Laravel

## Overview
This project teaches you advanced named route techniques in Laravel, including default parameters, route inspection, and complex named route scenarios. You'll learn to leverage named routes for more maintainable and flexible applications.

## Learning Objectives
- Implement default parameters for named routes
- Inspect current routes using route inspection methods
- Create complex named route hierarchies
- Use named routes with complex parameter combinations

## Prerequisites
- Completed LLD-02 or have a working knowledge of basic routing and named routes
- Understanding of HTTP methods and route parameters

## Implementation Outline

### Routes to Implement
- Named route with default parameters using URL::defaults()
- Route that inspects the current route name using request()->route()->named()
- Named routes with multiple optional parameters
- Named routes with complex parameter constraints

### Controllers to Implement
- RouteInspectorController for demonstrating route inspection
- ParameterDemoController for default parameter examples

### Functions to Implement
- URL parameter default setting in RouteServiceProvider
- Route inspection methods in middleware
- Named route generation with complex parameters
- Default parameter override functions

### Features to Implement
- Setting default URL parameters globally
- Inspecting current route in middleware and controllers
- Generating URLs with default and overridden parameters
- Complex named route parameter validation

## Testing Your Implementation
1. Set up default parameters and verify they appear in generated URLs
2. Create routes that inspect the current route name
3. Test named route generation with various parameter combinations
4. Verify that default parameters can be overridden when needed

## Conclusion
This project expanded your knowledge of Laravel's named routes by introducing advanced techniques like default parameters and route inspection. These features enhance the flexibility and maintainability of your routing system.