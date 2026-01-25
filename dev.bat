@echo off
title 東寧物語 - 開發環境啟動器
color 0A

echo ===================================================
echo      正在啟動 [東寧物語] 開發環境...
echo ===================================================
echo.

:: 檢查是否在專案根目錄
if not exist "artisan" (
    color 0C
    echo [錯誤] 找不到 artisan 檔案！
    echo 請確保此檔案位於 Laravel 專案的根目錄中。
    pause
    exit
)

echo [1/3] 啟動 Laravel 後端伺服器...
start "Laravel Server (Backend)" cmd /k "php artisan serve"

echo [2/3] 啟動 Vite 前端熱更新...
start "Vite (Frontend)" cmd /k "npm run dev"

echo [3/3] 啟動 排程監聽器 (體力恢復系統)...
start "Scheduler (Game Logic)" cmd /k "php artisan schedule:work"

echo.
echo ===================================================
echo      所有服務已啟動！
echo      前台網址: http://127.0.0.1:8000
echo ===================================================
echo.
echo 您現在可以關閉這個視窗，服務會在背景視窗繼續執行。
pause