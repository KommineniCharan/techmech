# WebP Image Conversion Guide

## Overview
Converting images to WebP format can reduce file sizes by 25-35% while maintaining visual quality, significantly improving page load times and PageSpeed scores.

## Option 1: Using Online Tools (Recommended for Quick Start)

### Bulk Conversion Tools:
1. **Squoosh** (Google): https://squoosh.app/
   - Drag and drop multiple images
   - Batch conversion support
   - Quality control

2. **CloudConvert**: https://cloudconvert.com/jpg-to-webp
   - Batch conversion API available
   - Free tier: 25 conversions/day

3. **Convertio**: https://convertio.co/jpg-webp/
   - Batch upload
   - Free for up to 10 files at a time

### Steps:
1. Upload all images from `images/` directory
2. Set quality to 85 (optimal balance)
3. Download WebP versions
4. Place them in the same directory structure with `.webp` extension
5. The code will automatically use WebP if available

## Option 2: Using Command Line Tools

### Install cwebp (Google WebP Tools)

**Windows:**
1. Download from: https://developers.google.com/speed/webp/download
2. Extract to a folder (e.g., `C:\webp\`)
3. Add to PATH or use full path

**Conversion Command:**
```batch
for /r "E:\xampp\htdocs\techmech\images" %f in (*.jpg *.jpeg *.png) do "C:\webp\cwebp.exe" -q 85 "%f" -o "%~dpnf.webp"
```

### Install ImageMagick (Alternative)

**Windows:**
1. Download from: https://imagemagick.org/script/download.php
2. Install with WebP support

**Conversion Command:**
```batch
magick mogrify -format webp -quality 85 -path "E:\xampp\htdocs\techmech\images" "E:\xampp\htdocs\techmech\images\**\*.{jpg,jpeg,png}"
```

## Option 3: Enable PHP GD with WebP Support

### For XAMPP:

1. **Check current PHP version:**
   ```bash
   php -v
   php -m | findstr gd
   ```

2. **Enable WebP in php.ini:**
   - Open `C:\xampp\php\php.ini`
   - Find `;extension=gd` and change to `extension=gd`
   - Restart Apache

3. **Verify WebP support:**
   ```bash
   php -r "var_dump(function_exists('imagewebp'));"
   ```

4. **Run conversion script:**
   ```bash
   php convert-to-webp.php
   ```

## Option 4: Using Node.js Script

If you have Node.js installed:

```javascript
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

function convertToWebP(dir) {
  const files = fs.readdirSync(dir);
  
  files.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    
    if (stat.isDirectory()) {
      convertToWebP(filePath);
    } else if (/\.(jpg|jpeg|png)$/i.test(file)) {
      const webpPath = filePath.replace(/\.(jpg|jpeg|png)$/i, '.webp');
      sharp(filePath)
        .webp({ quality: 85 })
        .toFile(webpPath)
        .then(() => console.log(`Converted: ${filePath}`))
        .catch(err => console.error(`Error converting ${filePath}:`, err));
    }
  });
}

convertToWebP('./images');
```

Install: `npm install sharp`

## Image Quality Recommendations

- **Product Images:** 85-90 (high quality needed)
- **Gallery Images:** 80-85 (good balance)
- **Client Logos:** 85 (clear text/logos)
- **Banner Images:** 80-85
- **Thumbnails:** 75-80 (smaller files)

## Directory Structure After Conversion

Your images directory should have both formats:
```
images/
├── products/
│   ├── eot.jpg
│   ├── eot.webp  ← New WebP version
│   ├── jib.jpg
│   ├── jib.webp  ← New WebP version
│   └── ...
├── gallery/
│   ├── 01.jpg
│   ├── 01.webp  ← New WebP version
│   └── ...
└── client-logos/
    ├── 1.jpg
    ├── 1.webp  ← New WebP version
    └── ...
```

## Automatic Fallback

The code has been updated to automatically:
1. Check for WebP version first
2. Fall back to JPG/PNG if WebP doesn't exist
3. No changes needed to existing code once WebP files are created

## Testing

After conversion:
1. Check browser DevTools Network tab
2. Verify images are loading as `.webp`
3. Check file sizes (should be smaller)
4. Test PageSpeed Insights again

## Expected File Size Reductions

- **JPG → WebP:** 25-35% smaller
- **PNG → WebP:** 50-80% smaller (if PNG has many colors)
- **Overall:** Expect 30-40% reduction in total image payload

## Priority Images to Convert First

1. **Above-the-fold images:**
   - `images/about-us.webp` (already done)
   - Logo images
   - Hero/banner images

2. **High-traffic images:**
   - Product images (`images/products/*.jpg`)
   - Gallery images (`images/gallery/*.jpg`)
   - Client logos (`images/client-logos/*.jpg`)

3. **Largest files:**
   - Convert largest images first for maximum impact

## Quick Start (Recommended)

1. Use **Squoosh** (https://squoosh.app/) to batch convert:
   - Upload entire `images` folder
   - Set quality: 85
   - Download WebP versions
   - Place in same directories

2. Or use the PHP script after enabling WebP in PHP:
   - Enable GD extension with WebP
   - Run: `php convert-to-webp.php`

3. Verify conversion:
   - Check that `.webp` files exist alongside originals
   - Test the website - images should load faster

## Troubleshooting

### WebP not loading?
- Check that `.webp` files exist in the same directory
- Verify file permissions
- Clear browser cache
- Check browser console for errors

### PHP WebP conversion not working?
- Verify GD extension is enabled: `php -m | grep gd`
- Check WebP support: `php -r "var_dump(function_exists('imagewebp'));"`
- Update PHP if needed (PHP 5.5+ required for WebP)

### Images look different?
- Adjust quality setting (try 90 instead of 85)
- Check if source images are high quality
- Some images may need manual adjustment

---

**Note:** The website code is already set up to use WebP automatically. Just convert the images and place them in the same directories with `.webp` extension.

