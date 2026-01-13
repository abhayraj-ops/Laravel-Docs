# Request Lifecycle

## Simple Overview

Understanding Laravel's request lifecycle helps you become a more confident developer. This document explains how Laravel processes incoming requests from start to finish in a simple, visual way.

```mermaid
graph LR
    A[Browser Request] --> B[Laravel Processes] --> C[Browser Response]
```

## The Request Journey (6 Simple Steps)

### 1. Entry Point

**All requests start at `public/index.php`** - this is your application's single entry point.

```mermaid
graph TD
    A[User's Browser] -->|HTTP Request| B[public/index.php]
    B --> C[Load Composer Autoloader]
    C --> D[Create Laravel Application]
```

**What happens:**
1. Web server (Apache/Nginx) directs all requests to `public/index.php`
2. Composer autoloader loads all framework classes
3. Laravel application instance is created

### 2. HTTP Kernel

**The HTTP Kernel orchestrates everything** - it's like the traffic controller.

```mermaid
graph TD
    A[HTTP Kernel] --> B[Run Bootstrappers]
    A --> C[Process Middleware]
    B --> B1[Error Handling]
    B --> B2[Logging Setup]
    B --> B3[Environment Detection]
    C --> C1[Session Handling]
    C --> C2[Maintenance Check]
    C --> C3[CSRF Protection]
```

**What happens:**
1. Bootstrappers configure the environment
2. Middleware stack processes the request
3. Each middleware can modify or reject the request

### 3. Service Providers (Most Important!)

**Service Providers bootstrap Laravel's features** - they're the heart of Laravel.

```mermaid
graph TD
    A[Service Providers] --> B[Database Provider]
    A --> C[Queue Provider]
    A --> D[Routing Provider]
    A --> E[Your Custom Providers]
    B & C & D & E --> F[register method]
    F --> G[boot method]
```

**What happens:**
1. Laravel loads all service providers
2. Calls `register()` on each provider
3. Calls `boot()` on each provider
4. All Laravel features become available

### 4. Routing

**The Router matches requests to code** - like a traffic director.

```mermaid
graph TD
    A[Router] --> B[Find Matching Route]
    B --> C[Run Route Middleware]
    C --> D[Execute Controller/Route]
    D --> E[Generate Response]
```

**What happens:**
1. Router finds the matching route
2. Route-specific middleware runs
3. Controller method or route closure executes
4. Response is generated

### 5. Response Processing

**Middleware processes the response** - final checks before sending.

```mermaid
graph TD
    A[Controller Response] --> B[Outbound Middleware]
    B --> C[Modify Response if needed]
    C --> D[HTTP Kernel]
```

**What happens:**
1. Response travels back through middleware
2. Middleware can modify the response
3. Final response is prepared

### 6. Send to Browser

**Final step - send response to user**

```mermaid
graph TD
    A[HTTP Kernel] --> B[Application Instance]
    B --> C[User's Browser]
```

**What happens:**
1. HTTP Kernel returns response object to Application Instance
2. Application Instance calls `send()` method
3. Response is sent to User's Browser

## Simple Summary

```mermaid
flowchart TD
    A[Browser Request] --> B[public/index.php]
    B --> C[HTTP Kernel]
    C --> D[Service Providers]
    D --> E[Router]
    E --> F[Controller/Route]
    F --> G[Response]
    G --> H[Outbound Middleware]
    H --> I[Browser Response]
```

## Key Points to Remember

1. **Single Entry Point**: All requests go through `public/index.php`
2. **HTTP Kernel**: Manages bootstrapping and middleware
3. **Service Providers**: Most important - they bootstrap all Laravel features
4. **Routing**: Matches URLs to controller methods or routes
5. **Middleware**: Filters both incoming requests and outgoing responses
6. **Response Flow**: Response goes back through middleware before sending

## Visual Cheat Sheet

```mermaid
graph TD
    A[Laravel Request Lifecycle] --> B[Entry Point]
    A --> C[HTTP Kernel]
    A --> D[Service Providers]
    A --> E[Routing]
    A --> F[Response]
    
    B --> B1[public_index_php]
    B --> B2[Composer_Autoloader]
    B --> B3[Create_Application]
    
    C --> C1[Bootstrappers]
    C --> C2[Middleware_Stack]
    
    D --> D1[register_method]
    D --> D2[boot_method]
    D --> D3[All_Laravel_features]
    
    E --> E1[Match_URL_to_route]
    E --> E2[Run_route_middleware]
    E --> E3[Execute_controller]
    
    F --> F1[Generate_response]
    F --> F2[Process_outbound_middleware]
    F --> F3[Send_to_browser]
```

## Next Topic

[Service Container](6-service-container.md)