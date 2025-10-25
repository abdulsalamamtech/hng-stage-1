<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## The Project

On this week HNG project I built a Laravel RESTful API service that analyzes strings and stores their computed properties.

This task validates my ability to manipulate string and query data based on users input.

I started by installing Laravel.

Creating the migration file, model, controller, and route endpoint.

Then creating string analyzer service class to separate the main logic to simplify the controller.

Then I created the seeder class to seed the database with dummy data and more...

I created a repository and push the project.

I deploy the project to railway.

Then I test the project to see how it works.

Thanks.

## Run the project locally

```sh
    # clone the project
    git clone https://github.com/abdulsalamamtech/HNGi13-backend-task

    # cd into the stage-0 project folder
    cd stage-0

    # install dependencies
    composer install

    # Seed the database
    php artisan migrate:fresh --seed

    # run the project
    php artisan serve

    # visit the endpoint
    https://localhost:8000/api/strings

```

###

The endpoints I built.

1. Create/Analyze String

    POST /strings
    Content-Type: application/json

Request Body:

Success Response (201 Created):

```json
{
    "data": [
        {
            "id": "73047f8d87a5268e01e44df3c11077db4983dd930f1993d58e6a87e021338ab0",
            "value": "Not a palindrome",
            "is_palindrome": false,
            "word_count": 3,
            "created_at": "2025-10-24T21:09:02.000000Z",
            "property": {
                "length": 16,
                "is_palindrome": false,
                "unique_char_count": 13,
                "word_count": 3,
                "sha256_hash": "73047f8d87a5268e01e44df3c11077db4983dd930f1993d58e6a87e021338ab0",
                "character_frequency_map": {
                    "N": 1,
                    "o": 2,
                    "t": 1,
                    " ": 2,
                    "a": 2,
                    "p": 1,
                    "l": 1,
                    "i": 1,
                    "n": 1,
                    "d": 1,
                    "r": 1,
                    "m": 1,
                    "e": 1
                }
            }
        },
        {
            "id": "1709305c9fa966d14f56f0da3c3614bbc10adcf53d72f90341bb96d24afcfca3",
            "value": "Level",
            "is_palindrome": false,
            "word_count": 1,
            "created_at": "2025-10-24T21:09:02.000000Z",
            "property": {
                "length": 5,
                "is_palindrome": false,
                "unique_char_count": 4,
                "word_count": 1,
                "sha256_hash": "1709305c9fa966d14f56f0da3c3614bbc10adcf53d72f90341bb96d24afcfca3",
                "character_frequency_map": { "L": 1, "e": 2, "v": 1, "l": 1 }
            }
        }
    ],
    "count": 12,
    "filters_applied": []
}
```

Error Responses:

    409 Conflict: String already exists in the system
    400 Bad Request: Invalid request body or missing "value" field
    422 Unprocessable Entity: Invalid data type for "value" (must be string)

2. Get Specific String
   GET /strings/{string_value}

-   string_value : hello world

Success Response (200 OK):

```json
{
    "id": "b94d27b9934d3e08a52e52d7da7dabfac484efe37a5380ee9088f7ace2efcde9",
    "value": "hello world",
    "properties": {
        "length": 11,
        "is_palindrome": false,
        "unique_char_count": 8,
        "word_count": 2,
        "sha256_hash": "b94d27b9934d3e08a52e52d7da7dabfac484efe37a5380ee9088f7ace2efcde9",
        "character_frequency_map": {
            "h": 1,
            "e": 1,
            "l": 3,
            "o": 2,
            " ": 1,
            "w": 1,
            "r": 1,
            "d": 1
        }
    },
    "created_at": "2025-10-24T21:09:02.000000Z"
}
```

Error Responses:

    404 Not Found: String does not exist in the system

3. Get All Strings with Filtering
   GET /strings?is_palindrome=true&min_length=5&max_length=20&word_count=2&contains_character=a

Query Parameters:

    is_palindrome: boolean (true/false)
    min_length: integer (minimum string length)
    max_length: integer (maximum string length)
    word_count: integer (exact word count)
    contains_character: string (single character to search for)
    Success Response (200 OK):

GET /strings?is_palindrome=true&min_length=1&max_length=11&word_count=1&contains_character=p

```json
{
    "data": [
        {
            "id": "0a6a15345ad0c7e36ed9dc3ec9c8ce843af4bc765d1019eb6d563e0836b962f3",
            "value": "PHP",
            "is_palindrome": true,
            "word_count": 1,
            "created_at": "2025-10-24T21:09:02.000000Z",
            "property": {
                "length": 3,
                "is_palindrome": true,
                "unique_char_count": 2,
                "word_count": 1,
                "sha256_hash": "0a6a15345ad0c7e36ed9dc3ec9c8ce843af4bc765d1019eb6d563e0836b962f3",
                "character_frequency_map": { "P": 2, "H": 1 }
            }
        }
    ],
    "count": 1,
    "filters_applied": {
        "is_palindrome": "true",
        "min_length": "1",
        "max_length": "11",
        "word_count": "1",
        "contains_character": "p"
    }
}
```

Error Response:

    404 Bad Request: Invalid query parameter values or types

```json
{
    "data": [],
    "count": 0,
    "filters_applied": {
        "is_palindrome": "true",
        "min_length": "10",
        "max_length": "11",
        "word_count": "1",
        "contains_character": "p"
    }
}
```

    400 Bad Request: Invalid query parameter values or types

4. Natural Language Filtering
   GET /strings/filter-by-natural-language?query=all%20word%with%20strings

    Success Response (200 OK):

```json
{
    "data": [
        {
            "id": "ab5d684a5cac66c6a7c902dda743fb642827b01b8bd4d9514d46834e4c425ff2",
            "value": "Laravel",
            "is_palindrome": false,
            "word_count": 1,
            "created_at": "2025-10-24T21:09:02.000000Z",
            "property": {
                "length": 7,
                "is_palindrome": false,
                "unique_char_count": 6,
                "word_count": 1,
                "sha256_hash": "ab5d684a5cac66c6a7c902dda743fb642827b01b8bd4d9514d46834e4c425ff2",
                "character_frequency_map": {
                    "L": 1,
                    "a": 2,
                    "r": 1,
                    "v": 1,
                    "e": 1,
                    "l": 1
                }
            }
        },
        {
            "id": "1709305c9fa966d14f56f0da3c3614bbc10adcf53d72f90341bb96d24afcfca3",
            "value": "Level",
            "is_palindrome": false,
            "word_count": 1,
            "created_at": "2025-10-24T21:09:02.000000Z",
            "property": {
                "length": 5,
                "is_palindrome": false,
                "unique_char_count": 4,
                "word_count": 1,
                "sha256_hash": "1709305c9fa966d14f56f0da3c3614bbc10adcf53d72f90341bb96d24afcfca3",
                "character_frequency_map": { "L": 1, "e": 2, "v": 1, "l": 1 }
            }
        }
    ],
    "count": 7,
    "interpreted_query": {
        "original": "all single word palindromic strings",
        "parsed_filters": { "word_count": 1 }
    }
}
```

5. Delete String
   DELETE /strings/{string_value}

Success Response (204 No Content): (Empty response body)Error Responses:

    404 Not Found: String does not exist in the system

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```

```
