#!/bin/bash

cd /var/www/quizchef
git stash
git pull
git stash pop
npm run build
php artisan route:cache
php artisan config:cache
php artisan migrate:fresh --force
php artisan optimize
