#!/bin/bash

# Make migrations
php artisan migrate --force

# Create public link on storage
php artisan storage:link

# Make storage/app/public a www-data user's directory
chown -R www-data storage/app/public

# Cache data
php artisan optimize:clear
php artisan config:cache
php artisan optimize

# Start supervisor daemon
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
