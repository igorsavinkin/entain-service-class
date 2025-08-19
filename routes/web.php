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


Route::get('/', function () {
    return view('welcome');
});

// Route for Loan Management - Protected by 'auth' middleware
Route::middleware(['auth'])->group(function () {
    Route::get("/loans", function () {
        return view("loans.index", [
            'header' => "Loan Manager header"  , 
            'slot' => "Random slot"
        ]);
    })->name("loans.index");

    // You might also want to protect the payments viewer route
    Route::get("/loans/{loan}/payments", function (Loan $loan) {
        return view("loans.payments", compact("loan"));
    })->name("loans.payments.show");
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/phpinfo', function () {
    return phpinfo();
});

require __DIR__.'/auth.php';
