# LLD-02.11: Route Listing in Laravel

## Overview
This project teaches you about Laravel's route:list Artisan command, which provides an overview of all routes defined in your application. You'll learn how to use this command effectively for debugging and understanding your application's routing structure.

## Learning Objectives
- Use the php artisan route:list command
- Interpret route:list output effectively
- Filter routes using route:list options
- Use route:list for debugging and analysis

## Prerequisites
- Completed LLD-02 or have a working knowledge of routing
- Understanding of Laravel's Artisan command-line tools

## Implementation Outline

### Routes to Implement
- Various route types to demonstrate route:list output
- Named routes for route:list identification
- Routes with different middleware for route:list display
- Routes with parameters for route:list demonstration

### Controllers to Implement
- RouteListDemoController for demonstrating different route types
- AnalysisController for route organization examples

### Functions to Implement
- Route organization for clear route:list output
- Route naming conventions for route:list clarity
- Middleware application for route:list filtering
- Route documentation for route:list annotations

### Features to Implement
- Basic route:list usage
- Verbose route:list with middleware (-v option)
- Path filtering (--path option)
- Vendor route filtering (--except-vendor/--only-vendor)
- Route:list for debugging and analysis
- Route organization best practices

## Testing Your Implementation
1. Run php artisan route:list and examine the output
2. Use verbose option to see middleware information
3. Filter routes by path using --path option
4. Use --except-vendor to hide package routes
5. Use --only-vendor to show only package routes
6. Organize routes to make route:list output more readable

## Conclusion
This project taught you about Laravel's route:list Artisan command, an essential tool for understanding and debugging your application's routing structure. The route:list command provides valuable insights into all defined routes, their methods, URIs, names, and middleware, making it easier to maintain and debug your routing system.