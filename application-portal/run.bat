@echo off
cd /d C:\Users\Dwealth\Documents\application\application-portal
"C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" -c "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.ini-development" artisan serve --host=127.0.0.1 --port=8000
pause