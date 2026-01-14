# LLD-02.02: Subdomain Routing in Laravel

## Overview
This project teaches you how to implement subdomain routing in Laravel, allowing different sections of your application to be accessible via different subdomains. You'll learn to capture subdomain segments and route them appropriately.

## Learning Objectives
- Configure subdomain routes in Laravel
- Capture subdomain segments as route parameters
- Implement subdomain-based application logic
- Understand subdomain routing precedence

## Prerequisites
- Completed LLD-02 or have a working knowledge of basic routing
- Understanding of domain names and subdomains

## Implementation Outline

### Routes to Implement
- Subdomain route capturing account segment: `{account}.example.com/user/{id}`
- Multiple subdomain routes for different purposes
- Root domain routes that don't interfere with subdomain routes
- Wildcard subdomain routes for flexible subdomain handling

### Controllers to Implement
- SubdomainUserController for handling subdomain-specific user logic
- AccountDashboardController for subdomain dashboards
- SubdomainMiddlewareController for subdomain-specific middleware

### Functions to Implement
- Subdomain route registration methods
- Subdomain parameter validation functions
- Subdomain-based access control methods
- Subdomain URL generation helpers

### Features to Implement
- Basic subdomain routing with parameter capture
- Subdomain-specific middleware application
- Subdomain route grouping
- Subdomain-aware URL generation
- Subdomain routing precedence management

## Testing Your Implementation
1. Set up subdomain routes and test with different subdomain names
2. Verify that subdomain parameters are captured correctly
3. Test that root domain routes still work properly
4. Ensure subdomain routes take precedence over root domain routes when appropriate
5. Verify middleware is applied correctly to subdomain routes

## Conclusion
This project taught you how to implement subdomain routing in Laravel, allowing you to create applications with different functionality accessible via different subdomains. This technique is useful for multi-tenant applications or organizing different sections of your application.