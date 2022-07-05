@echo off
docker-compose exec bapec /bin/bash -c "cd /var/www/html/bapec && php artisan cache:clear && php artisan config:clear && rm -f bootstrap/cache/config.php"
pause