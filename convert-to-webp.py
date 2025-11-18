#!/usr/bin/env python3
"""
Convert all JPG/PNG images to WebP format
Requires: pip install Pillow
"""

import os
import sys
from pathlib import Path

try:
    from PIL import Image
except ImportError:
    print("Error: Pillow library not found.")
    print("Install it with: pip install Pillow")
    sys.exit(1)

def convert_to_webp(input_path, output_path, quality=85):
    """Convert image to WebP format"""
    try:
        with Image.open(input_path) as img:
            # Convert RGBA to RGB if necessary (for JPG compatibility)
            if img.mode in ('RGBA', 'LA', 'P'):
                # Create white background for transparent images
                rgb_img = Image.new('RGB', img.size, (255, 255, 255))
                if img.mode == 'P':
                    img = img.convert('RGBA')
                rgb_img.paste(img, mask=img.split()[-1] if img.mode in ('RGBA', 'LA') else None)
                img = rgb_img
            elif img.mode != 'RGB':
                img = img.convert('RGB')
            
            # Save as WebP
            img.save(output_path, 'WEBP', quality=quality, method=6)
            return True
    except Exception as e:
        print(f"  ✗ Error: {e}")
        return False

def main():
    base_dir = Path('images')
    if not base_dir.exists():
        print(f"Error: {base_dir} directory not found!")
        sys.exit(1)
    
    quality = 85
    converted = 0
    skipped = 0
    errors = 0
    
    print("=== WebP Image Converter ===\n")
    print(f"Quality: {quality}")
    print(f"Target directory: {base_dir}\n")
    
    # Find all images
    image_extensions = ['.jpg', '.jpeg', '.png']
    images = []
    
    for ext in image_extensions:
        images.extend(base_dir.rglob(f'*{ext}'))
        images.extend(base_dir.rglob(f'*{ext.upper()}'))
    
    if not images:
        print("No images found to convert.")
        sys.exit(0)
    
    print(f"Found {len(images)} images to process.\n")
    
    for img_path in images:
        # Create WebP path
        webp_path = img_path.with_suffix('.webp')
        
        # Skip if WebP already exists
        if webp_path.exists():
            print(f"⏭  Skipping: {img_path.relative_to(base_dir)} (WebP exists)")
            skipped += 1
            continue
        
        print(f"🔄 Converting: {img_path.relative_to(base_dir)}...", end=' ')
        
        if convert_to_webp(img_path, webp_path, quality):
            # Get file sizes
            original_size = img_path.stat().st_size
            webp_size = webp_path.stat().st_size
            reduction = ((original_size - webp_size) / original_size) * 100
            
            print(f"✓ ({reduction:.1f}% smaller)")
            converted += 1
        else:
            errors += 1
    
    print(f"\n=== Conversion Complete ===")
    print(f"✅ Converted: {converted}")
    print(f"⏭  Skipped: {skipped}")
    print(f"✗ Errors: {errors}")
    print(f"\nTotal WebP files created: {converted}")

if __name__ == '__main__':
    main()

