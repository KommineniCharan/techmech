<?php
/**
 * Image Helper Function
 * Automatically serves WebP images with JPG/PNG fallback
 * 
 * Usage: <?php echo getOptimizedImage('images/products/eot.jpg', 'EOT Cranes', 400, 300); ?>
 */

function getOptimizedImage($src, $alt = '', $width = '', $height = '', $class = 'img-responsive', $loading = 'lazy') {
    // Convert extension to .webp
    $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
    
    // Check if WebP exists, otherwise use original
    $finalSrc = file_exists($webpSrc) ? $webpSrc : $src;
    
    // Build attributes
    $attrs = [];
    if ($alt) $attrs[] = 'alt="' . htmlspecialchars($alt) . '"';
    if ($width) $attrs[] = 'width="' . intval($width) . '"';
    if ($height) $attrs[] = 'height="' . intval($height) . '"';
    if ($class) $attrs[] = 'class="' . htmlspecialchars($class) . '"';
    if ($loading) $attrs[] = 'loading="' . htmlspecialchars($loading) . '"';
    
    return '<img src="' . htmlspecialchars($finalSrc) . '" ' . implode(' ', $attrs) . '>';
}

/**
 * Get picture element with WebP and fallback
 * More robust but requires more HTML
 */
function getPictureElement($src, $alt = '', $width = '', $height = '', $class = 'img-responsive', $loading = 'lazy') {
    $webpSrc = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
    $webpExists = file_exists($webpSrc);
    
    $attrs = [];
    if ($class) $attrs[] = 'class="' . htmlspecialchars($class) . '"';
    if ($width) $attrs[] = 'width="' . intval($width) . '"';
    if ($height) $attrs[] = 'height="' . intval($height) . '"';
    
    $imgAttrs = [];
    if ($alt) $imgAttrs[] = 'alt="' . htmlspecialchars($alt) . '"';
    if ($loading) $imgAttrs[] = 'loading="' . htmlspecialchars($loading) . '"';
    $imgAttrs = implode(' ', $imgAttrs);
    $pictureAttrs = implode(' ', $attrs);
    
    if ($webpExists) {
        return '<picture ' . $pictureAttrs . '>
            <source srcset="' . htmlspecialchars($webpSrc) . '" type="image/webp">
            <img src="' . htmlspecialchars($src) . '" ' . $imgAttrs . '>
        </picture>';
    } else {
        return '<img src="' . htmlspecialchars($src) . '" ' . $pictureAttrs . ' ' . $imgAttrs . '>';
    }
}
?>

