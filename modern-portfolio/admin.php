<?php
// Legacy admin entrypoint: keep for backwards-compatibility.
// - Runtime: redirect to modern admin.
// - Static build: emit a tiny HTML bridge page without exiting the build process.

if (defined('STATIC_BUILD')) {
    echo '<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=admin"><title>Redirecting…</title></head><body><a href="admin">Continue to Admin</a></body></html>';
    return;
}

header('Location: admin');
exit;
