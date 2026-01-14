# LLD-03.06: Partial Resource Routes in Laravel

## Overview
This project teaches you how to create partial resource routes in Laravel, allowing you to specify only a subset of the default resource actions. You'll learn to use only() and except() methods to customize your resource routes.

## Learning Objectives
- Use the only() method to specify specific resource actions
- Use the except() method to exclude specific resource actions
- Determine when to use partial resource routes
- Implement controllers with only the required methods

## Prerequisites
- Completed LLD-03 or have a working knowledge of resource controllers
- Understanding of RESTful routing concepts

## Implementation Outline

### Controllers to Implement
- ReadOnlyController with only index and show methods
- DataEntryController with only create and store methods
- StatusUpdateController with only show, edit, and update methods
- ArchiveController with only index, show, and destroy methods

### Routes to Implement
- Resource routes with only() method specifying allowed actions
- Resource routes with except() method excluding specific actions
- Multiple partial resource routes in a single application
- API partial resource routes

### Functions to Implement
- Selected resource methods based on route constraints
- Error handling for disabled resource actions
- Route validation for partial resources
- Method existence checking

### Features to Implement
- Basic partial resource route definition with only()
- Partial resource routes with except() method
- Controller method alignment with partial routes
- Route list verification for partial resources
- Error handling for unavailable actions

## Testing Your Implementation
1. Define resource routes using only() method with specific actions
2. Define resource routes using except() method to exclude actions
3. Create controllers with only the required methods
4. Verify that unauthorized routes return 404 errors
5. Test that allowed routes function correctly

## Conclusion
This project taught you how to create partial resource routes in Laravel, allowing you to specify only the resource actions you need. This helps create cleaner route definitions and prevents unwanted access to certain resource operations.