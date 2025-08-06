# API Platform Auth System

This repository provides a flexible foundation for implementing authentication and authorization in modern API-driven projects. It is designed for extensibility, security, and ease of integration.

## ✨ Features

- **User Registration & Login**: Endpoints for secure user sign-up and authentication.
- **Token-Based Authentication**: Stateless authentication using tokens.
- **Role & Permission Management**: Flexible system for user roles and permissions.
- **RESTful APIs**: All endpoints follow REST conventions.
- **Extensible Design**: Easily add new authentication strategies or user fields.
- **Secure by Default**: Adheres to best practices for password and session management.
- **API Documentation**: Auto-generated docs for all endpoints.

## 🚀 Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

- PHP (compatible version)
- Composer
- A supported database (e.g., MySQL, PostgreSQL)
- Node.js and npm (optional, for frontend integrations)

### Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/Mohamed-Boutarbouch/api_platform_auth_system.git
    cd api_platform_auth_system
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Set up environment variables:**
    - Copy the example environment file and configure as needed:
        ```bash
        cp .env.example .env
        ```
    - Add your [Mailtrap](https://mailtrap.io/) credentials for email testing by setting the `MAILER_DSN` variable in your `.env` file:
        ```
        MAILER_DSN=smtp://<username>:<password>@smtp.mailtrap.io:2525
        ```
      Replace `<username>` and `<password>` with your Mailtrap credentials.

4. **Set up the database:**
    - Edit your `.env` file with your database configuration.
    - Run migrations:
        ```bash
        php bin/console doctrine:migrations:migrate
        ```

5. **Start the development server:**
    ```bash
    php -S localhost:8000 -t public
    ```

## 📚 Usage

- Access the API documentation at `/api/docs` after starting the server.
- Use the endpoints for registering, logging in, and managing roles and permissions.

## 🛠️ Customization

You can extend this system by:
- Adding new authentication providers (OAuth, SSO, etc.)
- Integrating with frontend clients
- Customizing user attributes and validations

## 🤝 Contributing

Contributions are welcome! Please open an issue or submit a pull request for suggestions, improvements, or bug fixes.
