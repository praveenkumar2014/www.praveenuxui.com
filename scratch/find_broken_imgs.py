import os
import re

workspace = "/Users/mac/Desktop/praveen/myportfolio"
broken_images = []

for root, dirs, files in os.walk(workspace):
    for file in files:
        if file.endswith((".php", ".html", ".js", ".css")):
            path = os.path.join(root, file)
            with open(path, "r", errors="ignore") as f:
                content = f.read()
                # Find img src="..."
                imgs = re.findall(r'src=["\']([^"\']+)["\']', content)
                # Find url(...) in css
                urls = re.findall(r'url\(["\']?([^"\'\)]+)["\']?\)', content)
                
                for img in imgs + urls:
                    if img.startswith(("http", "https", "data:", "#")):
                        continue
                    
                    # Clean up double slashes
                    clean_img = img.replace("//", "/")
                    
                    img_path = os.path.join(workspace, clean_img)
                    if not os.path.exists(img_path):
                        # Try relative to the file
                        rel_path = os.path.join(root, clean_img)
                        if not os.path.exists(rel_path):
                            broken_images.append((path, img))

for file_path, img in set(broken_images):
    print(f"File: {file_path} | Broken: {img}")
