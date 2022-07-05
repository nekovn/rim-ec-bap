@echo off
docker-compose exec bapec /bin/bash -c "cd /var/www/html/bapec && npm run watch"
