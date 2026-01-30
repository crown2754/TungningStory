@echo off
title NPC Data Generator
color 0A

echo ================================================
echo       RPG GAME ASSET GENERATOR v1.0
echo ================================================
echo.

echo [1/2] Processing Avatars (AvatarSeeder)...
php artisan db:seed --class=AvatarSeeder

IF %ERRORLEVEL% NEQ 0 (
    goto ERROR
)

echo.
echo ------------------------------------------------
echo.

echo [2/2] Summoning NPCs (NpcSeeder)...
php artisan db:seed --class=NpcSeeder

IF %ERRORLEVEL% NEQ 0 (
    goto ERROR
)

echo.
echo ================================================
echo    SUCCESS! All Game Data has been imported.
echo ================================================
echo.
pause
exit

:ERROR
color 0C
echo.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo    [ERROR] Something went wrong!
echo    Please check the error message above.
echo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
pause