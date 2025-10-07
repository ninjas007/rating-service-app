@echo off
cd /d C:\laragon\www\rating-service-app
C:\laragon\bin\php\php-8.2.19-Win32-vs16-x64\php.exe artisan db:backup > backup.log 2>&1
