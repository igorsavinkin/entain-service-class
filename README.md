# Laravel Service Class + React UI    

## Table of Contents
- [Introduction](#introduction)
- [Requirements](#requirements)
- [Setup Instructions](#setup-instructions)
- [Main Components](#main-components)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (React)](#frontend-react)
- [Data Flow](#data-flow)
- [Access Points](#access-points)
- [Development Notes](#development-notes)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Introduction

This project demonstrates a seamless integration between a Laravel backend and a React frontend to display a promotional leaderboard. The application features a service-oriented architecture with Data Transfer Objects (DTOs) for clean separation of concepts as well as caching for optimised data transfer.

## Requirements

- **PHP**: 8.2.* (strict)  
  - The project already uses dependencies that require PHP 8.2   
  - Performance: PHP 8.2 is faster and safer
  - Avoid problems with PHP 8.3/8.4 that may have breaking changes
  - Compatibility: Avoid version conflicts 
- **Node.js**: 14.x or higher
- **Composer**: For PHP dependencies
- **NPM**: For JavaScript dependencies
- **Database**: PostgreSQL

## Setup Instructions

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone https://github.com/igorsavinkin/entain-service-class.git
cd entain-service-class

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your PostgreSQL database in the .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed with sample data (if available)
php artisan db:seed
```

### 4. Build and Compile Assets

```bash
# Compile assets for development
npm run dev

# Or watch for changes during development
npm run watch

# For production
npm run prod
```

### 5. Start the Application

```bash
# Start the Laravel development server
php artisan serve

# Access the application at http://localhost:8000
```

## Main Components

### Backend (Laravel)

#### 1. Service Class
**Location**: `app/Services/PromotionService.php`

The service class **contains the core business logic** for calculating leaderboard rankings. It:
- Executes the parameterized SQL query from the task #1.
- Returns DTOs instead of database entities
- Performs data caching to optimize the operation in case of high data traffic
- Is framework-agnostic for better testability

 Promotion Service - Usage Examples

**Basic Usage, Getting Leaderboard Data**
```php
use App\Services\PromotionService;
use Carbon\Carbon;

$service = new PromotionService();
$startDate = Carbon::parse('2025-06-18');
$endDate = Carbon::parse('2025-06-25');
$bonusPoints = 500;

try {
    $leaderboard = $service->getLeaderboard($startDate, $endDate, $bonusPoints);
    
    foreach ($leaderboard as $player) {
        echo "Rank: {$player->rank}, Player: {$player->username}, Score: {$player->performanceScore}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

The service class properly **handles exceptions**.
 - Basic exception handling: there is a `try/catch` block
 - Error logging: `Log::error()` is used.
 - Error callback: the `OnFailure()` mechanism is implemented.
 - Re-throwing an exception: the exception is not "swallowed"

**Custom Error Handling with Callbacks**
```php
use App\Services\PromotionService;
use Carbon\Carbon;

$service = new PromotionService();

// Register custom error handler
$service->onFailure(function ($exception) {
    // Send to error tracking service
    Sentry::captureException($exception);
    
    // Log custom message
    Log::error('Promotion service failed', [
        'error' => $exception->getMessage(),
        'time' => now()
    ]);
});

try {
    $leaderboard = $service->getLeaderboard(
        Carbon::now()->subWeek(),
        Carbon::now(),
        500
    );
} catch (Exception $e) {
    // Custom callback will be executed automatically
}
```


#### 2. Data Transfer Object (DTO)
**Location**: `app/DTOs/LeaderboardPlayerDTO.php`

The DTO decouples the service's output from the database structure, providing:
- A consistent data structure
- Type safety
- Easy serialization for API responses

#### 3. Controller
**Location**: `app/Http/Controllers/PromotionController.php`

The controller handles HTTP requests and:
- Retrieves data from the Service class as DTOs
- Converts DTOs to JSON format for the React app
- Handles errors and exceptions
- It facilitates pagination to optimize the data loading so that users don’t have to wait for all data to come before page is shown to them

#### 4. Routes
**Location**: `routes/web.php` &  `routes/api.php`

Defines the application endpoints:
- `/api/leaderboard`: API endpoint that returns JSON data
- `/leaderboard-app`: Serves the React application

#### 5. CORS Configuration
**Location**: `config/cors.php`

Configures Cross-Origin Resource Sharing to allow the React app to access the API.

### Frontend (React)

#### 1. React Components
**Location**: `resources/js/components/`

The React components are integrated directly into the Laravel application using Laravel Mix:
- Leaderboard table component
- Loading states and error handling
- Refresh functionality

#### 2. Blade Template
**Location**: `resources/views/leaderboard-app.blade.php`

Serves as the entry point for the React application, including:
- The HTML structure
- Script and style references
- The root DOM element for React

## Data Flow

1. React app makes a fetch request to `/leaderboard`
2. Laravel controller calls the **PromotionService** service class
3. The service executes the SQL query and returns DTO objects or return cached data (for optimisation)
4. Controller converts DTOs to JSON format
5. React receives and displays the data in the leaderboard table

## Access Points

- **React App**: http://localhost:8000/leaderboard-app
- **API Endpoint**: http://localhost:8000/leaderboard (returns raw JSON data)

## Development Notes

- Use `npm run watch` during development to automatically recompile assets
- The React components are integrated using Laravel Mix, not Create React App
- API calls use relative paths to avoid CORS issues in production
- The service class can be easily tested in isolation due to DTO usage

## Troubleshooting

If you encounter issues:

1. Ensure all dependencies are installed
2. Check that your database is properly configured
3. Verify that the CORS configuration includes your React app's URL
4. Check the browser console for JavaScript errors
5. Review Laravel logs for server-side issues

## License

This project is a proprietary software owned by Igor Savinkin, [Webscraping.pro](https://webscraping.pro).
