<?php
/**
 * WebP Image Converter Script
 * Converts all JPG, JPEG, and PNG images to WebP format
 * 
 * Usage: Run this script from command line: php convert-to-webp.php
 * Or access via browser: http://localhost/techmech/convert-to-webp.php
 */

// Check if GD library supports WebP
if (!function_exists('imagewebp')) {
    die("Error: WebP support is not available in your PHP installation. Please enable GD extension with WebP support.\n");
}

// Configuration
$baseDir = __DIR__ . '/images';
$quality = 85; // WebP quality (0-100)
$converted = 0;
$skipped = 0;
$errors = 0;

/**
 * Recursively find all image files
 */
function findImages($dir, &$files = []) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            findImages($path, $files);
        } else {
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $files[] = $path;
            }
        }
    }
    return $files;
}

/**
 * Convert image to WebP
 */
function convertToWebP($sourcePath, $quality = 85) {
    $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);
    
    // Skip if WebP already exists
    if (file_exists($webpPath)) {
        return ['status' => 'skipped', 'message' => 'WebP already exists'];
    }
    
    // Create image resource based on file type
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $image = imagecreatefrompng($sourcePath);
            // Preserve transparency for PNG
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        default:
            return ['status' => 'error', 'message' => 'Unsupported format'];
    }
    
    if (!$image) {
        return ['status' => 'error', 'message' => 'Failed to create image resource'];
    }
    
    // Convert to WebP
    $success = imagewebp($image, $webpPath, $quality);
    
    // Free memory
    imagedestroy($image);
    
    if ($success) {
        // Set same permissions as original
        if (file_exists($sourcePath)) {
            chmod($webpPath, fileperms($sourcePath));
        }
        return ['status' => 'success', 'webp' => $webpPath];
    } else {
        return ['status' => 'error', 'message' => 'Failed to save WebP'];
    }
}

// Start conversion
echo "=== WebP Image Converter ===\n";
echo "Starting conversion...\n\n";

$images = findImages($baseDir);

if (empty($images)) {
    echo "No images found to convert.\n";
    exit;
}

echo "Found " . count($images) . " images to process.\n\n";

foreach ($images as $imagePath) {
    $relativePath = str_replace(__DIR__ . '/', '', $imagePath);
    echo "Processing: $relativePath... ";
    
    $result = convertToWebP($imagePath, $quality);
    
    switch ($result['status']) {
        case 'success':
            $converted++;
            $webpRelative = str_replace(__DIR__ . '/', '', $result['webp']);
            echo "✓ Converted to $webpRelative\n";
            break;
        case 'skipped':
            $skipped++;
            echo "- Skipped (WebP exists)\n";
            break;
        case 'error':
            $errors++;
            echo "✗ Error: " . $result['message'] . "\n";
            break;
    }
}

echo "\n=== Conversion Complete ===\n";
echo "Converted: $converted\n";
echo "Skipped: $skipped\n";
echo "Errors: $errors\n";
echo "\nNext step: Update image references in PHP files to use .webp extension.\n";
?>

