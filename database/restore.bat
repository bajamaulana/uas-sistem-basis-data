@echo off
echo ========================================
echo Restore Database: coffeeidea
echo ========================================
set HOST=172.22.148.251
set USER=root
set PASSWORD=bajamaulana73*
set DBNAME=coffeeidea

set /p BACKUP_FILE="Masukkan nama file backup (contoh: backup_coffeeidea_20240101.sql): "

if not exist "%BACKUP_FILE%" (
    echo [ERROR] File %BACKUP_FILE% tidak ditemukan!
    pause
    exit /b
)

echo Menjalankan proses restore...
c:\xampp\mysql\bin\mysql.exe -h %HOST% -u %USER% -p"%PASSWORD%" %DBNAME% < "%BACKUP_FILE%"

if %errorlevel% neq 0 (
    echo [ERROR] Restore gagal!
) else (
    echo [SUCCESS] Database berhasil direstore dari %BACKUP_FILE%
)
pause
