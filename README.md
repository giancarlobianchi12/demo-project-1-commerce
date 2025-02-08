# Demo Project - 1-commerce

This is a Laravel-based project that utilizes Sail to streamline the development environment setup.

## Installation

To get started with the project, follow these steps:

### 1. Clone the Repository

```bash
git clone https://github.com/giancarlobianchi12/demo-project-1-commerce
cd folder
```

### 2. Set Up the Environment

You can choose one of the two options below to set up the development environment:



Option 1: Using Sail
```
composer install \
vendor/bin/sail up -d \
vendor/bin/sail shell
```
Option 2: Using Docker Compose
```
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan test
```

Make sure to setup these credentiales in the .env file (for demo project purposes):
- MERCADOLIBRE_CLIENT_ID=4170687618702922
- MERCADOLIBRE_CLIENT_SECRET=qlnNIYC0vfEsmn8mCfCVLJxdm6Zrz94y
- MERCADOLIBRE_REDIRECT_URI=https://localhost:3030/starter
- MERCADOLIBRE_API_URI=https://api.mercadolibre.com


### 3. Additional Notes

Credentials are pre-configured in the .env.example file to simplify the testing process.

### 4. About the API

This API allows an admin user to manage multiple "client" type users. These clients can integrate with the MercadoLibre API to:

- Obtain the access token.
- Refresh the access token.
- Retrieve shipping orders.
- Use a webhook to change the status of orders.

### 5. How It Works

This is the Postman collection: https://www.postman.com/grey-flare-132319/workspace/test/collection/1702613-cfa28fc3-1e2e-46e1-8f43-6b7a3dfb8ead?action=share&creator=1702613&active-environment=1702613-e51c7c01-4781-465e-bec5-8fd7d58842ca

1. First, log in as an admin user (we created this user in a seeder) using the "Login" endpoint in Postman.
2. Create a new client user. Once logged in as an admin, use the "Create User" endpoint and select the "client" type.
Log in as the client user using the same endpoint as the admin.

3. Go to the browser and enter this URL to get the "code" from MercadoLibre. We'll use it to get the access token in the backend:

- URL: https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id=4170687618702922&redirect_uri=https://localhost:3030/starter

Use the following test credentials:

- Email: test_user_548505576@testuser.com
- Password: RYiBuF7Xx3
- If 2FA is required, use: 273320 (the last 6 digits of the user ID).
After logging in, you'll receive a code in the URL, e.g., TG-67a7aae2b403520001290778-1652276660.

4. Use the "Auth MercadoLibre" endpoint in Postman to complete the authentication by copying and pasting the code.

That's it! Now you are authenticated with MercadoLibre.

\
Artisan Commands:

I have created two artisan commands:

- Refresh Token: mercadolibre:refresh-access-token. MercadoLibre access tokens expire approximately every 6 hours, so this command should be run to refresh the token.
- Sync Orders: mercadolibre:sync-orders. When a seller receives a new sale, this command will retrieve the order and its shipment information.
Job:

\
We have a webhook handler. When MercadoLibre receives an update, such as a shipment status change or an order update, MercadoLibre will send us this information. I've added an example webhook in Postman to change the order status. Please check the "Change Shipment Status" endpoint in Postman.
