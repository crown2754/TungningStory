@echo off
chcp 65001 >nul
title Tungning Story - 匯入物品
color 0A

cd /d "%~dp0"

if not exist "artisan" (
    color 0C
    echo [錯誤] 找不到 artisan，請將此檔案放在 Laravel 專案根目錄。
    pause
    exit /b 1
)

echo ================================================
echo       匯入物品資料（database\seeders\ItemSeeder.php）
echo ================================================
echo.

php artisan db:seed --class=ItemSeeder

IF %ERRORLEVEL% NEQ 0 (
    goto ERROR
)

echo.
echo ================================================
echo    完成：物品已寫入資料庫（依名稱 updateOrCreate）
echo ================================================
echo.
pause
exit /b 0

:ERROR
color 0C
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo    [錯誤] 種子執行失敗，請查看上方訊息。
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
pause
exit /b 1
