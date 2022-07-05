@echo off
docker-compose exec bapec /bin/bash -c "cd /var/www/html/bapec && php artisan cache:clear && php artisan config:clear && php artisan config:cache && php artisan route:clear && php artisan view:clear && php artisan clear-compiled && composer dump-autoload && rm -f bootstrap/cache/config.php"
pause