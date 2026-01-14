# LLD-06: Laravel Views and Blade Templates

## Overview
This project teaches you how to create and use views in Laravel with the powerful Blade templating engine. You'll learn to separate your presentation logic from your business logic and create dynamic, reusable templates.

## Learning Objectives
- Create and organize views in Laravel
- Use Blade templating syntax and features
- Implement template inheritance and sections
- Work with Blade components and slots
- Share data with views

## Prerequisites
- Completed LLD-01 through LLD-05
- Understanding of controllers and responses
- Basic knowledge of HTML and CSS

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, active status, bio, timestamps
- Notifications table for notification system (optional)

### Tables
- users table for user data
- notifications table for user notifications (if needed)

### Views to Implement
- Welcome view (`resources/views/welcome.blade.php`)
- Users index view (`resources/views/users/index.blade.php`)
- Master layout view (`resources/views/layouts/app.blade.php`)
- Child views extending master layout
- Shared component views in `resources/views/components/`
- Partial views in `resources/views/shared/`

### Controllers to Implement
- WelcomeController for welcome page
- UserController for user-related views
- View management methods for different view scenarios

### Routes to Implement
- GET `/welcome` route for welcome page
- GET `/users` route for users list
- GET `/users/list` route for template inheritance example
- Additional routes for component and composer examples

### Blade Features to Implement
- Variable display with HTML escaping
- Control structures (if/else, loops, switch statements)
- Template inheritance with layouts and sections
- View inclusion with parameters
- Stacks for asset management
- Conditional directives

### Components to Implement
- Alert component for displaying messages
- Button component for interactive elements
- Custom components with attributes and slots
- Anonymous components for simple UI elements

### View Composers to Implement
- SidebarComposer for sharing sidebar data
- Global data sharing mechanisms
- View creator functionality

### API Endpoints
- GET `/api/users` - Retrieve users for AJAX views
- POST `/api/users` - Create users via AJAX
- Various endpoints for dynamic view updates

### Functions to Implement
- View rendering methods in controllers
- Data passing methods to views
- Component rendering logic
- View composer composition methods
- Asset management functions

### Features to Implement
- Basic view creation and organization
- Blade templating syntax implementation
- Template inheritance system
- Component-based architecture
- Data sharing across views
- View composer implementation
- Asset stacking functionality
- Dynamic view rendering

## Testing Your Views
1. Start the development server
2. Visit the routes that return your views
3. Test different data scenarios
4. Verify that Blade directives work correctly
5. Check that template inheritance works as expected
6. Test component functionality
7. Verify view composer data sharing

## Conclusion
This project taught you how to create and use views in Laravel with the Blade templating engine. You learned about Blade syntax, template inheritance, components, and how to share data across views. These skills are essential for creating well-structured, maintainable web applications with clean separation of concerns between business logic and presentation.