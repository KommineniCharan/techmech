# Website Optimization & Testing Practices
## Complete Guide for Non-Technical Users

---

## Table of Contents

1. [Introduction](#introduction)
2. [Why Website Optimization Matters](#why-website-optimization-matters)
3. [The 9 Essential Practices](#the-9-essential-practices)
4. [Step-by-Step Implementation Guide](#step-by-step-implementation-guide)
5. [GitHub Actions Automation](#github-actions-automation)
6. [Testing & Verification](#testing--verification)
7. [Troubleshooting](#troubleshooting)
8. [Glossary](#glossary)

---

## Introduction

This guide explains the website optimization practices used to make websites load faster, use less data, and provide a better experience for visitors. All practices are explained in simple, non-technical language with step-by-step instructions.

### What You'll Learn

- How to optimize images for faster loading
- How to reduce file sizes without losing quality
- How to make websites load faster on all devices
- How to automate optimization using GitHub Actions
- How to verify that optimizations are working

### Who This Guide Is For

- Website owners who want to understand optimization
- Project managers overseeing website projects
- Content creators who add images to websites
- Anyone who wants to improve website performance

---

## Why Website Optimization Matters

### The Problem

When a website loads slowly:
- **Users leave**: 53% of mobile users abandon sites that take more than 3 seconds to load
- **Search engines rank you lower**: Google favors fast-loading websites
- **You lose money**: Every 1-second delay can reduce conversions by 7%
- **Mobile users waste data**: Large images consume expensive mobile data

### The Solution

By following the 9 practices in this guide, you can:
- ✅ Reduce page load time by 50-70%
- ✅ Improve Google PageSpeed score to 90-100
- ✅ Save mobile users' data (up to 80% reduction)
- ✅ Improve user experience and engagement
- ✅ Increase search engine rankings

---

## The 9 Essential Practices

### Overview

Here are the 9 practices we'll implement, in order:

1. **Resize Images** - Create multiple sizes for different devices
2. **Convert to WebP** - Use modern image format (smaller file size)
3. **Replace `<img>` with `<picture>`** - Let browsers choose the best image
4. **Gzip CSS and JS** - Compress files to reduce download size
5. **Preload Critical CSS** - Load important styles first
6. **Defer or Async Scripts** - Load JavaScript without blocking page
7. **Remove Unused CSS & JS** - Delete code that's not being used
8. **Minify CSS & JS** - Remove unnecessary spaces and comments
9. **Add Gzip Cache & Host Fonts Locally** - Cache files and keep fonts on your server

---

## Step-by-Step Implementation Guide

### Practice 1: Resize Images

#### What It Means

Instead of using one large image for all devices, we create multiple sizes. A mobile phone doesn't need a 1920px wide image - a 360px image is enough and loads much faster.

#### Why It's Important

- **Mobile users**: Get smaller images = faster loading = less data usage
- **Desktop users**: Get larger images = better quality
- **Overall**: Everyone gets the right size for their device

#### How It Works

We create images in these sizes:
- **360px** - Small mobile phones
- **480px** - Large mobile phones
- **640px** - Small tablets
- **768px** - Tablets
- **1024px** - Small laptops
- **1280px** - Laptops
- **1920px** - Large desktop monitors

#### Step-by-Step Process

1. **Find all images** in your website's `images` folder
2. **For each image**, create 7 copies in different sizes
3. **Organize them** in folders named `360w`, `480w`, `640w`, etc.
4. **Name them** like: `product_360w.jpg`, `product_480w.jpg`, etc.

#### Example

**Before:**
```
images/
└── product.jpg (2MB, 1920px wide)
```

**After:**
```
images/
├── 360w/
│   └── product_360w.jpg (50KB)
├── 480w/
│   └── product_480w.jpg (80KB)
├── 640w/
│   └── product_640w.jpg (120KB)
├── 768w/
│   └── product_768w.jpg (180KB)
├── 1024w/
│   └── product_1024w.jpg (300KB)
├── 1280w/
│   └── product_1280w.jpg (500KB)
├── 1920w/
│   └── product_1920w.jpg (1MB)
└── product.jpg (original, 2MB)
```

#### Technical Implementation

This is done automatically using a script called `resize-images.js`. The script:
- Finds all JPG, JPEG, and PNG images
- Uses a tool called "Sharp" to resize them
- Creates folders and saves resized images
- Keeps the original image as backup

---

### Practice 2: Convert Images to WebP

#### What It Means

WebP is a modern image format created by Google. It provides the same quality as JPG/PNG but with 25-35% smaller file size.

#### Why It's Important

- **Smaller files** = Faster downloads
- **Same quality** = No visible difference
- **Saves bandwidth** = Especially important for mobile users
- **Better SEO** = Google favors optimized websites

#### How It Works

1. Take original JPG/PNG images
2. Convert them to WebP format
3. Keep original files as backup
4. Use WebP versions on the website

#### Step-by-Step Process

1. **Scan** all images in the `images` folder
2. **Convert** each JPG/PNG to WebP format
3. **Save** WebP files next to originals
4. **Verify** conversion was successful

#### Example

**Before:**
```
images/
└── product.jpg (500KB)
```

**After:**
```
images/
├── product.jpg (500KB - original, kept as backup)
└── product.webp (300KB - 40% smaller!)
```

#### Technical Implementation

The `convert-webp.js` script:
- Finds all JPG, JPEG, and PNG images
- Uses Sharp library to convert to WebP
- Maintains image quality while reducing size
- Creates `.webp` files automatically

---

### Practice 3: Replace `<img>` with `<picture>` Element & Add Lazy Loading

#### What It Means

Instead of using simple `<img>` tags, we use `<picture>` elements that let the browser automatically choose the best image size based on the user's device. We also add "lazy loading" so images only load when the user scrolls to them.

#### Why It's Important

- **Automatic optimization**: Browser picks the right image size
- **Faster initial load**: Images below the fold load later
- **Better mobile experience**: Mobile gets smaller images automatically
- **Reduced bandwidth**: Only loads what's needed

#### How It Works

**Before (Simple):**
```html
<img src="images/product.jpg" alt="Product">
```

**After (Optimized):**
```html
<picture>
<source srcset="images/360w/product_360w.webp" media="(max-width: 360px)" type="image/webp">
<source srcset="images/480w/product_480w.webp" media="(max-width: 480px)" type="image/webp">
<source srcset="images/640w/product_640w.webp" media="(max-width: 640px)" type="image/webp">
<source srcset="images/1024w/product_1024w.webp" media="(max-width: 1024px)" type="image/webp">
<img src="images/product.webp" alt="Product" loading="lazy" width="400" height="300">
</picture>
```

#### What Each Part Does

1. **`<picture>` element**: Container that holds multiple image options
2. **`<source>` tags**: Different image sizes for different screen widths
3. **`media` attribute**: Tells browser when to use each size
4. **`type="image/webp"`**: Specifies WebP format
5. **`<img>` tag**: Fallback for older browsers
6. **`loading="lazy"`**: Only loads image when user scrolls near it
7. **`width` and `height`**: Prevents layout shift while loading

#### Step-by-Step Process

1. **Find all** `<img>` tags in HTML/PHP files
2. **Replace** with `<picture>` element
3. **Add** multiple `<source>` tags for different sizes
4. **Add** `loading="lazy"` attribute
5. **Add** width and height attributes

#### Technical Implementation

The `replace-img-with-picture.js` script:
- Scans all HTML and PHP files
- Finds all `<img>` tags
- Replaces them with optimized `<picture>` elements
- Adds lazy loading automatically
- Updates CSS background images too

---

### Practice 4: Gzip CSS and JS Files

#### What It Means

Gzip is a compression method that makes files smaller before sending them over the internet. It's like zipping a file before emailing it.

#### Why It's Important

- **Reduces file size by 60-80%**
- **Faster downloads** = Faster page loads
- **Saves bandwidth** = Lower hosting costs
- **Better user experience** = Especially on slow connections

#### How It Works

1. Original CSS file: 100KB
2. Gzip compresses it: 30KB (70% reduction!)
3. Browser downloads 30KB
4. Browser automatically decompresses it
5. Website works exactly the same

#### Step-by-Step Process

1. **Find all** CSS and JS files
2. **Compress** each file using Gzip
3. **Create** `.gz` versions (e.g., `style.css.gz`)
4. **Configure server** to serve `.gz` files when browser supports it

#### Example

**Before:**
```
css/
└── style.css (200KB)
js/
└── main.js (150KB)
```

**After:**
```
css/
├── style.css (200KB - original)
└── style.css.gz (60KB - 70% smaller!)
js/
├── main.js (150KB - original)
└── main.js.gz (45KB - 70% smaller!)
```

#### Technical Implementation

In GitHub Actions, this is done with:
```bash
find css js -type f \( -name "*.css" -o -name "*.js" \) -exec gzip -kf {} \;
```

This command:
- Finds all CSS and JS files
- Compresses them with Gzip
- Keeps original files (`-k` flag)
- Forces overwrite if exists (`-f` flag)

---

### Practice 5: Preload Critical CSS

#### What It Means

Critical CSS is the CSS code needed to display the top part of your website (above the fold). By preloading it, we tell the browser to download it immediately, so the page looks right from the start.

#### Why It's Important

- **Faster visual appearance**: Page looks styled immediately
- **Better user experience**: No "flash of unstyled content"
- **Improved PageSpeed score**: Google measures this
- **Reduces render-blocking**: Page can display while other CSS loads

#### How It Works

**Before:**
```html
<link rel="stylesheet" href="css/style.css">
```

**After:**
```html
<!-- Preload critical CSS -->
<link rel="preload" href="css/critical.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="css/critical.css"></noscript>

<!-- Load other CSS asynchronously -->
<link rel="preload" href="css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="css/style.css"></noscript>
```

#### What Each Part Does

1. **`rel="preload"`**: Tells browser to download immediately
2. **`as="style"`**: Specifies it's a stylesheet
3. **`onload` handler**: Converts preload to actual stylesheet when loaded
4. **`<noscript>`**: Fallback for browsers without JavaScript

#### Step-by-Step Process

1. **Identify** critical CSS (styles for above-the-fold content)
2. **Extract** it into a separate file (`critical.css`)
3. **Add** preload link in `<head>` section
4. **Load** other CSS asynchronously

#### Manual Implementation

Add this to your HTML `<head>` section:
```html
<!-- Critical CSS - Inline or Preload -->
<style>
/* Paste critical CSS here OR */
</style>
<link rel="preload" href="css/critical.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

<!-- Non-critical CSS - Load asynchronously -->
<link rel="preload" href="css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
```

---

### Practice 6: Defer or Async Non-Critical Scripts

#### What It Means

JavaScript files can block page rendering. By using `defer` or `async`, we tell the browser to load JavaScript in the background without blocking the page from displaying.

#### Why It's Important

- **Faster page display**: Page shows content while JS loads
- **Better user experience**: Users see content immediately
- **Improved PageSpeed**: Reduces render-blocking resources
- **Better mobile performance**: Critical on slow connections

#### Difference Between Defer and Async

- **`defer`**: Scripts load in background, execute in order after page loads
- **`async`**: Scripts load in background, execute immediately when ready (order not guaranteed)

#### How It Works

**Before (Blocking):**
```html
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
```

**After (Non-Blocking):**
```html
<!-- Critical: Load early -->
<script src="js/jquery.min.js"></script>

<!-- Non-critical: Defer -->
<script src="js/bootstrap.min.js" defer></script>
<script src="js/custom.js" defer></script>
<script src="js/analytics.js" defer></script>
```

#### Step-by-Step Process

1. **Identify** critical scripts (usually jQuery or core libraries)
2. **Keep** critical scripts without defer/async
3. **Add** `defer` to non-critical scripts
4. **Add** `async` to independent scripts (like analytics)

#### Example Implementation

```html
<!-- In <head> - Critical for page functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- In <body> before </body> - Non-critical, can defer -->
<script src="js/bootstrap.min.js" defer></script>
<script src="js/slick.min.js" defer></script>
<script src="js/custom.js" defer></script>

<!-- Analytics - Can be async (doesn't depend on other scripts) -->
<script src="js/analytics.js" async></script>
```

---

### Practice 7: Carefully Remove Unused CSS & JS

#### What It Means

Over time, websites accumulate CSS and JavaScript code that's no longer being used. Removing this "dead code" reduces file sizes and improves performance.

#### Why It's Important

- **Smaller files**: Less code = faster downloads
- **Faster parsing**: Browser processes less code
- **Better performance**: Especially on mobile devices
- **Cleaner codebase**: Easier to maintain

#### How It Works

1. **Analyze** which CSS/JS is actually used
2. **Identify** unused code
3. **Remove** unused code (carefully!)
4. **Test** to ensure nothing breaks

#### Step-by-Step Process

1. **Use tools** like PurgeCSS to find unused CSS
2. **Review** the list of unused code
3. **Test** in a development environment first
4. **Remove** unused code carefully
5. **Test** website functionality thoroughly

#### Tools for This

**For CSS:**
- **PurgeCSS**: Automatically removes unused CSS
- **Chrome DevTools**: Coverage tab shows unused CSS/JS

**For JavaScript:**
- **Webpack Bundle Analyzer**: Shows what's in your JS bundles
- **Chrome DevTools**: Coverage tab

#### Manual Process

1. Open Chrome DevTools (F12)
2. Go to "Coverage" tab
3. Reload page
4. See which CSS/JS is unused (red = unused, green = used)
5. Remove unused code carefully

#### Automated Process (GitHub Actions)

This can be automated using PurgeCSS:
```bash
npm install -g purgecss
purgecss --css css/*.css --content *.php --output css/cleaned/
```

---

### 🔥 Special Guide: Safely Removing Unused Code from Multi-Page Sites with Bootstrap CDN

#### The Challenge

When you have:
- Multiple pages (index.php, about.php, contact.php, etc.)
- Bootstrap CDN files
- Custom CSS and JavaScript
- Responsive design that must work on all devices

You need to remove unused code **without breaking**:
- Any page functionality
- Responsive layouts
- Interactive components
- Mobile/tablet/desktop views

#### The Safe Approach

We'll use a **multi-page scanning** approach that checks ALL pages before removing anything.

#### Step 1: Analyze ALL Pages Together

**Tools You'll Need:**
1. **PurgeCSS** - For CSS analysis
2. **Chrome DevTools Coverage** - For manual verification
3. **UnCSS** - Alternative tool for CSS

**Option A: Using PurgeCSS (Recommended)**

PurgeCSS can scan multiple pages and keep all CSS used across them.

**Install PurgeCSS:**
```bash
npm install -D purgecss
```

**Create a Configuration File:**

Create a file called `purgecss.config.js`:

```javascript
module.exports = {
  content: [
    // List ALL your PHP/HTML pages
    '*.php',
    '**/*.php',
    'js/**/*.js',
    // Add more paths if needed
  ],
  css: [
    'css/bootstrap.min.css',
    'css/style.css',
    'css/animate.min.css',
    // Add all your CSS files
  ],
  // Safelist: CSS classes that should NEVER be removed
  safelist: {
    // Standard safelist - keeps these classes always
    standard: [
      'active',
      'show',
      'fade',
      'collapse',
      'collapsing',
      'modal-open',
      'modal-backdrop',
    ],
    // Deep safelist - keeps these and all variants (e.g., col-md-6, col-lg-4)
    deep: [
      /^col-/,        // All Bootstrap columns (col-md-6, col-lg-4, etc.)
      /^row/,         // All Bootstrap rows
      /^btn-/,        // All Bootstrap buttons
      /^navbar-/,     // All navbar classes
      /^dropdown-/,   // All dropdown classes
      /^modal-/,      // All modal classes
      /^carousel-/,   // All carousel classes
      /^form-/,       // All form classes
      /^nav-/,        // All navigation classes
      /^card-/,       // All card classes
      /^alert-/,      // All alert classes
      /^table-/,      // All table classes
      /^badge-/,      // All badge classes
      /^d-/,          // Display utilities (d-none, d-block, etc.)
      /^flex-/,       // Flexbox utilities
      /^justify-/,    // Justify content utilities
      /^align-/,      // Align items utilities
      /^m[tblrxy]?-/, // Margin utilities (mt-3, mx-auto, etc.)
      /^p[tblrxy]?-/, // Padding utilities (pt-2, px-4, etc.)
      /^w-/,          // Width utilities
      /^h-/,          // Height utilities
      /^text-/,       // Text utilities (text-center, text-danger, etc.)
      /^bg-/,         // Background utilities
      /^border-/,     // Border utilities
      /^rounded-/,    // Border radius utilities
      /^shadow-/,     // Shadow utilities
      /^position-/,   // Position utilities
      /^visible/,     // Visibility utilities
      /^invisible/,   // Invisibility utilities
      /^sr-/,         // Screen reader utilities
      /^offset-/,     // Grid offset utilities
      /^order-/,      // Flex order utilities
    ],
    // Greedy safelist - keeps classes with these patterns
    greedy: [
      /slick/,        // Slick slider
      /fancybox/,     // Fancybox
      /isotope/,      // Isotope
      /animate/,      // Animate.css
    ]
  },
  // Additional options
  defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
  fontFace: true,      // Keep @font-face rules
  keyframes: true,     // Keep @keyframes animations
  variables: true,     // Keep CSS variables
  rejected: true,      // Generate report of removed CSS
  rejectedCss: true,   // Save rejected CSS to file for review
}
```

**Run PurgeCSS:**

```bash
# Create output directory
mkdir -p css/optimized

# Run PurgeCSS with config
npx purgecss --config ./purgecss.config.js --output css/optimized
```

**Option B: Using UnCSS**

UnCSS actually loads pages in a browser to detect used CSS.

**Install UnCSS:**
```bash
npm install -g uncss
```

**Run UnCSS on all pages:**
```bash
# For local files
uncss index.php about.php contact.php products.php > css/cleaned-bootstrap.css

# For live website (better - includes dynamic classes)
uncss http://localhost/yoursite/index.php \
      http://localhost/yoursite/about.php \
      http://localhost/yoursite/contact.php \
      --output css/cleaned-bootstrap.css
```

#### Step 2: Safelist Critical Bootstrap Classes

Bootstrap uses many classes dynamically (especially for responsive design). You MUST safelist them.

**Bootstrap Classes That Are Often Removed By Mistake:**

```javascript
// Add these to your PurgeCSS safelist
const bootstrapSafelist = [
  // Responsive breakpoints
  /^col-xs-/,
  /^col-sm-/,
  /^col-md-/,
  /^col-lg-/,
  /^col-xl-/,
  
  // Display utilities for all breakpoints
  /^d-none/,
  /^d-inline/,
  /^d-block/,
  /^d-flex/,
  /^d-sm-/,
  /^d-md-/,
  /^d-lg-/,
  /^d-xl-/,
  
  // Spacing utilities (all breakpoints)
  /^m[tblrxy]?-[0-5]$/,
  /^m[tblrxy]?-sm-[0-5]$/,
  /^m[tblrxy]?-md-[0-5]$/,
  /^m[tblrxy]?-lg-[0-5]$/,
  /^m[tblrxy]?-xl-[0-5]$/,
  /^p[tblrxy]?-[0-5]$/,
  /^p[tblrxy]?-sm-[0-5]$/,
  /^p[tblrxy]?-md-[0-5]$/,
  /^p[tblrxy]?-lg-[0-5]$/,
  /^p[tblrxy]?-xl-[0-5]$/,
  
  // JavaScript-added classes (dynamic)
  'show',
  'active',
  'fade',
  'collapse',
  'collapsing',
  'collapsed',
  'modal-open',
  'modal-backdrop',
  'carousel-item-next',
  'carousel-item-prev',
  'carousel-item-left',
  'carousel-item-right',
  
  // Hover/focus states
  /^hover:/,
  /^focus:/,
  /^active:/,
];
```

#### Step 3: Test on ALL Breakpoints

After removing unused CSS, test **every page** on **every device size**:

**Testing Checklist:**

**Desktop (1920px+):**
- [ ] All pages load correctly
- [ ] Navigation works
- [ ] Modals open/close
- [ ] Forms submit
- [ ] Carousels work

**Laptop (1024px - 1366px):**
- [ ] Layout adapts properly
- [ ] No horizontal scroll
- [ ] All content visible

**Tablet (768px - 1024px):**
- [ ] Navigation collapses (if applicable)
- [ ] Grid system responds correctly
- [ ] Images resize properly
- [ ] All interactive elements work

**Mobile (320px - 767px):**
- [ ] Mobile menu works
- [ ] Single column layout
- [ ] Touch targets are large enough
- [ ] No content overflow

#### Step 4: Handle Bootstrap CDN Specifically

**Problem:** You can't modify CDN files directly.

**Solution:** Create a custom "cleaned" version for production.

**Approach 1: Download and Clean Bootstrap**

```bash
# Download Bootstrap CSS
wget https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css -O css/bootstrap-full.css

# Clean it with PurgeCSS (scanning all your pages)
npx purgecss --css css/bootstrap-full.css --content "*.php" "**/*.php" "js/**/*.js" --output css/

# Result: css/bootstrap-full.css (cleaned version)
```

**Approach 2: Use Bootstrap's Custom Build**

Visit https://getbootstrap.com/docs/5.3/customize/overview/ and select only the components you use.

**Approach 3: Keep CDN, Remove Unused Custom CSS Only**

```javascript
// purgecss.config.js - Only clean YOUR CSS, not Bootstrap
module.exports = {
  content: ['*.php', '**/*.php', 'js/**/*.js'],
  css: [
    'css/style.css',          // Clean this
    'css/custom.css',         // Clean this
    // DON'T include Bootstrap CDN file
  ],
  // ... rest of config
}
```

#### Step 5: Safe Removal Process

**Never remove code directly in production!** Follow this process:

**Step-by-step Safe Process:**

1. **Create a backup:**
```bash
# Backup all CSS files
mkdir -p backups/css-backup-$(date +%Y%m%d)
cp -r css/* backups/css-backup-$(date +%Y%m%d)/
```

2. **Run PurgeCSS with rejected CSS tracking:**
```bash
npx purgecss --config ./purgecss.config.js --output css/optimized --rejected
```

3. **Review rejected.txt:**
Check what was removed. Look for:
- Responsive classes (col-md-, d-sm-, etc.)
- Dynamic classes (active, show, etc.)
- Hover/focus states

4. **If something important was removed, add to safelist:**
```javascript
// In purgecss.config.js
safelist: {
  deep: [
    /^the-class-pattern-that-was-removed/,
  ]
}
```

5. **Test in development:**
```bash
# Start local server
php -S localhost:8000

# Open in browser and test ALL pages
```

6. **Use Chrome DevTools Coverage:**
- Open DevTools (F12)
- Go to Coverage tab (Ctrl+Shift+P → "Show Coverage")
- Visit each page
- Check if cleaned CSS is actually used

7. **Deploy to staging (not production):**
Test on real devices before going live.

8. **Monitor for issues:**
Check for:
- Layout breaks
- Missing styles
- Responsive issues

#### Step 6: Automate with GitHub Actions

**Create `.github/workflows/purge-css.yml`:**

```yaml
name: PurgeCSS - Remove Unused Styles

on:
  workflow_dispatch:  # Manual trigger only (for safety)

jobs:
  purgecss:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4
      
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: 20
      
      - name: Install PurgeCSS
        run: npm install -D purgecss
      
      - name: Create PurgeCSS config
        run: |
          cat > purgecss.config.js << 'EOF'
          module.exports = {
            content: ['*.php', '**/*.php', 'js/**/*.js'],
            css: ['css/*.css'],
            safelist: {
              standard: ['active', 'show', 'fade', 'collapse', 'collapsing'],
              deep: [
                /^col-/, /^row/, /^btn-/, /^navbar-/, /^dropdown-/,
                /^modal-/, /^carousel-/, /^form-/, /^nav-/, /^card-/,
                /^alert-/, /^table-/, /^badge-/, /^d-/, /^flex-/,
                /^justify-/, /^align-/, /^m[tblrxy]?-/, /^p[tblrxy]?-/,
                /^w-/, /^h-/, /^text-/, /^bg-/, /^border-/, /^rounded-/,
                /^shadow-/, /^position-/, /^visible/, /^invisible/,
                /^sr-/, /^offset-/, /^order-/
              ],
              greedy: [/slick/, /fancybox/, /isotope/, /animate/]
            },
            defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
            fontFace: true,
            keyframes: true,
            variables: true,
          }
          EOF
      
      - name: Run PurgeCSS
        run: |
          mkdir -p css/optimized
          npx purgecss --config ./purgecss.config.js --output css/optimized
      
      - name: Generate report
        run: |
          echo "# PurgeCSS Report" > purgecss-report.md
          echo "Generated: $(date)" >> purgecss-report.md
          echo "" >> purgecss-report.md
          echo "## File Sizes" >> purgecss-report.md
          for file in css/*.css; do
            if [ -f "css/optimized/$(basename $file)" ]; then
              original=$(stat -f%z "$file" 2>/dev/null || stat -c%s "$file")
              optimized=$(stat -f%z "css/optimized/$(basename $file)" 2>/dev/null || stat -c%s "css/optimized/$(basename $file)")
              reduction=$((100 - (optimized * 100 / original)))
              echo "- $(basename $file): $original → $optimized bytes (${reduction}% reduction)" >> purgecss-report.md
            fi
          done
      
      - name: Upload optimized CSS
        uses: actions/upload-artifact@v4
        with:
          name: optimized-css
          path: css/optimized/
      
      - name: Upload report
        uses: actions/upload-artifact@v4
        with:
          name: purgecss-report
          path: purgecss-report.md
```

#### JavaScript Unused Code Removal

**For JavaScript, be VERY careful.** Bootstrap JS depends on many functions.

**Safe Approach:**

1. **Don't touch Bootstrap JS** - It's already minified and optimized
2. **Only remove custom JS** that you're 100% sure is unused
3. **Use Chrome DevTools Coverage** to identify unused custom JS

**Manual JS Cleanup:**

```javascript
// Instead of removing, comment out unused functions
// and test for a week to ensure nothing breaks

/* 
// Unused function - commented out for testing
function unusedFeature() {
  // ... code ...
}
*/

// If no errors after a week, then delete permanently
```

#### Complete Example: Multi-Page Site

**Project Structure:**
```
your-site/
├── index.php
├── about.php
├── products.php
├── contact.php
├── css/
│   ├── bootstrap.min.css (from CDN originally)
│   ├── style.css
│   └── animate.min.css
└── js/
    ├── bootstrap.bundle.min.js
    └── custom.js
```

**Command to clean safely:**

```bash
# 1. Download Bootstrap from CDN
curl -o css/bootstrap-full.css https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css

# 2. Create PurgeCSS config (as shown above)

# 3. Run PurgeCSS on all pages
npx purgecss \
  --css css/bootstrap-full.css css/style.css css/animate.min.css \
  --content index.php about.php products.php contact.php "**/*.php" "js/**/*.js" \
  --output css/optimized \
  --safelist-deep col- row btn- navbar- d- m- p- text- bg-

# 4. Check file sizes
ls -lh css/
ls -lh css/optimized/

# 5. Test in browser before deploying
```

#### Pro Tips

1. **Start Conservative:** Keep more classes than you think you need. You can always remove more later.

2. **Test Dynamic Content:** If you load content with JavaScript/AJAX, those classes won't be detected. Add them to safelist.

3. **Check Email Templates:** If you send HTML emails, their CSS might be in your main files.

4. **Monitor Analytics:** After deploying, watch for increased bounce rates on specific pages (indicates broken layout).

5. **Keep Backups:** Always keep original files and ability to rollback quickly.

6. **Document Changes:** Note what was removed so you can add back if needed.

#### Troubleshooting

**Issue: Responsive layout broken on mobile**
```javascript
// Solution: Add more responsive class patterns to safelist
deep: [
  /^col-/,
  /^d-/,
  /^flex-/,
  /^order-/,
  /^offset-/,
]
```

**Issue: Dropdown menus don't work**
```javascript
// Solution: Add dropdown classes
standard: ['show', 'dropdown-menu-show'],
deep: [/^dropdown-/]
```

**Issue: Modals missing styles**
```javascript
// Solution: Add modal classes
deep: [/^modal-/],
standard: ['modal-open', 'modal-backdrop', 'fade', 'show']
```

---

### Practice 8: Minify CSS & JS Files

#### What It Means

Minification removes unnecessary spaces, comments, and line breaks from code. The code works exactly the same, but the file is much smaller.

#### Why It's Important

- **Smaller file sizes**: 30-50% reduction
- **Faster downloads**: Less data to transfer
- **Better performance**: Especially on mobile
- **Standard practice**: All production websites do this

#### How It Works

**Before (Readable):**
```css
/* This is a comment */
.header {
background-color: #ffffff;
padding: 20px;
margin: 10px;
}
```

**After (Minified):**
```css
.header{background-color:#fff;padding:20px;margin:10px}
```

#### Step-by-Step Process

1. **Take** original CSS/JS file
2. **Remove** all comments
3. **Remove** unnecessary spaces
4. **Remove** line breaks
5. **Save** as `.min.css` or `.min.js`

#### Tools for This

**Online Tools:**
- CSS Minifier: https://www.minifier.org/
- JS Minifier: https://www.minifier.org/

**Command Line:**
- **clean-css-cli**: For CSS files
- **terser**: For JavaScript files

#### Example Commands

```bash
# Minify CSS
npx clean-css-cli -o css/style.min.css css/style.css

# Minify JavaScript
npx terser js/custom.js -o js/custom.min.js -c -m
```

---

### Practice 9: Add Gzip Cache in .htaccess & Host Fonts Locally

#### What It Means

1. **Gzip Cache**: Tell browsers to cache compressed files so they don't need to download them again
2. **Host Fonts Locally**: Instead of loading fonts from Google, keep them on your server for faster loading

#### Why It's Important

- **Faster repeat visits**: Cached files load instantly
- **Reduced server load**: Less bandwidth usage
- **Better privacy**: No external font requests
- **Faster font loading**: No waiting for Google's servers

#### How It Works - Gzip Cache

Add rules to `.htaccess` file that:
1. Enable Gzip compression
2. Set cache headers for compressed files
3. Tell browsers to cache files for a long time

#### How It Works - Local Fonts

**Before (External):**
```html
<link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
```

**After (Local):**
```html
<link rel="preload" href="fonts/roboto.woff2" as="font" type="font/woff2" crossorigin>
<style>
@font-face {
font-family: 'Roboto';
src: url('fonts/roboto.woff2') format('woff2');
}
</style>
```

#### Step-by-Step Process - Gzip Cache

1. **Open** `.htaccess` file in website root
2. **Add** Gzip compression rules
3. **Add** cache headers for .gz files
4. **Test** that compression works

#### Step-by-Step Process - Local Fonts

1. **Download** font files (WOFF2 format)
2. **Save** in `fonts/` folder
3. **Create** `@font-face` declarations
4. **Update** HTML to use local fonts
5. **Remove** Google Fonts links

#### .htaccess Configuration

Add this to your `.htaccess` file:

```apache
# Enable Gzip Compression
<IfModule mod_deflate.c>
AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cache Gzipped Files
<IfModule mod_headers.c>
# Cache .gz files for 1 year
<FilesMatch "\.(css|js)\.gz$">
Header set Cache-Control "max-age=31536000, public"
Header set Content-Encoding "gzip"
Header set Content-Type "text/css; charset=UTF-8"
</FilesMatch>

# Serve .gz files when available
RewriteEngine On
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{REQUEST_FILENAME}\.gz -f
RewriteRule ^(.*)\.(css|js)$ $1.$2.gz [L]
</IfModule>

# Cache Images and Fonts
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/webp "access plus 1 year"
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/png "access plus 1 year"
ExpiresByType font/woff2 "access plus 1 year"
ExpiresByType font/woff "access plus 1 year"
</IfModule>
```

---

## GitHub Actions Automation

### What is GitHub Actions?

GitHub Actions is a tool that automatically runs tasks when you make changes to your website code. Instead of doing all the optimizations manually, GitHub Actions does them automatically.

### How It Works

1. **You push code** to GitHub
2. **GitHub Actions detects** the change
3. **It runs the optimization workflow** automatically
4. **It creates optimized files**
5. **It pushes optimized files** to a separate branch

### The Complete Workflow

Here's the complete GitHub Actions workflow file that automates all 9 practices:

```yaml
name: Website Optimization

# When to run: On push to main/optimized branches, or manually
on:
push:
branches:
- main
- optimized
workflow_dispatch:  # Allows manual triggering

jobs:
optimize:
runs-on: ubuntu-latest  # Uses Linux server

steps:
# Step 1: Get the code from GitHub
- name: Checkout code
uses: actions/checkout@v4
with:
fetch-depth: 0

# Step 2: Setup Node.js (needed for our scripts)
- name: Setup Node.js
uses: actions/setup-node@v4
with:
node-version: 20

# Step 3: Install required tools
- name: Install dependencies
run: |
# Update system packages
sudo apt-get update
# Install image processing tools
sudo apt-get install -y libvips-tools
# Install Node.js packages for optimization
npm install sharp glob
npm install --save-dev html-minifier-terser clean-css-cli terser purgecss
npm install @squoosh/cli@0.7.1

# Step 4: Create folder for optimized files
- name: Create optimized directory
run: mkdir -p optimized

# Step 5: Copy all files to optimized folder
- name: Copy files to optimized directory
run: |
cp -r * optimized/ 2>/dev/null || true
cd optimized
rm -rf .git .github  # Don't copy Git files

# ============================================
# IMAGE OPTIMIZATION (Practices 1 & 2)
# ===========================================

# Practice 1: Resize Images
- name: Generate Responsive Images
run: node js/resize-images.js
working-directory: optimized

# Practice 2: Convert Images to WebP
- name: Convert Images to WebP
run: node js/convert-webp.js
working-directory: optimized

# ============================================
# FILE COMPRESSION (Practice 4)
# ===========================================

# Practice 4: Gzip CSS and JS
- name: Gzip CSS and JS
run: |
find optimized/css optimized/js -type f \( -name "*.css" -o -name "*.js" \) -exec gzip -kf {} \;

# ============================================
# REPORT GENERATION
# ===========================================

- name: Create optimization report
run: |
cd optimized
echo "# Website Optimization Report" > optimization-report.md
echo "Generated: $(date)" >> optimization-report.md
echo "" >> optimization-report.md
echo "## File Counts" >> optimization-report.md
echo "- CSS: $(find css -name '*.css' | wc -l)" >> optimization-report.md
echo "- JS: $(find js -name '*.js' | wc -l)" >> optimization-report.md
echo "- Images: $(find images -type f | wc -l)" >> optimization-report.md

# ============================================
# DEPLOY OPTIMIZED FILES
# ===========================================

- name: Configure git
run: git remote set-url origin https://x-access-token:${{ secrets.GITHUB_TOKEN }}@github.com/YOUR_USERNAME/YOUR_REPO.git

- name: Commit and push optimized files
run: |
git config user.name "GitHub Actions"
git config user.email "actions@github.com"

git checkout -B optimized

# Remove everything and replace with optimized output
git rm -rf .
cp -r optimized/* .
rm -rf optimized

git add .
git commit -m "🔥 Optimized website assets (CSS/JS minified, images optimized)" || echo "No changes"
git push origin optimized --force

- name: Upload optimization report
uses: actions/upload-artifact@v4
with:
name: optimization-report
path: optimized/optimization-report.md
```

### Understanding the Workflow

#### Section 1: Setup
- **Checkout code**: Gets your website files
- **Setup Node.js**: Prepares the environment
- **Install dependencies**: Installs tools needed for optimization

#### Section 2: Image Optimization
- **Generate Responsive Images**: Creates multiple sizes (Practice 1)
- **Convert to WebP**: Converts images to WebP format (Practice 2)

#### Section 3: File Compression
- **Gzip CSS and JS**: Compresses files (Practice 4)

#### Section 4: Deployment
- **Commit and push**: Saves optimized files to GitHub

### How to Use GitHub Actions

1. **Create workflow file**: Save the YAML code above as `.github/workflows/optimize.yml`
2. **Push to GitHub**: Commit and push your code
3. **Workflow runs automatically**: GitHub Actions starts the process
4. **Check results**: Go to "Actions" tab in GitHub to see progress
5. **Use optimized branch**: Deploy from the `optimized` branch

### Manual Trigger

You can also trigger the workflow manually:
1. Go to GitHub repository
2. Click "Actions" tab
3. Select "Website Optimization" workflow
4. Click "Run workflow"
5. Click green "Run workflow" button

---

## Testing & Verification

### How to Verify Optimizations Are Working

#### 1. Check Image Sizes

**Before optimization:**
- Single large image: 2MB

**After optimization:**
- Multiple sizes: 50KB - 1MB
- WebP versions: 30-40% smaller

**How to check:**
1. Open website in browser
2. Right-click on image → "Inspect"
3. Check the `srcset` attribute
4. Verify WebP format is being used

#### 2. Check File Compression

**How to check Gzip:**
1. Open browser DevTools (F12)
2. Go to "Network" tab
3. Reload page
4. Click on a CSS or JS file
5. Check "Response Headers"
6. Look for `Content-Encoding: gzip`

#### 3. Check PageSpeed Score

**Using Google PageSpeed Insights:**
1. Go to https://pagespeed.web.dev/
2. Enter your website URL
3. Click "Analyze"
4. Check the score (should be 90-100)

**What to look for:**
- Performance score: 90-100
- Largest Contentful Paint (LCP): < 2.5s
- First Input Delay (FID): < 100ms
- Cumulative Layout Shift (CLS): < 0.1

#### 4. Check Network Usage

**Using Chrome DevTools:**
1. Open DevTools (F12)
2. Go to "Network" tab
3. Check "Disable cache"
4. Reload page
5. Look at "Total" size at bottom
6. Compare before and after optimization

**Expected results:**
- 50-70% reduction in total size
- Faster load time
- Fewer requests (if combining files)

#### 5. Check Image Formats

**How to verify WebP:**
1. Open website
2. Right-click image → "Inspect"
3. Check the `<source>` tags
4. Verify `type="image/webp"` is present
5. Check that browser is using WebP (in Network tab)

---

## Troubleshooting

### Common Issues and Solutions

#### Issue 1: Images Not Loading

**Symptoms:**
- Images show broken icon
- Console shows 404 errors

**Solutions:**
1. Check that image files exist in correct folders
2. Verify file paths in HTML are correct
3. Check file permissions (should be readable)
4. Verify WebP conversion completed

#### Issue 2: Website Looks Broken

**Symptoms:**
- Styles not applying
- Layout looks wrong

**Solutions:**
1. Check that CSS files are loading
2. Verify Gzip files are being served correctly
3. Check browser console for errors
4. Verify critical CSS is loading

#### Issue 3: GitHub Actions Fails

**Symptoms:**
- Workflow shows red X
- Error messages in Actions tab

**Solutions:**
1. Check error message in Actions log
2. Verify all required files exist
3. Check Node.js version compatibility
4. Verify file paths are correct
5. Check GitHub token permissions

#### Issue 4: Slow Performance Still

**Symptoms:**
- PageSpeed score still low
- Website still loads slowly

**Solutions:**
1. Verify all optimizations were applied
2. Check server response time
3. Verify CDN is working (if using one)
4. Check for large unoptimized files
5. Verify caching is enabled

#### Issue 5: WebP Not Working

**Symptoms:**
- Browser still loading JPG/PNG
- WebP files exist but not used

**Solutions:**
1. Check browser support (Chrome, Firefox, Edge support WebP)
2. Verify `<picture>` element is correct
3. Check that WebP files were created
4. Verify file paths in HTML

---

## Glossary

### Technical Terms Explained

**Async/Defer**
- Ways to load JavaScript without blocking page rendering
- `async`: Loads and executes immediately when ready
- `defer`: Loads in background, executes after page loads

**CDN (Content Delivery Network)**
- Network of servers that deliver content from locations close to users
- Reduces load time by serving files from nearby servers

**CLS (Cumulative Layout Shift)**
- Measures how much page content shifts during loading
- Lower is better (target: < 0.1)

**Critical CSS**
- CSS code needed to display above-the-fold content
- Should load first for faster visual appearance

**Gzip**
- Compression method that reduces file sizes
- Browser automatically decompresses files

**Lazy Loading**
- Technique where images load only when user scrolls near them
- Reduces initial page load time

**LCP (Largest Contentful Paint)**
- Time until largest content element is visible
- Target: < 2.5 seconds

**Minification**
- Process of removing unnecessary characters from code
- Reduces file size without changing functionality

**PageSpeed Score**
- Google's measure of website performance (0-100)
- Higher is better (target: 90-100)

**Preload**
- Tells browser to download resource early
- Improves load time for critical resources

**Render-Blocking**
- Resources that prevent page from displaying
- CSS and JS can be render-blocking if not optimized

**Responsive Images**
- Images that adapt to different screen sizes
- Provides right size for each device

**WebP**
- Modern image format by Google
- 25-35% smaller than JPG/PNG with same quality

**srcset**
- HTML attribute that provides multiple image sources
- Browser chooses best one based on screen size

---

## Quick Reference Checklist

Use this checklist to ensure all practices are implemented:

### Image Optimization
- [ ] Images resized to multiple sizes (360, 480, 640, 768, 1024, 1280, 1920)
- [ ] Images converted to WebP format
- [ ] `<img>` tags replaced with `<picture>` elements
- [ ] Lazy loading added to images
- [ ] Width and height attributes added

### CSS Optimization
- [ ] Critical CSS preloaded
- [ ] Non-critical CSS loaded asynchronously
- [ ] CSS files minified
- [ ] Unused CSS removed
- [ ] CSS files gzipped

### JavaScript Optimization
- [ ] Non-critical scripts use `defer` or `async`
- [ ] JavaScript files minified
- [ ] Unused JavaScript removed
- [ ] JavaScript files gzipped

### Server Configuration
- [ ] Gzip compression enabled in .htaccess
- [ ] Cache headers configured
- [ ] Fonts hosted locally
- [ ] .htaccess rules tested

### Automation
- [ ] GitHub Actions workflow created
- [ ] Workflow tested and working
- [ ] Optimized branch created
- [ ] Reports generated

### Testing
- [ ] PageSpeed score checked (90-100)
- [ ] Images loading correctly
- [ ] Website functionality verified
- [ ] Mobile performance tested
- [ ] Network usage verified

---

## Conclusion

By following these 9 practices, you can significantly improve your website's performance, user experience, and search engine rankings. The GitHub Actions workflow automates most of this process, making it easy to maintain optimizations as you add new content.

### Key Takeaways

1. **Image optimization** is the biggest win - can reduce page size by 50-70%
2. **Automation** saves time - GitHub Actions does the work for you
3. **Testing** is important - always verify optimizations work
4. **Maintenance** is ongoing - optimize new content as you add it

### Next Steps

1. Implement the practices one by one
2. Set up GitHub Actions workflow
3. Test thoroughly
4. Monitor performance regularly
5. Optimize new content as you add it

### Need Help?

If you encounter issues:
1. Check the Troubleshooting section
2. Review the GitHub Actions logs
3. Test each practice individually
4. Verify file paths and permissions

---

**Document Version:** 1.0  
**Last Updated:** 2025  
**For:** TechMech Website Optimization  
**Author:** AI Assistant

---

## Appendix: Complete Code Examples

### A. resize-images.js (Complete Code)

```javascript
const sharp = require("sharp");
const fs = require("fs");
const path = require("path");
const glob = require("glob");

// Image sizes to create (in pixels)
const sizes = [360, 480, 640, 768, 1024, 1280, 1920];

// Get absolute path to the current project directory
const projectRoot = path.resolve(".");

// Find all images inside any `images` folder in the project
glob
.sync("**/images/**/*.{jpg,jpeg,png}", {
cwd: projectRoot,
nodir: true,
absolute: false,
})
.forEach((relativePath) => {
const ext = path.extname(relativePath).toLowerCase();
const baseName = path.basename(relativePath, ext);
const dir = path.dirname(relativePath);
const absInputPath = path.join(projectRoot, relativePath);

// Create resized versions for each size
sizes.forEach((size) => {
const outputDir = path.join(projectRoot, dir, `${size}w`);
if (!fs.existsSync(outputDir)) {
fs.mkdirSync(outputDir, { recursive: true });
}

sharp(absInputPath)
.resize(size)
.toFile(path.join(outputDir, `${baseName}_${size}w${ext}`))
.then(() => {
console.log(`Created: ${outputDir}/${baseName}_${size}w${ext}`);
})
.catch((err) => console.error("Error processing", absInputPath, err));
});
});
```

### B. convert-webp.js (Complete Code)

```javascript
const sharp = require("sharp");
const fs = require("fs");
const path = require("path");
const glob = require("glob");

const projectRoot = path.resolve(".");

// Find all images
const imageFiles = glob.sync("**/images/**/*.{jpg,jpeg,png}", {
cwd: projectRoot,
nodir: true,
absolute: true,
});

console.log(`Found ${imageFiles.length} images to convert to WebP`);

// Convert each image to WebP
imageFiles.forEach((filePath) => {
const ext = path.extname(filePath).toLowerCase();
const outputPath = path.join(
path.dirname(filePath),
path.basename(filePath, ext) + ".webp"
);

sharp(filePath)
.toFormat("webp")
.toFile(outputPath)
.then(() => console.log(`Converted to WebP: ${outputPath}`))
.catch((err) => console.error(`Error converting ${filePath}:`, err));
});
```

### C. replace-img-with-picture.js (Complete Code)

```javascript
const fs = require("fs");
const path = require("path");
const cheerio = require("cheerio");

const sizes = [360, 480, 640, 768, 1024, 1280, 1920];

function processHTMLorPHP(filePath) {
let html = fs.readFileSync(filePath, "utf8");
const $ = cheerio.load(html, { decodeEntities: false });

$("img").each((_, el) => {
const $img = $(el);
const src = $img.attr("src");

if (!src || src.includes("data:")) return;

const ext = path.extname(src).toLowerCase();
const baseName = path.basename(src, ext);
const dirName = path.dirname(src);

const picture = $("<picture></picture>");

// Add WebP sources for different sizes
sizes.forEach((size) => {
picture.append(
`<source srcset="${dirName}/${size}w/${baseName}_${size}w.webp" media="(max-width: ${size}px)" type="image/webp">`
);
});

// Fallback <img> using WebP
const imgFallback = $img.clone();
imgFallback.attr("src", `${dirName}/${baseName}.webp`);
imgFallback.attr("loading", "lazy");
picture.append(imgFallback);

$img.replaceWith(picture);
});

fs.writeFileSync(filePath, $.html(), "utf8");
console.log(`✅ Updated <img> tags in ${filePath}`);
}

// Process all HTML and PHP files
function walk(dir) {
fs.readdirSync(dir).forEach((file) => {
const fullPath = path.join(dir, file);
const stat = fs.statSync(fullPath);

if (stat.isDirectory()) {
walk(fullPath);
} else if (/\.(php|html)$/i.test(file)) {
processHTMLorPHP(fullPath);
}
});
}

walk(path.resolve(__dirname, ".."));
```

### D. Complete .htaccess Configuration

```apache
# ============================================
# GZIP COMPRESSION
# ============================================
<IfModule mod_deflate.c>
# Compress HTML, CSS, JavaScript, Text, XML and fonts
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
AddOutputFilterByType DEFLATE application/x-font
AddOutputFilterByType DEFLATE application/x-font-opentype
AddOutputFilterByType DEFLATE application/x-font-otf
AddOutputFilterByType DEFLATE application/x-font-truetype
AddOutputFilterByType DEFLATE application/x-font-ttf
AddOutputFilterByType DEFLATE application/x-javascript
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE font/opentype
AddOutputFilterByType DEFLATE font/otf
AddOutputFilterByType DEFLATE font/ttf
AddOutputFilterByType DEFLATE image/svg+xml
AddOutputFilterByType DEFLATE image/x-icon
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/javascript
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/xml
</IfModule>

# ============================================
# BROWSER CACHING
# ============================================
<IfModule mod_expires.c>
ExpiresActive On

# Images
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/jpg "access plus 1 year"
ExpiresByType image/gif "access plus 1 year"
ExpiresByType image/png "access plus 1 year"
ExpiresByType image/webp "access plus 1 year"
ExpiresByType image/svg+xml "access plus 1 year"
ExpiresByType image/x-icon "access plus 1 year"

# Video
ExpiresByType video/mp4 "access plus 1 year"
ExpiresByType video/mpeg "access plus 1 year"

# CSS, JavaScript
ExpiresByType text/css "access plus 1 month"
ExpiresByType text/javascript "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
ExpiresByType application/x-javascript "access plus 1 month"

# Fonts
ExpiresByType font/ttf "access plus 1 year"
ExpiresByType font/otf "access plus 1 year"
ExpiresByType font/woff "access plus 1 year"
ExpiresByType font/woff2 "access plus 1 year"
ExpiresByType application/font-woff "access plus 1 year"
</IfModule>

# ============================================
# SERVE GZIPPED FILES
# ============================================
<IfModule mod_headers.c>
# Serve gzip compressed CSS and JS files if they exist
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{REQUEST_FILENAME}\.gz -f
RewriteRule ^(.*)\.(css|js)$ $1.$2.gz [L]

# Set proper MIME type for gzipped files
<FilesMatch "\.(css|js)\.gz$">
Header set Content-Encoding "gzip"
Header set Content-Type "text/css; charset=UTF-8"
</FilesMatch>
</IfModule>

# ============================================
# CACHE CONTROL HEADERS
# ============================================
<IfModule mod_headers.c>
# Cache images and fonts for 1 year
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf|otf)$">
Header set Cache-Control "max-age=31536000, public"
</FilesMatch>

# Cache CSS and JS for 1 month
<FilesMatch "\.(css|js)$">
Header set Cache-Control "max-age=2592000, public"
</FilesMatch>
</IfModule>
```

---

**End of Document**

