@echo off
title Tungning Story - Dev Launcher
color 0A

echo ===================================================
echo      Starting [Tungning Story] Dev Environment...
echo ===================================================
echo.

:: Check if artisan exists
if not exist "artisan" (
    color 0C
    echo [ERROR] artisan not found!
    echo Please run this file from the Laravel project root.
    pause
    exit
)

echo [1/3] Starting Laravel Backend...
start "Laravel Server (Backend)" cmd /k "php artisan serve"

echo [2/3] Starting Vite Frontend...
start "Vite (Frontend)" cmd /k "npm run dev"

echo [3/3] Starting Scheduler (Game Logic)...
start "Scheduler (Game Logic)" cmd /k "php artisan schedule:work"

echo.
echo ===================================================
echo      All services started!
echo      Frontend URL: http://127.0.0.1:8000
echo ===================================================
echo.
echo You can close this window now. Services are running in background windows.
pause