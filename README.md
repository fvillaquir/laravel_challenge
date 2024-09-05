# Laravel Testing Project

This Laravel project is designed to test development tools and strategies such as query caching, job execution for sending emails, and more. It includes various API endpoints for testing and requires proper configuration and migration setup before running.

## Requirements

- PHP >= 7.2.5
- Composer
- Laravel >= 7.29
- MySQL or any other compatible database
- Redis (optional, for caching)
## Installation

Clone the repository:

```git clone https://github.com/fvillaquir/laravel_testing.git```

```cd your-laravel-project ```

Install dependencies:

```composer install ```

Create and configure the .env file:

Copy the .env.example file to .env:

```cp .env.example .env ```

Then, update the .env file with your database and email service credentials.

Run migrations:

```php artisan migrate ```

Seed the database (if applicable):

```php artisan db:seed```

Start the development server:

```php artisan serve ```

## Features

- Query Caching: Implements caching strategies to optimize database queries.
- Job Execution: Sends emails asynchronously using Laravel’s job system.
- API Endpoints: Provides various endpoints for testing API-based functionality.
- Automated Tests: Implements a simple version of laravel test factory.
## API Endpoints

- /api/compra: Generates a mocked store order.
- /api/orden/{$orden}: Retrieves order information.
You can test the API routes using a tool like Postman or cURL.

## Running Jobs

Ensure that the queue worker is running to handle the email jobs:

```php artisan queue:work```

## Contributing

Feel free to fork the repository and submit pull requests. For major changes, please open an issue to discuss what you would like to change.

## License

This project is open-source and available under the MIT license.
