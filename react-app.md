The React leaderboard app to Laravel controller! Here's what I've set up:
# 1. Updated CORS Configuration
Added `/leaderboard` to the allowed paths in `config/cors.php` so  React app can access the API

# 2. Fixed Data Format
Updated  PromotionController to convert the DTO objects to the snake_case format that  React app expects
The controller now returns data with properties like player_rank, player_id, username, etc.

# 3. Created Routes
`/leaderboard` - API endpoint that returns JSON data
`/leaderboard-app` - Serves the React app
# 4. Created React App View
Created `resources/views/leaderboard-app.blade.php` with  React code
Fixed the React syntax to work with the browser-based version
Changed the fetch URL to use a relative path (/leaderboard) to avoid CORS issues

# 5. Access the React app:
Go to `http://localhost:8000/leaderboard-app`
The app will automatically fetch data from /leaderboard
Test the API directly:
Go to http://localhost:8000/leaderboard to see the raw JSON data
# 6. Data Flow
 - React app makes a fetch request to /leaderboard
 - Laravel controller calls  PromotionService service class
 - The service executes the SQL query and returns DTO object
 - Controller converts DTOs to the format React expects
 - React displays the data at the leaderboard table
 
# The main app components

## Service class
`app/Service/PromotionService.php`  The service class contains the logic from the given parametrized SQL query from task #1. Yet, it does not return DB entities but a DTO.
 
## Data Transfer Object (DTO)
To have the service class to be "framework agnostic" we use DTOs. A DTO decouples the service's output from the database structure.
`app/DTOs/LeaderboardPlayerDTO.php`

## Controller
Retrieves data from the Service class (as DTOs) and forwards them to the React app as JSON.
`app/Http/PromotionController.php`