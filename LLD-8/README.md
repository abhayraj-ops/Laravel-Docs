# LLD-08: Laravel Eloquent ORM

## Overview
This project focuses on Laravel's Eloquent ORM (Object-Relational Mapping), which provides an elegant and simple ActiveRecord implementation for working with databases. You'll learn how to create models, define relationships, query data, and use Eloquent's powerful features.

## Learning Objectives
- Create and configure Eloquent models
- Query data using Eloquent
- Define and use model relationships
- Implement model scopes and accessors
- Use Eloquent collections and mutators

## Prerequisites
- Completed LLD-01 through LLD-07
- Understanding of database migrations
- Basic knowledge of SQL queries

## Implementation Outline

### Database Schema
- Users table with fields: id, name, email, password, email_verified_at, remember_token, timestamps
- Articles table with fields: id, title, content, excerpt, slug, published, published_at, user_id, category_id, timestamps
- Categories table with fields: id, name, slug, description, timestamps
- Comments table with fields: id, content, approved, user_id, article_id, parent_id, timestamps
- Tags table with fields: id, name, slug, timestamps
- Article-tag pivot table with fields: id, article_id, tag_id, timestamps

### Tables
- users table for user accounts
- articles table for blog posts/articles
- categories table for content categorization
- comments table for user comments
- tags table for content tagging
- article_tag table for many-to-many relationship between articles and tags

### Models to Implement
- User model with authentication features and relationships
- Article model with content management features
- Category model for content categorization
- Comment model for user comments
- Tag model for content tagging
- Model configurations with fillable fields, casts, dates, hidden fields

### Relationships to Implement
- One-to-many: User has many Articles
- One-to-many: User has many Comments
- One-to-many: Category has many Articles
- One-to-many: Article has many Comments
- One-to-many: Comment has many Replies (self-referencing)
- Belongs-to: Article belongs to User
- Belongs-to: Article belongs to Category
- Belongs-to: Comment belongs to User
- Belongs-to: Comment belongs to Parent Comment (self-referencing)
- Many-to-many: Article belongs to many Tags (with article_tag pivot table)
- Many-to-many: User belongs to many Roles

### Query Methods to Implement
- Basic queries: all(), find(), first(), findOrFail()
- Conditional queries: where(), whereIn(), whereBetween(), whereNull()
- Ordering and limiting: orderBy(), take(), skip()
- Aggregations: count(), sum(), avg(), max(), min()
- Pagination: paginate()
- Joins and complex queries
- Eager loading with with() method

### Scopes to Implement
- Global scopes for common filters (e.g., published articles)
- Local scopes for reusable query constraints
- Published scope for filtering published content
- Recent scope for ordering by creation date
- Search scope for text-based searching
- User-specific scopes

### Accessors and Mutators
- Attribute accessors for data formatting when retrieving
- Attribute mutators for data formatting when setting
- Excerpt accessor for generating content excerpts
- Title accessor for formatting titles
- Slug mutator for generating URL-friendly slugs

### Model Events and Observers
- Creating event for automatic slug generation
- Updating event for slug updates when title changes
- Deleting event for cleanup operations
- Observer classes for handling multiple model events
- Event registration in service providers

### Collections Features
- Collection filtering methods
- Collection mapping and transformation
- Grouping and sorting collections
- Plucking specific values from collections

### API Endpoints
- GET `/api/articles` - Retrieve articles with relationships
- POST `/api/articles` - Create new articles
- GET `/api/articles/{id}` - Retrieve specific article
- PUT `/api/articles/{id}` - Update specific article
- DELETE `/api/articles/{id}` - Delete specific article
- GET `/api/users/{id}/articles` - Retrieve user's articles
- GET `/api/categories/{id}/articles` - Retrieve category's articles

### Functions to Implement
- Model relationship methods
- Scope methods (local and global)
- Accessor and mutator methods
- Model event handlers
- Observer methods
- Query builder methods
- Collection manipulation methods

### Features to Implement
- Eloquent model creation and configuration
- Database relationship implementation
- Query optimization with eager loading
- Model scopes for reusable queries
- Attribute accessors and mutators
- Model events and observers
- Eloquent collections usage
- Mass assignment protection
- Attribute casting and type conversion
- Soft deletes implementation

## Testing Your Eloquent Implementation
1. Create models and run migrations
2. Test basic CRUD operations
3. Verify relationships work correctly
4. Test scopes and accessors
5. Check that events and observers function properly
6. Verify eager loading prevents N+1 queries
7. Test collection methods

## Conclusion
This project taught you how to work with Laravel's Eloquent ORM. You learned to create models, define relationships, query data efficiently, use scopes and accessors, and implement advanced features like model events and observers. Eloquent provides a powerful and intuitive way to interact with your database using object-oriented syntax.