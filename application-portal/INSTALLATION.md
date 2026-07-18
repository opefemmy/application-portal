# Online Application Portal - Installation Guide

## Prerequisites

- PHP 8.3 or higher
- Composer
- MySQL 5.7 or higher
- Web Server (Apache/Nginx)
- Node.js (for frontend assets)

## Installation Steps

### 1. Clone/Extract the Project

Extract the project files to your web directory.

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

### 4. Configure Database

Open `.env` and update the database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=application_portal
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database

```bash
php artisan db:seed
```

This will create:
- Default admin user: `admin@portal.com` / `password123`
- Default roles
- Email templates
- Settings

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 10. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to view the application.

---

## Admin Login

- URL: `/admin/login`
- Email: `admin@portal.com`
- Password: `password123`

---

## Features Overview

### Frontend (Public)
- Home Page with hero banner and features
- About, Requirements, How to Apply, FAQ, Contact pages
- Application form with multi-step wizard
- Document upload functionality
- Application tracking
- Acknowledgement slip with QR code

### Backend (Admin)
- Dashboard with statistics and charts
- Application management (view, filter, search, export)
- Bulk actions (status update, delete)
- Send shortlist/rejection emails
- Interview scheduling
- Settings configuration
- Role-based access control

---

## Configuration

### Email Settings
Configure SMTP in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Portal Settings
Access via Admin Panel > Settings:
- Portal name and institution name
- Contact information
- Portal open/close dates
- Maximum applications
- Upload size limits

---

## Security Features

- CSRF Protection
- SQL Injection Prevention (via Eloquent)
- XSS Protection
- Rate Limiting
- Secure File Storage
- Password Hashing (bcrypt)
- Audit Logging

---

## File Structure

```
application-portal/
├── app/
│   ├── Console/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   └── Mail/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── emails/
│   │   ├── frontend/
│   │   └── layouts/
├── routes/
└── storage/
```

---

## Troubleshooting

### File Upload Issues
Ensure storage directory has proper permissions:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Database Connection Issues
- Verify MySQL is running
- Check credentials in `.env`
- Create database: `CREATE DATABASE application_portal;`

### Permission Issues
```bash
chmod -R 755 /path/to/application
chown -R www-data:www-data /path/to/application
```

---

## Support

For issues or questions, please contact the development team.

---

## License

This project is proprietary software. All rights reserved.