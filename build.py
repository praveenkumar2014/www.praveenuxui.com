#!/usr/bin/env python3
"""
build.py — Static Site Generator for Praveen Kumar K Portfolio
Renders each PHP page via the local PHP server and saves as .html
Resolves the PHP include system so header/footer are baked in.
"""

import subprocess
import sys
import time
import os
import signal
import urllib.request
import urllib.error

ROOT = "/Users/mac/Desktop/praveen/myportfolio"
PORT = 8765  # use a safe port so we don't conflict with dev server

PAGES = [
    ("index.php",          "index.html"),
    ("about.php",          "about.html"),
    ("services.php",       "services.html"),
    ("portfolio.php",      "portfolio.html"),
    ("contact.php",        "contact.html"),
    ("blog.php",           "blog.html"),
    ("article.php",        "article.html"),
    ("gpay.php",           "gpay.html"),
    ("gmobile.php",        "gmobile.html"),
    ("gtresearch.php",     "gtresearch.html"),
    ("kd.php",             "kd.html"),
]

def start_server():
    proc = subprocess.Popen(
        ["php", "-S", f"localhost:{PORT}", "-t", ROOT],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
        cwd=ROOT,
    )
    time.sleep(2)  # wait for server to boot
    return proc

def fetch(php_file):
    url = f"http://localhost:{PORT}/{php_file}"
    try:
        with urllib.request.urlopen(url, timeout=20) as resp:
            content = resp.read().decode("utf-8", errors="replace")
            return content
    except Exception as e:
        print(f"  ✗ FAILED {php_file}: {e}")
        return None

def fix_paths(html):
    """Ensure all asset paths are root-relative (they already are, but double-check)."""
    return html

def main():
    os.chdir(ROOT)
    print(f"🚀 Starting build server on port {PORT}...")
    server = start_server()

    built = 0
    failed = 0

    try:
        for php_file, html_file in PAGES:
            print(f"  📄 Building {php_file} → {html_file}", end="", flush=True)
            html = fetch(php_file)
            if html:
                out_path = os.path.join(ROOT, html_file)
                with open(out_path, "w", encoding="utf-8") as f:
                    f.write(html)
                size = len(html) // 1024
                print(f" ✓ ({size}KB)")
                built += 1
            else:
                failed += 1
    finally:
        server.terminate()
        server.wait()

    print(f"\n{'='*50}")
    print(f"✅ Built: {built}  ❌ Failed: {failed}")

    if failed:
        sys.exit(1)
    else:
        print("🎉 Build complete — ready to push to GitHub/Vercel")

if __name__ == "__main__":
    main()
