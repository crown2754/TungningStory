@echo off
chcp 65001 >nul
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

echo Starting all services in a single window...
echo Press Ctrl+C to stop all services.
echo.

:: 使用 concurrently 將所有服務統一在同一個視窗執行，並加上不同的顏色標籤以利辨識
:: --kill-others 確保關閉時能一併終止所有服務
npx concurrently -c "blue,green,magenta" "php artisan serve" "npm run dev" "php artisan schedule:work" --names="Server,Vite,Schedule" --kill-others

pause
