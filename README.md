# Laravel Multi-Tenant Authentication System

## Objective

This project demonstrates a simple multi-tenant authentication system using Laravel 9 and Laravel Sanctum.

## Tech Stack

- Laravel 9
- PHP 8.1+
- MySQL
- Laravel Sanctum

## Project Structure

- Master Database
    - clients
    - client_users

- Tenant Databases
    - IBM
    - HCL
    - INFOSYS

Each tenant database contains its own users table.

## Authentication Flow

1. User submits email and password.
2. System identifies the tenant using the client_users mapping table.
3. Reads tenant database configuration from the clients table.
4. Dynamically switches the database connection.
5. Authenticates the user.
6. Generates a Laravel Sanctum API token.
7. Returns the authenticated user with the token.

## APIs

### Login

POST `/api/login`

```json
{
    "email":"ibmuser@gmail.com",
    "password":"password"
}
```

### Logout

POST `/api/logout`

Authorization: Bearer Token

### Get Authenticated User

GET `/api/user`

Authorization: Bearer Token

## Setup

1. Clone repository

```bash
git clone <repository-url>
```

2. Install dependencies

```bash
composer install
```

3. Copy environment file

```bash
cp .env.example .env
```

4. Generate application key

```bash
php artisan key:generate
```

5. Create databases

- master_db
- ibm_db
- hcl_db
- infosys_db

6. Import the provided SQL files.

7. Configure `.env`.

8. Run the application.

```bash
php artisan serve
```

## Assumption

The assignment requires tenant identification using only email and password. Since no tenant identifier (subdomain, client code, or email domain) was provided, a `client_users` mapping table was introduced in the master database to map each email to its respective tenant before dynamically switching the database connection.