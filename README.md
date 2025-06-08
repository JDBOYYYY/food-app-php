# FoodApp - Food Delivery Platform

This is a full-stack food delivery application built with a Laravel 11 backend API and a Vue 3 frontend. The entire development environment is containerized using Docker for easy setup and consistency.

## Key Technologies

-   **Backend:** Laravel 11 (PHP 8.2)
-   **Frontend:** Vue 3 (Composition API), Vite, Pinia, Vue Router
-   **Styling:** Tailwind CSS
-   **Database:** Microsoft SQL Server
-   **Environment:** Docker & Docker Compose

## Prerequisites

Before you begin, ensure you have the following installed on your system:
-   [Docker](https://www.docker.com/products/docker-desktop/)
-   [Docker Compose](https://docs.docker.com/compose/install/)
-   A code editor (e.g., Visual Studio Code)
-   A terminal or command-line interface
-   A database management tool that can connect to MS SQL Server (e.g., [Azure Data Studio](https://azure.microsoft.com/en-us/products/data-studio) or [DBeaver](https://dbeaver.io/))

## Getting Started: Installation & Setup

Follow these steps to get the application running locally.

### 1. Clone the Repository

First, clone this repository to your local machine.

```bash
git clone 
cd foodapp-php
```

### 2. Configure Environment Variables

The application uses a `.env` file for configuration. A `docker-compose.yml` file is also included which relies on these variables.

1.  Create a `.env` file by copying the example file:
    ```bash
    cp .env.example .env
    ```

### 3. Build and Start the Containers

Run the following command to build the Docker images and start the application containers in the background.

```bash
docker compose up -d --build
```

This will start two services: `laravel_app` (the web application) and `db` (the MS SQL Server database).

### 4. Create the Database (First Time Only)

**ZA PIERWSZYM RAZEM** (ON THE FIRST RUN), the database container will start, but the `FoodApiDb` database required by the application will not exist yet. You must create it manually.

1.  Open your database management tool (e.g., Azure Data Studio).
2.  Create a new connection with the following details:
    -   **Server:** `localhost`
    -   **Port:** `1433`
    -   **Authentication type:** SQL Login
    -   **User:** `sa`
    -   **Password:** The password you set for `SA_PASSWORD` in your `.env` file.
3.  Connect to the server.
4.  Open a new query window and run the following SQL command to create the database:
    ```sql
    CREATE DATABASE FoodApiDb;
    ```

### 5. Run Database Migrations & Seeding

Now that the database exists, you need to create the application's tables and populate them with initial data (users, restaurants, products, etc.).

1.  Enter the `laravel_app` container's shell:
    ```bash
    docker compose exec laravel_app bash
    ```
2.  Inside the container, run the Laravel `migrate` and `seed` command:
    ```bash
    php artisan migrate --seed
    ```
    This will set up the entire database schema and add all the necessary sample data.

### 6. Install Frontend Dependencies

The final step is to install the Node.js packages required for the Vue.js frontend.
```bash
npm install
```

## Running the Application

You're all set! The application is now running.
```bash
npm run dev
```

-   **Frontend & Backend:** Open your web browser and navigate to:
    [**http://localhost:8000**](http://localhost:8000)

The Laravel application serves the Vue frontend, and the Vite server provides Hot Module Replacement (HMR) for a smooth development experience. Any changes you make to the Vue components will be reflected instantly in your browser.