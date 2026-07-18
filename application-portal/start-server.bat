@echo off
echo Starting Application Portal...
echo.

REM Check if MySQL is running
netstat -an | findstr ":3306" > nul
if %errorlevel% neq 0 (
    echo ERROR: MySQL is not running!
    echo Please start MySQL in XAMPP Control Panel or Laragon
    echo.
    pause
    exit /b 1
)

REM Start PHP 8.3 with Laravel
cd /d C:\Users\Dwealth\Documents\application\application-portal
set PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;%PATH%
echo Starting server at http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server
echo.
php artisan serve --host=127.0.0.1 --port=8000
pause