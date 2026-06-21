@echo off
echo ========================================
echo Backup Database: coffeeidea
echo ========================================
set HOST=172.22.148.251
set USER=root
set PASSWORD=bajamaulana73*
set DBNAME=coffeeidea
set BACKUP_FILE=backup_coffeeidea_%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql

echo Menjalankan proses backup...
c:\xampp\mysql\bin\mysqldump.exe -h %HOST% -u %USER% -p"%PASSWORD%" %DBNAME% > %BACKUP_FILE%

if %errorlevel% neq 0 (
    echo [ERROR] Backup gagal! Pastikan xampp/mysql/bin/mysqldump.exe tersedia dan database online.
) else (
    echo [SUCCESS] Backup berhasil disimpan ke %BACKUP_FILE%
)
pause
