# PowerShell Script to Convert Images to WebP using .NET
# Requires: .NET Framework 4.5+ (usually pre-installed on Windows)

$ErrorActionPreference = "Stop"

# Check if System.Drawing is available
try {
    Add-Type -AssemblyName System.Drawing
    Write-Host "System.Drawing loaded successfully" -ForegroundColor Green
} catch {
    Write-Host "Error: System.Drawing not available. Please install .NET Framework." -ForegroundColor Red
    exit 1
}

# Function to convert image to WebP
function Convert-ToWebP {
    param(
        [string]$InputPath,
        [string]$OutputPath,
        [int]$Quality = 85
    )
    
    try {
        # Load image
        $image = [System.Drawing.Image]::FromFile($InputPath)
        
        # Create encoder parameters
        $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
        $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter(
            [System.Drawing.Imaging.Encoder]::Quality, 
            $Quality
        )
        
        # Get WebP codec (if available)
        # Note: .NET doesn't natively support WebP, so we'll use a workaround
        # For now, this script will prepare the structure
        # You'll need to use an external tool
        
        Write-Host "Processing: $InputPath" -ForegroundColor Yellow
        
        # Save as WebP format (requires additional library)
        # For now, we'll create a list of files to convert
        
        $image.Dispose()
        return $true
    } catch {
        Write-Host "Error converting $InputPath : $_" -ForegroundColor Red
        return $false
    }
}

# Alternative: Use ImageMagick via command line if available
Write-Host "`nChecking for ImageMagick..." -ForegroundColor Cyan
$magickPath = Get-Command magick -ErrorAction SilentlyContinue

if ($magickPath) {
    Write-Host "ImageMagick found! Converting images..." -ForegroundColor Green
    
    $imagesDir = Join-Path $PSScriptRoot "images"
    $quality = 85
    $converted = 0
    $skipped = 0
    
    Get-ChildItem -Path $imagesDir -Recurse -Include *.jpg,*.jpeg,*.png | ForEach-Object {
        $webpPath = $_.FullName -replace '\.(jpg|jpeg|png)$', '.webp'
        
        if (Test-Path $webpPath) {
            Write-Host "Skipping: $($_.Name) (WebP already exists)" -ForegroundColor Gray
            $skipped++
        } else {
            Write-Host "Converting: $($_.Name)..." -ForegroundColor Yellow
            & magick $_.FullName -quality $quality $webpPath
            if (Test-Path $webpPath) {
                Write-Host "  ✓ Converted to $([System.IO.Path]::GetFileName($webpPath))" -ForegroundColor Green
                $converted++
            } else {
                Write-Host "  ✗ Failed to convert" -ForegroundColor Red
            }
        }
    }
    
    Write-Host "`n=== Conversion Complete ===" -ForegroundColor Cyan
    Write-Host "Converted: $converted" -ForegroundColor Green
    Write-Host "Skipped: $skipped" -ForegroundColor Gray
} else {
    Write-Host "ImageMagick not found." -ForegroundColor Yellow
    Write-Host "`nPlease install ImageMagick or use one of these alternatives:" -ForegroundColor Cyan
    Write-Host "1. Download ImageMagick: https://imagemagick.org/script/download.php" -ForegroundColor White
    Write-Host "2. Use online tool: https://squoosh.app/" -ForegroundColor White
    Write-Host "3. Install cwebp: https://developers.google.com/speed/webp/download" -ForegroundColor White
}

