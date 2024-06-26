#!/bin/bash

cd /var/www/quizchef
git pull origin main
npm run build
php artisan route:cache
php artisan config:cache
php artisan migrate:fresh --force
php artisan optimize
