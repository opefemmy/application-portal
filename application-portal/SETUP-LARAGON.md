# How to Run the Application Portal in Laragon

## Method 1: Quick Start (Recommended)

1. **Open Laragon**
   - Double-click the Laragon icon on your desktop or start menu

2. **Add the Project**
   - In Laragon, click: **Website** → **Add**
   - Browse to: `C:\Users\Dwealth\Documents\application-portal`
   - Click **OK**

3. **Start Everything**
   - Click the **Start All** button in Laragon
   - Laragon will automatically:
     - Start Apache/Nginx
     - Start MySQL
     - Configure PHP 8.3
     - Set up the database

4. **Access the Application**
   - Click the **Web** button in Laragon, OR
   - Open your browser and go to: `http://application-portal.test`

## Method 2: Manual Start (if Method 1 fails)

1. **Start MySQL first:**
   - Open XAMPP Control Panel or Laragon
   - Start MySQL

2. **Then run this file:**
   - Double-click: `start-server.bat`

3. **Access at:** `http://127.0.0.1:8000`

## Login Credentials

- **Admin URL:** `http://application-portal.test/admin/login`
- **Email:** `admin@portal.com`
- **Password:** `password123`

## Troubleshooting

If you get a 500 error:
1. Make sure MySQL is running
2. Make sure the database exists (run: `php artisan migrate`)
3. Clear cache: `php artisan config:clear`

## Support

For issues, check the log file:
`C:\Users\Dwealth\Documents\application-portal\storage\logs\laravel.log`