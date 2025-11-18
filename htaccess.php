# --------------------------------
# Force HTTPS + WWW (combined)
# --------------------------------
RewriteEngine On
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteRule ^(.*)$ https://www.techmechcranes.com/$1 [R=301,L]

# --------------------------------
# Error Handling
# --------------------------------
ErrorDocument 404 https://www.techmechcranes.com/

# Redirect index.php → root
RewriteRule ^index\.php$ / [L,R=301]

# Redirect .php/ → .php
RewriteRule ^([^/]+)\.php/.*$ /$1.php [L,R=301]

# --------------------------------
# Security Headers
# --------------------------------
<IfModule mod_headers.c>
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
</IfModule>

# --------------------------------
# Compression (Prefer Brotli > Deflate)
# --------------------------------
<IfModule mod_brotli.c>
    AddOutputFilterByType BROTLI_COMPRESS text/html text/plain text/xml text/css text/javascript application/javascript application/json application/xml image/svg+xml font/woff2
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json application/xml image/svg+xml font/woff font/woff2 font/ttf font/otf

    # Exclude already compressed formats
    SetEnvIfNoCase Request_URI \.(?:gif|jpg|jpeg|png|webp|mp4|avi|mov|wmv|mp3|zip|gz|rar)$ no-gzip dont-vary

    # Compatibility fixes
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
    Header append Vary User-Agent
</IfModule>

# --------------------------------
# Browser Caching
# --------------------------------
<IfModule mod_expires.c>
    ExpiresActive On

    # Images
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"

    # Fonts
    ExpiresByType font/ttf "access plus 1 year"
    ExpiresByType font/otf "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"

    # CSS & JS
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"

    # HTML (shorter cache)
    ExpiresByType text/html "access plus 1 week"

    # PDFs & Docs
    ExpiresByType application/pdf "access plus 1 year"
</IfModule>

# Cache-Control (stronger than Expires)
<FilesMatch "\.(ico|jpe?g|png|gif|webp|svg|woff2?|ttf|otf|css|js|pdf)$">
    Header set Cache-Control "public, max-age=31536000, immutable"
</FilesMatch>
<FilesMatch "\.(html|htm)$">
    Header set Cache-Control "public, max-age=604800"
</FilesMatch>