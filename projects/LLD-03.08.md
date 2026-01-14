# LLD-03.08: Soft Deleted Models in Resource Controllers

## Overview
This project teaches you how to handle soft deleted models in Laravel resource controllers. You'll learn to configure resource routes to include soft deleted models using the withTrashed() method.

## Learning Objectives
- Use the withTrashed() method to include soft deleted models
- Configure resource routes to handle soft deleted records
- Determine which actions should access soft deleted models
- Implement proper authorization for soft deleted models

## Prerequisites
- Completed LLD-03 or have a working knowledge of resource controllers and soft deletes
- Understanding of Laravel's soft delete feature

## Implementation Outline

### Models to Implement
- Soft deletable User model
- Soft deletable Post model
- Soft deletable Product model
- Soft deletable Comment model

### Controllers to Implement
- TrashUserController for managing soft deleted users
- TrashPostController for managing soft deleted posts
- ArchiveProductController for archived products
- RecycleCommentController for recycled comments

### Routes to Implement
- Resource routes with withTrashed() method for all actions
- Resource routes with withTrashed() for specific actions
- API resource routes with soft delete support
- Individual routes with soft delete access

### Functions to Implement
- Soft delete restoration methods
- Trashed model retrieval methods
- Authorization checks for soft deleted models
- Bulk restoration functionality

### Features to Implement
- Basic withTrashed() method on resource routes
- Selective withTrashed() for specific actions
- Soft delete model access in resource controllers
- Restoration workflows for soft deleted models
- Authorization for accessing soft deleted records

## Testing Your Implementation
1. Set up models with soft deletes trait
2. Configure resource routes with withTrashed() method
3. Test access to soft deleted models via resource routes
4. Verify that only authorized actions can access trashed models
5. Test restoration of soft deleted models

## Conclusion
This project taught you how to handle soft deleted models in Laravel resource controllers. Using the withTrashed() method allows you to include soft deleted records in your resource routes, providing functionality for trash and archive features in your application.