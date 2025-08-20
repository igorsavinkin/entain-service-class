# Laravel Loan Management and Amortization Application

This document provides a comprehensive guide to setting up, configuring, and using the Laravel Loan Management and Amortization Application. It covers installation, database setup, user authentication, and the core functionalities of managing loans and viewing amortization schedules.

## Table of Contents

1.  [Introduction](#1-introduction)
2.  [Features](#2-features)
3.  [System Requirements](#3-system-requirements)
4.  [Installation Guide](#4-installation-guide)
    *   [Cloning the Repository](#41-cloning-the-repository)
    *   [Composer Dependencies](#42-composer-dependencies)
    *   [Environment Configuration](#43-environment-configuration)
    *   [Database Setup](#44-database-setup)
    *   [Frontend Dependencies](#45-frontend-dependencies)
    *   [Running Migrations and Seeders](#46-running-migrations-and-seeders)
5.  [User Authentication](#5-user-authentication)
6.  [Core Functionalities](#6-core-functionalities)
    *   [Loan Management (CRUD)](#61-loan-management-crud)
    *   [Viewing Loan Payments](#62-viewing-loan-payments)
7.  [Troubleshooting Common Issues](#7-troubleshooting-common-issues)
    *   [PHP Version Incompatibility](#71-php-version-incompatibility)
    *   [Livewire Component Not Found](#72-livewire-component-not-found)
    *   [Undefined Variable: Header](#73-undefined-variable-header)
    *   [Target Class [Loan] Does Not Exist](#74-target-class-loan-does-not-exist)
    *   [Empty Screen on Payments Page](#75-empty-screen-on-payments-page)
8.  [Development Tools](#8-development-tools)
9.  [Contributing](#9-contributing)
10. [License](#10-license)

## 1. Introduction

This Laravel application is designed to manage loans and generate amortization schedules. It provides a user-friendly interface for creating, viewing, updating, and deleting loan records, as well as a detailed breakdown of loan payments over time. The application leverages Laravel's robust framework and Livewire for dynamic frontend interactions.

## 2. Features

*   **User Authentication:** Secure user registration and login powered by Laravel Breeze.
*   **Loan Management (CRUD):** Full Create, Read, Update, and Delete operations for loan records.
*   **Amortization Schedule Generation:** Automatically calculates and displays detailed payment schedules for each loan.
*   **Dynamic UI:** Utilizes Livewire for a reactive and interactive user experience without extensive JavaScript.
*   **Database Integration:** Stores loan and payment data persistently.

## 3. System Requirements

Before you begin, ensure your development environment meets the following requirements:

*   **PHP:** Version 8.1 or higher (PHP 8.2 recommended).
*   **Composer:** Latest version.
*   **Node.js & npm (or Yarn):** Latest LTS version.
*   **Database:** MySQL, PostgreSQL, SQLite, or SQL Server.
*   **Web Server:** Nginx or Apache (Laravel Valet, Laragon, XAMPP, WAMP are also suitable).

## 4. Installation Guide

Follow these steps to get the application up and running on your local machine.

### 4.1. Cloning the Repository

First, clone the application repository to your local machine:

```bash
git clone https://github.com/igorsavinkin/loan-management-app.git
cd loan-management-app
```

### 4.2. Composer Dependencies

Install the backend PHP dependencies using Composer:

```bash
composer install
```

### 4.3. Environment Configuration

Create a copy of the `.env.example` file and name it `.env`:

```bash
cp .env.example .env
```

Generate a new application key:

```bash
php artisan key:generate
```

Open the `.env` file and configure your database connection details (for PostgreSQL):

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Replace `your_database_name`, `your_database_user`, and `your_database_password` with your actual database credentials.

### 4.4. Database Setup

Ensure your database server is running and you have created the database specified in your `.env` file.

### 4.5. Frontend Dependencies

Install the frontend Node.js dependencies and compile the assets:

```bash
npm install
npm run dev
```

If you prefer Yarn, use:

```bash
yarn install
yarn dev
```

### 4.6. Running Migrations and Seeders

Run the database migrations to create the necessary tables. If you have seeders (e.g., for initial users or test data), you can run them as well:

```bash
php artisan migrate --seed
```

## 5. User Authentication

This application uses Laravel Breeze for user authentication. After installation, you will have routes for registration, login, password reset, etc.

To access the protected areas of the application (like loan management), you will need to register and log in.

*   **Register:** Navigate to `/register` to create a new user account.
*   **Login:** Navigate to `/login` to log in with an existing account.

## 6. Core Functionalities

### 6.1. Loan Management (CRUD)

Once logged in, you can access the loan management dashboard:

*   **Access:** Navigate to `/loans`.
*   **Create Loan:** Use the form on the dashboard to input new loan details (Principal Amount, Interest Rate, Loan Term, Start Date).
*   **View Loans:** All existing loans will be listed in a table.
*   **Edit/Delete Loans:** (These functionalities are implemented in `LoanManager` Livewire component).

### 6.2. Viewing Loan Payments

To view the detailed amortization schedule for a specific loan:

*   **Access:** From the loan management dashboard (`/loans`), click on the "Schedule" or similar link associated with a specific loan.
*   **URL Structure:** The URL will typically be in the format `/loans/{loan_id}/payments` (e.g., `http://127.0.0.1:8000/loans/1/payments` for loan with ID 1).
*   **Details:** This page will display a table with payment dates, principal and interest components, total payment, and remaining balance for each installment.

## 7. Troubleshooting Common Issues

This section addresses common problems encountered during development and provides solutions based on our troubleshooting experience.

### 7.1. PHP Version Incompatibility

**Problem:** Errors like `Undefined variable: header` or other unexpected behaviors when using Laravel 9/10 with PHP 7.4.

**Solution:** Laravel 9+ requires PHP 8.0 or higher. Ensure your PHP version is 8.1 or higher. If using Laragon, select the correct PHP version in its menu and restart all services. If using a direct PHP installation, ensure your system's PATH environment variable prioritizes the correct PHP 8.x executable, or explicitly use the PHP 8.x executable when running `php artisan serve`.

### 7.2. Livewire Component Not Found

**Problem:** Error message `App\Http\Livewire\YourComponent was not found`.

**Solution:** Livewire components are typically in the `App\Livewire` namespace. Ensure your `use` statements in `routes/web.php` and the `namespace` declaration within your Livewire component files (e.g., `LoanManager.php`, `LoanPaymentsViewer.php`) correctly reflect `App\Livewire` (or `App\Http\Livewire` if you explicitly moved them there).

### 7.3. Undefined Variable: Header

**Problem:** `ErrorException: Undefined variable: header` in `resources/views/layouts/app.blade.php`.

**Solution:** This error occurs when a view extending `layouts.app` (like `loans.index`) does not provide content for the `header` slot. Ensure your view uses `<x-app-layout>` and includes a `<x-slot name="header">` section. For example:

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Your Page Title") }}
        </h2>
    </x-slot>
    <!-- Page content here -->
</x-app-layout>
```

### 7.4. Target Class [Loan] Does Not Exist

**Problem:** `Illuminate\Contracts\Container\BindingResolutionException: Target class [Loan] does not exist` when using route model binding (e.g., `/loans/{loan}/payments`).

**Solution:** This means the `Loan` model class is not correctly imported in the file where it's being used (e.g., `routes/web.php`). Add `use App\Models\Loan;` at the top of your `routes/web.php` file.

### 7.5. Empty Screen on Payments Page

**Problem:** Navigating to `/loans/{id}/payments` results in an empty screen.

**Solution:** Ensure `resources/views/loans/payments.blade.php` correctly extends `layouts.app` and renders the `LoanPaymentsViewer` Livewire component, passing the `loan` object to it. For example:

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Loan Payments for Loan #") }}{{ $loan->id }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @livewire("loan-payments-viewer", ["loan" => $loan])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

Also, ensure `resources/views/livewire/loan-payments-viewer.blade.php` contains the actual HTML structure to display the payments data.

## 8. Development Tools

This project is developed using standard Laravel practices. You'll need the following tools at your Linux/WSL machine:

*   **Composer:** For managing PHP dependencies.
*   **npm (or Yarn):** For managing frontend JavaScript dependencies.

 While any modern IDE can be used, tools like **Cursor.com AI code editor** can enhance the development experience through AI-assisted coding and vibe-coding with conventional coding. Other recommended tools include:

*   **PHPStorm:** A powerful IDE specifically designed for PHP development.
*   **VS Code:** A lightweight yet powerful source code editor with extensive extensions for Laravel, PHP, and Livewire development.


## 9. Contributing

Contributions are welcome! If you find a bug or have a feature request, please open an issue on the GitHub repository. If you'd like to contribute code, please fork the repository and submit a pull request.

## 10. License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Author:**  Igor Savinkin, https://webscraping.pro