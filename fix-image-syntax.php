<?php
/**
 * Fix syntax errors in image onerror attributes
 */

$files = glob('*.php');
$fixed = 0;

foreach ($files as $file) {
    if (in_array($file, ['convert-to-webp.php', 'fix-image-syntax.php', 'batch-update-images.php'])) {
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix double onerror: onerror="...'file.webp' onerror="...
    // Should be: onerror="...'file.jpg';"
    $content = preg_replace(
        "/(onerror=\"this\.onerror=null;this\.src='[^']+\.webp')\s+onerror=\"([^\"]+)\";/",
        "$1;",
        $content
    );
    
    // Fix malformed onerror with double quotes
    $content = preg_replace(
        "/onerror=\"this\.onerror=null;this\.src='([^']+)\.webp'\s+onerror=\"this\.onerror=null;this\.src='([^']+)';\"\";/",
        "onerror=\"this.onerror=null;this.src='$2';\";",
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $fixed++;
        echo "Fixed: $file\n";
    }
}

echo "\nFixed $fixed files.\n";
?>

