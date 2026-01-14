# LLD-02.03: Nested Route Groups in Laravel

## Overview
This project teaches you how to implement nested route groups in Laravel, allowing you to combine multiple route attributes like middleware, prefixes, and namespaces in a hierarchical structure. You'll learn how nested groups intelligently merge attributes with their parent groups.

## Learning Objectives
- Create nested route groups with multiple attribute layers
- Understand how attributes are merged in nested groups
- Apply different middleware combinations using nesting
- Use nested groups for complex route organization

## Prerequisites
- Completed LLD-02 or have a working knowledge of route groups and middleware
- Understanding of route prefixes and namespaces

## Implementation Outline

### Routes to Implement
- Nested group with parent middleware and child-specific middleware
- Nested group with parent prefix and child prefix extension
- Nested group combining middleware, prefix, and namespace
- Deeply nested groups (3+ levels) for complex scenarios

### Controllers to Implement
- NestedGroupDemoController for demonstrating nested functionality
- AdminApiV1Controller for nested API versioning example
- MultiLayerController for deep nesting examples

### Functions to Implement
- Nested group registration methods
- Attribute merging logic demonstration
- Middleware combination functions
- Prefix appending functions

### Features to Implement
- Basic nested route groups
- Middleware inheritance in nested groups
- Prefix combination in nested groups
- Namespace inheritance in nested groups
- Complex nested group scenarios

## Testing Your Implementation
1. Create nested groups with different attribute combinations
2. Verify that middleware from parent groups applies to nested routes
3. Test that prefixes are properly combined in nested groups
4. Ensure namespaces are inherited correctly in nested groups
5. Verify that route names are properly prefixed in nested groups

## Conclusion
This project taught you how to implement nested route groups in Laravel, allowing for more sophisticated route organization. Nested groups help you combine multiple route attributes efficiently while maintaining clean and maintainable route definitions.