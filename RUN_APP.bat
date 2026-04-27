@echo off
setlocal

set "PROJECT_DIR=%~dp0"
set "PORT=8001"
set "PHP_EXE="

if exist "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" (
    set "PHP_EXE=C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
)

if not defined PHP_EXE (
    for /d %%D in ("C:\laragon\bin\php\php-*") do (
        if exist "%%D\php.exe" set "PHP_EXE=%%D\php.exe"
    )
)

if not defined PHP_EXE (
    where php >nul 2>nul
    if not errorlevel 1 set "PHP_EXE=php"
)

if not defined PHP_EXE (
    echo PHP was not found.
    echo Please start Laragon or XAMPP, then open:
    echo http://localhost/MVCReservationSystemDoradoSalgado/
    pause
    exit /b 1
)

echo Starting Dorado Salgado MVC Reservation System...
echo URL: http://127.0.0.1:%PORT%/

start "" "%PHP_EXE%" -S 127.0.0.1:%PORT% -t "%PROJECT_DIR%"
timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:%PORT%/"

echo.
echo The app should now be open in your browser.
echo Keep the PHP server window open while using the system.
pause
