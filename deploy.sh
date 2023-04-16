#!/bin/sh
set -e

git pull

composer2 install

php artisan migrate
php artisan optimize
php artisan app:fill-it-user-branch

cd public
rm -rf build
unzip build.zip
rm build.zip
