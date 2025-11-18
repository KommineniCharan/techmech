<?php
/**
 * Batch Update All Image References to WebP
 * Updates all .jpg, .jpeg, .png references to .webp with automatic fallback
 */

$phpFiles = glob('*.php');
$updated = 0;
$total = 0;

foreach ($phpFiles as $file) {
    if (in_array($file, ['convert-to-webp.php', 'update-images-to-webp.php', 'batch-update-images.php', 'image-helper.php'])) {
        continue; // Skip helper scripts
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Update img src attributes
    $content = preg_replace_callback(
        '/(src=["\'])(images\/[^"\']+\.)(jpg|jpeg|png)(["\'])/i',
        function($matches) {
            return $matches[1] . $matches[2] . 'webp' . $matches[4] . ' onerror="this.onerror=null;this.src=\'' . $matches[2] . $matches[3] . '\';"';
        },
        $content
    );
    
    // Update href attributes (for fancybox links)
    $content = preg_replace_callback(
        '/(href=["\'])(images\/[^"\']+\.)(jpg|jpeg|png)(["\'])/i',
        function($matches) {
            return $matches[1] . $matches[2] . 'webp' . $matches[4];
        },
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $updated++;
        echo "Updated: $file\n";
    }
    $total++;
}

echo "\n=== Batch Update Complete ===\n";
echo "Files processed: $total\n";
echo "Files updated: $updated\n";
echo "\nNote: All images now reference .webp with automatic fallback to original format.\n";
echo "Convert your images using WEBP_CONVERSION_GUIDE.md\n";
?>

