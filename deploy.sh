#!/bin/sh
set -e

git pull

composer install
composer update

php artisan migrate
php artisan optimize

cd public
rm -rf build
unzip build.zip
rm build.zip
