# LLD-07: Laravel Database and Migrations

## Overview
This project focuses on Laravel's database management capabilities, particularly migrations. You'll learn how to create, run, and manage database schemas using Laravel's migration system, which acts like version control for your database.

## Learning Objectives
- Create and run database migrations
- Rollback and refresh migrations
- Manage database relationships
- Seed databases with initial data
- Use Laravel's Schema Builder

## Prerequisites
- Completed LLD-01 through LLD-06
- Understanding of relational databases
- Basic SQL knowledge

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, email_verified_at, remember_token, phone, date_of_birth, bio, is_active, timestamps, soft_deletes
- Articles/posts table with fields: id, user_id, category_id, title, slug, excerpt, content, published, published_at, timestamps
- Categories table with fields: id, name, slug, description, timestamps
- Comments table with fields: id, post_id, user_id, content, approved, timestamps
- Tags table with fields: id, name, slug, timestamps
- Post-tag pivot table with fields: id, post_id, tag_id, timestamps

### Tables
- users table for user accounts
- posts table for blog posts/articles
- categories table for content categorization
- comments table for user comments
- tags table for content tagging
- post_tag table for many-to-many relationship between posts and tags

### Migrations to Implement
- Create users table migration with unique constraints and indexes
- Create posts table migration with foreign key constraints and indexes
- Create categories table migration with unique constraints
- Create comments table migration with foreign key constraints and indexes
- Create tags table migration with unique constraints
- Create post_tag pivot table migration with composite unique key
- Modify users table migration to add profile fields and soft deletes
- Add indexes to frequently queried columns

### Database Configuration
- Configure database connection settings in .env file
- Set up database credentials (host, port, database name, username, password)
- Configure database connection in config/database.php

### Seeding Implementation
- UserSeeder for populating users table
- CategorySeeder for populating categories table
- ArticleSeeder for populating posts table
- CommentSeeder for populating comments table
- TagSeeder for populating tags table
- Model factories for generating realistic test data

### Foreign Key Constraints
- User-posts relationship (one-to-many)
- Category-posts relationship (one-to-many)
- Post-comments relationship (one-to-many)
- User-comments relationship (one-to-many)
- Post-tags relationship (many-to-many)

### Migration Commands to Use
- php artisan make:migration for creating new migrations
- php artisan migrate for running migrations
- php artisan migrate:rollback for rolling back migrations
- php artisan migrate:refresh for refreshing database
- php artisan migrate:fresh for fresh database setup
- php artisan migrate:status for checking migration status

### API Endpoints
- GET `/api/users` - Retrieve users with pagination
- GET `/api/posts` - Retrieve posts with filtering options
- GET `/api/categories` - Retrieve categories
- GET `/api/tags` - Retrieve tags
- Various endpoints for database-driven content

### Functions to Implement
- Migration up() and down() methods for schema changes
- Seeder run() methods for data insertion
- Model factory definition methods
- Database connection testing methods
- Schema modification methods

### Features to Implement
- Database migration system implementation
- Foreign key constraint management
- Index creation for performance optimization
- Database seeding with realistic data
- Model factory creation for test data generation
- Soft deletes implementation
- Cascade delete operations
- Unique constraint enforcement

## Testing Your Migrations
1. Run migrations using artisan command
2. Verify tables were created in your database
3. Rollback migrations to test rollback functionality
4. Refresh and re-run migrations to test refresh functionality
5. Run seeders to populate database with test data
6. Verify foreign key relationships and constraints

## Conclusion
This project taught you how to manage your database schema using Laravel migrations. You learned to create, modify, and rollback database tables, implement relationships with foreign keys, and populate your database with seeders and factories. These skills are essential for maintaining a consistent database structure across different environments and development stages.