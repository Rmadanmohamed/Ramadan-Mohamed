# Laravel Blog — Coursework / Application Prototype

A Laravel 10 application containing post routes, authentication scaffolding, migrations, Blade views, and PHPUnit test scaffolding. The repository name is historical; this is **not** the GitHub profile README.

## Technology

- PHP 8.1 or later
- Laravel 10, Laravel UI
- Composer, npm, Vite
- Database driver configured through Laravel environment settings

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
```

Configure the database in `.env`, create the database if necessary, and then run:

```bash
php artisan migrate
php artisan serve
```

In another terminal, run `npm run dev` for frontend assets. Do not commit the generated `.env` file or an application key.

## Existing routes

The `routes/web.php` file registers a home view, `/about` through `PostController@show`, resource routes at `/posts`, and Laravel UI authentication routes. The auth registration currently appears twice; this should be removed after confirming intended route behavior.

## Known issues

- The previous README contained unresolved Git merge conflict markers and unrelated Laravel framework promotional text.
- The application has not been executed or tested during this audit.
- Review route authorization, input validation, database setup, and migration status before deployment.

## Verification

```bash
php artisan route:list
php artisan test
npm run build
```

These are recommended checks, **not** claims of passing tests.

## License

The Composer project declares MIT; verify any existing repository license and third-party attribution before redistribution.
