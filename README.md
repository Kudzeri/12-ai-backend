
# 12-ai-backend

## Project Overview
12-ai-backend is an API for a classified ads service similar to OLX. It allows users to create advertisements, manage categories (admin only), register and verify accounts via phone and email. The API uses Laravel, PHP 8, Sanctum for authentication, and Mailpit for email handling.

## Features
- User registration and login
- Email verification
- Phone verification via Twilio
- Advertisement creation, update, and deletion
- Category management (admin only)
- Authentication via Laravel Sanctum

## Technologies
- Laravel 10
- PHP 8
- Sanctum for API authentication
- Postman for API testing
- Mailpit for email testing
- Twilio for phone verification

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/12-ai-backend.git
   cd 12-ai-backend
   ```

2. **Install dependencies:**

   ```bash
   composer install
   ```

3. **Set up environment variables:**

   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Update the following variables in your `.env` file:

   ```env
   TWILIO_SID=your_twilio_sid
   TWILIO_TOKEN=your_twilio_token
   TWILIO_PHONE_NUMBER=your_twilio_phone_number

   MAIL_MAILER=log
   MAIL_HOST=127.0.0.1
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

4. **Generate an application key:**

   ```bash
   php artisan key:generate
   ```

5. **Run the database migrations and seed the database:**

   ```bash
   php artisan migrate --seed
   ```

6. **Start the local development server:**

   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Log in a user
- `POST /api/logout` - Log out a user
- `POST /api/email/verification-notification` - Resend email verification link
- `GET /api/email/verify/{id}/{hash}` - Verify email
- `POST /api/phone/verification-code` - Send phone verification code
- `POST /api/phone/verify` - Verify phone number

### Advertisements
- `POST /api/v1/advertisements` - Create a new advertisement
- `GET /api/v1/advertisements` - List all advertisements
- `GET /api/v1/advertisements/search` - Search advertisements
- `GET /api/v1/advertisements/{id}` - Get a single advertisement
- `PUT /api/v1/advertisements/{id}` - Update an advertisement
- `DELETE /api/v1/advertisements/{id}` - Delete an advertisement

### Categories
- `POST /api/v1/categories` - Create a new category
- `GET /api/v1/categories` - List all categories
- `GET /api/v1/categories/{id}` - Get a single category
- `PUT /api/v1/categories/{id}` - Update a category
- `DELETE /api/v1/categories/{id}` - Delete a category

## Postman Collection

Use Postman to interact with the API. You can import the Postman collection file located at:

```
/path/to/postman_collection.json
```

## Mailpit Configuration
Mailpit is used to handle email notifications. You can access the Mailpit dashboard at:

```
http://127.0.0.1:8025
```

## Database Seeding
You can populate the database with dummy data using the following command:

```bash
php artisan db:seed
```

## License
This project is open-source and available under the [MIT License](LICENSE).

---

Feel free to customize it further!
