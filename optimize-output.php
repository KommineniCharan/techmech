<?php
// optimize-output.php
// Include early in the page (e.g. in header.php) to start buffering.
// This file defines optimize_output_callback() and a small helper.

function optimize_output_callback($html) {
    // Only run on HTML responses
    if (stripos($html, '<html') === false && stripos($html, '<!doctype') === false) {
        return $html;
    }

    // Use DOMDocument to safely manipulate the final HTML
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();

    // Load HTML in a forgiving way, preserve encoding
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new DOMXPath($dom);

    // ---------- 1) Add preload for first stylesheet ----------
    $links = $xpath->query("//link[@rel='stylesheet' and @href]");
    if ($links->length > 0) {
        $first = $links->item(0);
        $href = $first->getAttribute('href');

        // Insert a preload before the first stylesheet if not already present
        $existing = $xpath->query("//link[@rel='preload' and @href='{$href}']");
        if ($existing->length === 0) {
            $pre = $dom->createElement('link');
            $pre->setAttribute('rel', 'preload');
            $pre->setAttribute('as', 'style');
            $pre->setAttribute('href', $href);
            $pre->setAttribute('onload', "this.onload=null;this.rel='stylesheet'");
            $first->parentNode->insertBefore($pre, $first);
        }
    }

    // ---------- 2) Process images: add loading/decoding, dimensions, picture with webp ----------
    $imgs = $xpath->query("//img");
    foreach ($imgs as $img) {
        // skip images that already have data-no-optimize attribute
        if ($img->hasAttribute('data-no-optimize')) {
            continue;
        }

        // add lazy + decoding attributes if missing
        if (!$img->hasAttribute('loading')) {
            $img->setAttribute('loading', 'lazy');
        }
        if (!$img->hasAttribute('decoding')) {
            $img->setAttribute('decoding', 'async');
        }

        // try to add width & height for local files
        $src = $img->getAttribute('src');
        if ($src && !preg_match('#^https?://#i', $src) && !preg_match('#^data:#i', $src)) {
            // Resolve path relative to document root
            $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
            // If src starts with '/', it's absolute; otherwise relative to current script path
            $candidatePaths = [];

            if (strpos($src, '/') === 0) {
                $candidatePaths[] = $docRoot . $src;
            } else {
                // relative path: try relative to current script and to doc root
                $scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
                $candidatePaths[] = realpath($scriptDir . '/' . $src);
                $candidatePaths[] = realpath($docRoot . '/' . $src);
            }

            $found = false;
            foreach ($candidatePaths as $path) {
                if ($path && is_file($path)) {
                    $size = @getimagesize($path);
                    if ($size) {
                        list($w, $h) = $size;
                        if (!$img->hasAttribute('width')) $img->setAttribute('width', $w);
                        if (!$img->hasAttribute('height')) $img->setAttribute('height', $h);
                        $found = true;
                        break;
                    }
                }
            }

            // ---------- build <picture> with .webp alternatives if responsive images exist ----------
            // Expect responsive images in same folder or sibling folders named like: 320w, 640w, 1024w, 1920w
            // Example original: /images/photo.jpg  -> webp variants: /images/320w/photo_320w.webp etc.
            // This is conservative: only replace if we find at least one webp variant.

            if ($found) {
                $ext = pathinfo($src, PATHINFO_EXTENSION);
                $base = basename($src, '.' . $ext);
                $dir  = dirname($src);
                $sizes = [320, 640, 1024, 1920];
                $sources = [];

                foreach ($sizes as $s) {
                    // variant path (relative)
                    $candidate = rtrim($dir, '/') . '/' . $s . 'w/' . $base . '_' . $s . 'w.webp';
                    $candidateAbs = $docRoot . $candidate;
                    if (is_file($candidateAbs)) {
                        $sources[] = ['size' => $s, 'href' => $candidate];
                    }
                }

                if (count($sources) > 0) {
                    // build picture element
                    $picture = $dom->createElement('picture');

                    // add webp source with srcset
                    $srcset_parts = [];
                    foreach ($sources as $s) {
                        $srcset_parts[] = "{$s['href']} {$s['size']}w";
                    }
                    $webpSource = $dom->createElement('source');
                    $webpSource->setAttribute('type', 'image/webp');
                    $webpSource->setAttribute('srcset', implode(', ', $srcset_parts));
                    $webpSource->setAttribute('sizes', '100vw');
                    $picture->appendChild($webpSource);

                    // keep original img as fallback - clone it
                    $clonedImg = $img->cloneNode(true);

                    // if original src is jpg/png, also add srcset for those variants (optional)
                    $fallbackParts = [];
                    foreach ($sources as $s) {
                        // assume fallback variants exist with original extension
                        $candidateFallback = rtrim($dir, '/') . '/' . $s['size'] . 'w/' . $base . '_' . $s['size'] . 'w.' . $ext;
                        $candidateFallbackAbs = $docRoot . $candidateFallback;
                        if (is_file($candidateFallbackAbs)) {
                            $fallbackParts[] = "{$candidateFallback} {$s['size']}w";
                        }
                    }
                    if (count($fallbackParts) > 0) {
                        $clonedImg->setAttribute('srcset', implode(', ', $fallbackParts));
                        $clonedImg->setAttribute('sizes', '100vw');
                    }

                    // insert cloned img into picture
                    $picture->appendChild($clonedImg);

                    // replace original img in DOM with picture
                    $img->parentNode->replaceChild($picture, $img);
                }
            }
        }
    } // end foreach img

    // ---------- 3) Optionally add a small inline style to hint fonts (font-display) ----------
    // This is a conservative injection: we add a tiny style that sets font-display:swap for @font-face rules
    // only if there's at least one external stylesheet link
    if ($links->length > 0) {
        $styleText = "/* font-display: swap hint added by optimizer */\n@font-face { font-display: swap; }\n";
        $styleEl = $dom->createElement('style', $styleText);
        $head = $dom->getElementsByTagName('head')->item(0);
        if ($head) {
            $head->appendChild($styleEl);
        } else {
            // if no head element, insert at top
            $dom->documentElement->insertBefore($styleEl, $dom->documentElement->firstChild);
        }
    }

    // Save and return optimized HTML
    $optimized = $dom->saveHTML();

    // Properly remove the xml encoding we prepended
    $optimized = preg_replace('/^<\?xml.*?\?>/', '', $optimized);

    libxml_clear_errors();
    return $optimized;
}
