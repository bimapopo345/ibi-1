@echo off
echo ====================================
echo    Setup Ngrok untuk XAMPP
echo ====================================
echo.

REM Cek apakah XAMPP Apache berjalan
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I "httpd.exe" >NUL
if errorlevel 1 (
    echo [ERROR] Apache XAMPP belum running!
    echo Silakan start Apache di XAMPP Control Panel dulu
    echo.
    pause
    exit
)

echo [OK] Apache XAMPP terdeteksi...
echo.

REM Jalankan ngrok
echo Menjalankan ngrok...
echo.
echo Tekan CTRL+C untuk menghentikan ngrok
echo.
cd C:\ngrok
echo Mengarahkan ke localhost/aplikasi_pemesanan
ngrok http --host-header="localhost" 80

pause
