# CMS Headless

## Description

CMS Headless is a content management system that allows you to manage and deliver content via APIs. This project is built using PHP, Symfony, and Doctrine ORM.

## Features

- Import CSV files
- Manage content entities
- API endpoints for content management
- User authentication and authorization

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/your-username/cms-headless.git
    cd cms-headless
    ```

2. Install dependencies using Composer:
    ```bash
    composer install
    ```

3. Set up the environment variables:
    ```bash
    cp .env.example .env
    ```

4. Update the `.env` file with your database configuration.

5. Run the database migrations:
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

6. Start the Symfony server:
    ```bash
    symfony server:start
    ```

## API Documentation

The API documentation is available at:
  ```bash
  http://127.0.0.1:8000/api/docs
  ```
