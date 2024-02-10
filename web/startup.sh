!/bin/sh

#copy env
cp .env.production .env
# install
composer install --prefer-dist --no-scripts --no-autoloader

# secret
php artisan key:generate

php artisan migrate

composer dump-autoload --no-scripts --optimize