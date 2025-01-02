# Project Setup Guide

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)

---

## Prerequisites

Before you begin, ensure you have the following software installed on your machine:

- [PHP](https://www.php.net/downloads) (version 8.0 or higher)
- [Composer](https://getcomposer.org/download/) (Dependency Manager for PHP)
- [Node.js](https://nodejs.org/en/download/) (version 14 or higher)
- [npm](https://www.npmjs.com/get-npm) or [yarn](https://yarnpkg.com/getting-started/install) (Node package managers)
- [MySQL](https://www.mysql.com/downloads/) (or your preferred database)

## Installation

### 1. Clone the Repository

Clone the project repository to your local machine:

```bash
git clone repo_link
```

### 2. Start the XAMPP Control Panel

#### In the XAMPP Control Panel, start the Apache and MySQL services:

Apache: Click the "Start" button next to Apache.
MySQL: Click the "Start" button next to MySQL.

#### Access phpMyAdmin:

Open a web browser and navigate to http://localhost/phpmyadmin/index.php

#### Create the Database:

In phpMyAdmin, click on the "Databases" tab.
Enter themelooks_product_management_db in the "Create database" field.
Click "Create."

### 3. Set Up the Backend (Laravel)

#### Goto the folder backend_laravel ( that is Laravel the backend part )

Open the backend folder and open terminal or cmd in current path (it will be shown this way example: ..\backend_laravel)

#### Install PHP Dependencies

Install the Laravel dependencies:

```bash
composer install
```

#### Set Up Environment Variables

Copy the .env.example file to create your .env file:

```bash
cp .env.example .env
```

Open the .env file and configure the database connection:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=themelooks_product_management_db
DB_USERNAME=root
DB_PASSWORD=
```

Also fix these (optional)

```bash
APP_TIMEZONE='Asia/Dhaka'
```

config >> database.php and setup this 
```bash

'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'themelooks_product_management_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        ],

```

Generate Application Key
Generate a new application key:

```bash
php artisan key:generate
```

Import the SQL File (Optional) or

Migrate and Seed the Database
Run the migrations and seeders to set up your database:

```bash
php artisan migrate:refresh --seed
```

### 3. Set Up the Frontend (Vue.js)

#### Install Node.js Dependencies

Navigate to the frontend_vue directory and install the dependencies:
open terminal or cmd in current path (it will be shown this way example: ..\frontend_vue)
and use the command

```bash
npm install
```

## Configuration

## Running the Application

### 1. Start the Laravel Development Server

From the backend folder using cmd or terminal

Start the Laravel server to serve the backend API:

```bash
php artisan serve
```

### 2. Start the Vue Development Server

Start the React development server from the frontend folder:

```bash
npm run dev
```

The Vue app should now be running at http://localhost:5173.

##### Finally run or test the project from http://localhost:5173 this address in a web browser.

## Reason for Partial Completion:
Although the additional time provided was appreciated, it was not sufficient to complete the entire project, especially considering I only began the task three days ago. Despite the time constraints, I made every effort to progress as much as possible. While some aspects of the task remain unfinished, I am confident that with more time, I would be able to fully complete it.



