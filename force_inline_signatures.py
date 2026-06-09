import os
import glob
import re

pdf_dir = r'c:\xampp\htdocs\whiteapp1\document\checklist\type\pdf'
php_files = glob.glob(os.path.join(pdf_dir, '*.php'))

for file_path in php_files:
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    # We want to find <img ... alt="Inspector Signature"...> and <img ... alt="Client Signature"...>
    # and ensure they have width="60" height="25"
    
    def replace_img(match):
        img_tag = match.group(0)
        # remove existing width/height/style
        img_tag = re.sub(r'\bstyle="[^"]*"', '', img_tag)
        img_tag = re.sub(r'\bwidth="\d+"', '', img_tag)
        img_tag = re.sub(r'\bheight="\d+"', '', img_tag)
        
        # inject style="max-width: 60px; max-height: 25px;"
        img_tag = img_tag.replace('<img ', '<img style="max-width: 60px; max-height: 25px;" ')
        return img_tag

    # Match any img tag containing 'Signature' in alt
    new_content = re.sub(r'<img\s+[^>]*alt="[^"]*Signature[^"]*"[^>]*>', replace_img, content, flags=re.IGNORECASE)

    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {os.path.basename(file_path)}")

print("Done forcing inline sizes on all signature images.")
