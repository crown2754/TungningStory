@echo off
chcp 65001 >nul
title Tungning Story - Dev Launcher
color 0A

echo ===================================================
echo      Starting [Tungning Story] Dev Environment...
echo ===================================================
echo.

REM Check if artisan exists
if not exist "artisan" (
    color 0C
    echo [ERROR] artisan not found!
    echo Please run this file from the Laravel project root.
    pause
    exit
)

echo Starting all services in a single window...
echo Press Ctrl+C to stop all services.
echo.

REM Use concurrently to run all services in one window with colored labels
REM --kill-others stops every child when one exits or Ctrl+C
npx concurrently -c "blue,green,magenta" "php artisan serve" "npm run dev" "php artisan schedule:work" --names="Server,Vite,Schedule" --kill-others

pause
