<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Practical

## Features

- User login register with Passport.
- Product CRUD with multiple category binding
- Category CRUD with parent child concept 

## Installation

Clone repository and run composer update command to install dependencies of laravel
```sh
composer update
```

After that run migration command to generate database structure
```sh
php artisan migrate
```

Then run seeders to generate dummy data for all modules
```sh
php artisan db:seed
```

You can access all API via postman export URL
[Postman URL](https://www.getpostman.com/collections/93dc975779acecfd613d).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
