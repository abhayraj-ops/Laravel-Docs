# LLD-01: Laravel Basics - Installation and Project Setup

## Overview
This project focuses on learning the fundamentals of Laravel by creating a new Laravel application, understanding the project structure, and setting up the basic configuration. You'll learn how to install Laravel, create your first application, and navigate the default project structure.

## Learning Objectives
- Install Laravel using the Laravel installer
- Create a new Laravel application
- Understand the basic Laravel project structure
- Configure environment variables
- Run the development server

## Prerequisites
- PHP 8.0+ installed
- Composer installed
- Basic understanding of command line

## Implementation Outline

### Database Schema
- No database required for initial setup

### Tables
- No tables required for initial setup

### Routes to Implement
- Default welcome route at `/` showing Laravel welcome page

### Configuration Files to Set Up
- `.env` file with database connection settings
- `config/app.php` with basic application settings
- `config/database.php` with database configuration

### Commands to Execute
- Global installation of Laravel installer via Composer
- Creation of new Laravel application
- Generation of application encryption key
- Starting of development server

### Environment Variables to Configure
- Database connection settings (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Application key (APP_KEY)
- Application environment (APP_ENV)
- Debug mode setting (APP_DEBUG)

### Project Structure Elements to Understand
- `app/` directory containing core application code
- `config/` directory containing configuration files
- `database/` directory containing migrations, seeds, and factories
- `public/` directory containing index.php and assets
- `resources/` directory containing views and raw assets
- `routes/` directory containing route definitions
- `storage/` directory containing logs, caches, and compiled files
- `tests/` directory containing test files

### Features to Implement
- Laravel application installation
- Environment configuration
- Application key generation
- Development server setup
- Basic project structure navigation

## Testing Your Setup
1. Execute the development server start command
2. Access the application via browser at the specified address
3. Verify that the Laravel welcome page loads correctly
4. Confirm that all configuration settings are properly applied

## Conclusion
This project introduces you to Laravel's basic setup and project structure. Understanding these fundamentals is crucial for building more complex applications later. You now have a working Laravel application and know how to start the development server.