# LLD-09: Laravel Authentication

## Overview
This project focuses on Laravel's built-in authentication system. You'll learn how to implement user registration, login, logout, password reset, and authorization features using Laravel's authentication scaffolding and APIs.

## Learning Objectives
- Set up Laravel authentication
- Implement user registration and login
- Create password reset functionality
- Protect routes with authentication middleware
- Implement user authorization and policies

## Prerequisites
- Completed LLD-01 through LLD-08
- Understanding of Eloquent ORM and migrations
- Knowledge of middleware and controllers

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, email_verified_at, remember_token, phone, date_of_birth, bio, is_active, avatar, timestamps
- Password reset tokens table for password reset functionality
- Failed jobs table for failed job logging
- Personal access tokens table for API authentication (if needed)

### Tables
- users table for user accounts
- password_reset_tokens table for password reset functionality
- sessions table for session management
- personal_access_tokens table for API tokens (if needed)

### Authentication Components to Implement
- User model with authentication features and additional fields
- Authentication controllers for login, registration, password reset
- Authentication middleware for route protection
- Password reset notification system
- Email verification functionality

### Forms to Implement
- User registration form with validation
- Login form with authentication
- Password reset request form
- Password reset form
- Profile update form

### Routes to Implement
- Authentication routes (login, register, password reset) handled by Laravel Breeze
- Protected routes requiring authentication
- Admin routes requiring specific roles/permissions
- Profile management routes

### Middleware to Implement
- Authentication middleware ('auth') for protecting routes
- Role-based access middleware for restricting access by user roles
- Verified email middleware for email verification requirement
- Guest middleware for preventing authenticated users from accessing login/register pages

### Authorization Implementation
- Gates for simple authorization checks
- Policies for model-based authorization
- Permission-based access control
- Role-based access control

### API Endpoints
- POST `/api/login` - User login
- POST `/api/logout` - User logout
- POST `/api/register` - User registration
- POST `/api/password/email` - Password reset request
- POST `/api/password/reset` - Password reset
- GET `/api/user` - Get authenticated user

### Functions to Implement
- User registration with validation and account creation
- User authentication and session management
- Password hashing and verification
- Password reset token generation and validation
- Email verification token handling
- Authorization checks using gates and policies
- Account management functions

### Features to Implement
- User registration with validation and account creation
- Secure login and logout functionality
- Password reset via email with token verification
- Email verification system
- Route protection with authentication middleware
- Role-based and policy-based authorization
- Account management (profile update, password change)
- Session management and security
- Login throttling to prevent brute force attacks
- Remember me functionality

## Testing Your Authentication System
1. Register a new user account
2. Login with the new account
3. Access protected routes
4. Test password reset functionality
5. Verify authorization works correctly
6. Test different user roles and permissions
7. Verify email verification process
8. Test login throttling mechanism

## Conclusion
This project taught you how to implement a complete authentication system in Laravel. You learned to set up user registration, login, password reset, route protection, and authorization using both gates and policies. Laravel's authentication system provides a solid foundation for securing your applications and managing user access.