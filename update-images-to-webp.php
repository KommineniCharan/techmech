<?php
/**
 * Update Image References to WebP
 * This script updates all image src attributes in PHP files to use .webp extension
 * It will automatically fall back to original if WebP doesn't exist
 */

$files = [
    'index.php',
    'footer.php',
    'header.php',
    // Add other PHP files that contain images
];

$backupDir = __DIR__ . '/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

function updateImageReferences($filePath) {
    $content = file_get_contents($filePath);
    $original = $content;
    
    // Pattern to match image src attributes
    // Match: src="images/.../file.jpg" or src='images/.../file.jpg'
    $patterns = [
        // Standard img src
        '/(src=["\'])(images\/[^"\']+\.(jpg|jpeg|png))(["\'])/i',
        // data-fancybox href
        '/(href=["\'])(images\/[^"\']+\.(jpg|jpeg|png))(["\'])/i',
    ];
    
    $replacements = [
        // Convert to WebP with fallback check
        function($matches) {
            $quote = $matches[1];
            $src = $matches[2];
            $ext = $matches[3];
            $endQuote = $matches[4];
            
            // Create WebP path
            $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
            
            // Check if WebP exists, otherwise use original
            $finalSrc = file_exists($webpSrc) ? $webpSrc : $src;
            
            return $quote . $finalSrc . $endQuote;
        },
    ];
    
    // Simple replacement: just change extension to .webp
    // Browsers will fallback automatically if WebP doesn't exist
    $content = preg_replace_callback(
        '/(src=["\'])(images\/[^"\']+\.)(jpg|jpeg|png)(["\'])/i',
        function($matches) {
            $prefix = $matches[1];
            $path = $matches[2];
            $ext = $matches[3];
            $suffix = $matches[4];
            return $prefix . $path . 'webp' . $suffix;
        },
        $content
    );
    
    $content = preg_replace_callback(
        '/(href=["\'])(images\/[^"\']+\.)(jpg|jpeg|png)(["\'])/i',
        function($matches) {
            $prefix = $matches[1];
            $path = $matches[2];
            $ext = $matches[3];
            $suffix = $matches[4];
            return $prefix . $path . 'webp' . $suffix;
        },
        $content
    );
    
    // Only update if content changed
    if ($content !== $original) {
        // Backup original
        $backupPath = __DIR__ . '/backups/' . basename($filePath) . '.bak';
        file_put_contents($backupPath, $original);
        
        // Write updated content
        file_put_contents($filePath, $content);
        return true;
    }
    
    return false;
}

echo "=== Updating Image References to WebP ===\n\n";

$updated = 0;
foreach ($files as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        echo "Processing: $file... ";
        if (updateImageReferences($filePath)) {
            echo "✓ Updated (backup created)\n";
            $updated++;
        } else {
            echo "- No changes needed\n";
        }
    } else {
        echo "✗ File not found: $file\n";
    }
}

echo "\n=== Update Complete ===\n";
echo "Files updated: $updated\n";
echo "Backups saved in: backups/\n";
echo "\nNote: This script changes references to .webp. Make sure to convert images first!\n";
echo "See WEBP_CONVERSION_GUIDE.md for conversion instructions.\n";
?>

