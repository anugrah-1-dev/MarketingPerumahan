@echo off
setlocal

:: Mendapatkan path folder saat ini
set "ROOT_PATH=%~dp0"

echo ==========================================
echo    MENJALANKAN PROYEK MARKETING PERUMAHAN
echo ==========================================

:: 1. Membuka Frontend di Browser
echo [1/2] Membuka Frontend...
start "" "%ROOT_PATH%frontend/index.html"

:: 2. Menjalankan Backend Laravel
echo [2/2] Menjalankan Backend Laravel...
cd /d "%ROOT_PATH%backend"
php artisan serve

pause
