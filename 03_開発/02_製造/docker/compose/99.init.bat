@echo off
docker-compose build --no-cache
timeout /t 2 > nul
docker-compose up -d
timeout /t 2 > nul
docker-compose exec bapec /bin/bash -c "cd /var/www/html/bapec && composer install && npm install && chmod -R 777 /var/www/html/bapec/storage && php artisan storage:link && service httpd start"
pause
