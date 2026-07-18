#!/bin/bash
set -e

# Fix file ownership for volume-mounted application directory
chown -R www-data:www-data /var/www/html 2>/dev/null || true

# Ensure install directory is writable
chmod -R 775 /var/www/html/install 2>/dev/null || true

exec "$@"
