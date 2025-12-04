@echo off
cd C:\xampp\htdocs\money-transfer\WebProject
composer install --no-interaction
composer dump-autoload -o
php artisan config:clear
php artisan cache:clear
echo Installation complete!
pause
