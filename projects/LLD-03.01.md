# LLD-03.01: Single Action Controllers in Laravel

## Overview
This project teaches you how to create and use single action controllers in Laravel. Single action controllers contain only one method (the __invoke method) and are ideal for handling specific, isolated tasks in your application.

## Learning Objectives
- Create invokable controllers using the __invoke method
- Generate single action controllers with Artisan command
- Register routes for single action controllers
- Determine when to use single action controllers vs traditional controllers

## Prerequisites
- Completed LLD-03 or have a working knowledge of basic controllers
- Understanding of routing concepts

## Implementation Outline

### Controllers to Implement
- ProvisionServerController as an invokable controller for server provisioning
- SendNewsletterController for handling newsletter sending
- ProcessPaymentController for payment processing tasks
- ReportGeneratorController for generating reports

### Routes to Implement
- POST route to ProvisionServerController
- POST route to SendNewsletterController
- POST route to ProcessPaymentController
- GET route to ReportGeneratorController

### Functions to Implement
- __invoke method in each single action controller
- Business logic encapsulation in single methods
- Input validation in invokable controllers
- Error handling in single action controllers

### Features to Implement
- Basic invokable controller creation
- Artisan command generation with --invokable flag
- Route registration for single action controllers
- Appropriate use cases for single action controllers
- Dependency injection in invokable controllers

## Testing Your Implementation
1. Generate invokable controllers using php artisan make:controller --invokable
2. Implement business logic in the __invoke method
3. Register routes without specifying method names
4. Test that the controllers respond correctly to requests
5. Verify dependency injection works in the __invoke method

## Conclusion
This project taught you how to create and use single action controllers in Laravel. Single action controllers are useful for handling specific tasks that don't warrant a full controller with multiple methods, helping keep your codebase clean and organized.