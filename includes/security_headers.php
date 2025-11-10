<?php
// Security headers to protect against common attacks
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// For HTTPS only (uncomment in production with SSL)
// header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Content Security Policy (adjust as needed)
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://kit.fontawesome.com https://cdnjs.cloudflare.com 'unsafe-inline'; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com https://ka-f.fontawesome.com; img-src 'self' data:; connect-src 'self';");