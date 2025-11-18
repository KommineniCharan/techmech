@echo off
REM Batch script to convert images to WebP using cwebp
REM This script will download cwebp if needed

setlocal enabledelayedexpansion

echo ========================================
echo WebP Image Converter
echo ========================================
echo.

REM Check if cwebp is available
where cwebp >nul 2>&1
if %errorlevel% equ 0 (
    echo cwebp found in PATH
    goto :convert
)

REM Check if cwebp exists in current directory
if exist "cwebp.exe" (
    echo cwebp.exe found in current directory
    set CWEBP_PATH=cwebp.exe
    goto :convert
)

REM Try to find cwebp in common locations
if exist "C:\webp\cwebp.exe" (
    echo cwebp found at C:\webp\
    set CWEBP_PATH=C:\webp\cwebp.exe
    goto :convert
)

echo.
echo cwebp not found. You have two options:
echo.
echo Option 1: Download cwebp manually
echo   1. Go to: https://developers.google.com/speed/webp/download
echo   2. Download Windows version
echo   3. Extract cwebp.exe to this directory or add to PATH
echo   4. Run this script again
echo.
echo Option 2: Use online conversion
echo   1. Go to: https://squoosh.app/
echo   2. Upload all images from images\ directory
echo   3. Set quality: 85
echo   4. Download WebP versions
echo   5. Place in same directories with .webp extension
echo.
pause
exit /b 1

:convert
if not defined CWEBP_PATH set CWEBP_PATH=cwebp

echo.
echo Starting conversion...
echo Quality: 85
echo.

set /a converted=0
set /a skipped=0
set /a errors=0

REM Convert all images in images directory
for /r "images" %%f in (*.jpg *.jpeg *.png *.JPG *.JPEG *.PNG) do (
    set "input=%%f"
    set "output=%%~dpnf.webp"
    
    REM Check if WebP already exists
    if exist "!output!" (
        echo [SKIP] %%f - WebP already exists
        set /a skipped+=1
    ) else (
        echo [CONVERT] %%f...
        %CWEBP_PATH% -q 85 "%%f" -o "!output!" >nul 2>&1
        if exist "!output!" (
            echo         ^✓ Converted to %%~nxf.webp
            set /a converted+=1
        ) else (
            echo         ✗ Failed to convert
            set /a errors+=1
        )
    )
)

echo.
echo ========================================
echo Conversion Complete
echo ========================================
echo Converted: %converted%
echo Skipped: %skipped%
echo Errors: %errors%
echo.
echo All WebP images have been created!
echo.
pause

