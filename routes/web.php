<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use app\Http\Livewire\LoanManager;
use app\Http\Livewire\LoanPaymentsViewer;
use App\Models\Loan;
use App\Http\Controllers\PromotionController;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});

// API endpoint for leaderboard data test
Route::get('/leaderboard', [PromotionController::class, 'getLeaderboard']);

// Serve the React leaderboard app
Route::get('/leaderboard-app', function () {
    return view('leaderboard-app');
});

// Route for Loan Management - Protected by 'auth' middleware
Route::middleware(['auth'])->group(function () {
    Route::get("/loans", function () {
        return view("loans.index", [
            'header' => "Loan Manager header",
            'slot' => "Random slot"
        ]);
    })->name("loans.index");

    // we protect the payments viewer route as well
    //Route::get("/loans/{loan}/payments", LoanPaymentsViewer::class);

    Route::get("/loans/{loan}/payments", function (Loan $loan) {
        return view("loans.payments",  compact("loan"));
    })->name("loans.payments.show");
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        return "Connected to: <b>" . DB::connection()->getDatabaseName() . "</b>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/phpinfo', function () {
    return phpinfo();
});

require __DIR__ . '/auth.php';
