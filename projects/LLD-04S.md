# LLD-04S: Laravel Service Container and Dependency Injection

## Overview
This project focuses on Laravel's service container and dependency injection system. You'll learn how to manage class dependencies, create service bindings, and leverage the container for building maintainable applications.

## Learning Objectives
- Understand Laravel's service container and dependency injection
- Create service bindings for interfaces and implementations
- Implement singleton and scoped services
- Use service providers to register bindings
- Apply dependency injection in controllers and other classes

## Prerequisites
- Completed LLD-01 through LLD-03
- Understanding of PHP classes and constructors
- Basic knowledge of interfaces and implementations

## Steps to Complete the Task

### 1. Service Container Fundamentals
- Understand dependency injection concept and type-hinting
- Learn zero configuration resolution with reflection
- Identify when manual container configuration is needed
- Configure service container bindings for basic classes

### 2. Interface to Implementation Bindings
- Bind interfaces to concrete implementations
- Use service container for interface-based programming
- Implement payment gateway example with multiple providers
- Configure bindings in service providers

### 3. Singleton and Scoped Services
- Create singleton services for shared resources
- Implement scoped services for request lifecycle
- Understand the difference between singleton and regular bindings
- Configure appropriate service lifecycles

### 4. Service Provider Registration
- Create custom service providers
- Register bindings in the container
- Use conditional bindings (bindIf, singletonIf)
- Organize service registrations properly

### 5. Dependency Injection in Application Classes
- Inject dependencies in controllers
- Use dependency injection in middleware
- Apply dependency injection in event listeners and commands
- Configure service container for application services

## Specifications

### Database Requirements
None required for this project

### API Endpoints
None required for this project

### Core Features
1. Service container configuration
2. Interface implementation bindings
3. Singleton service management
4. Service provider implementation
5. Dependency injection in application classes

### Implementation Requirements
- Create at least 2 interfaces with multiple implementations
- Implement 3 different types of bindings (regular, singleton, scoped)
- Create 1 custom service provider
- Use dependency injection in at least 2 different application classes
- Demonstrate zero configuration resolution