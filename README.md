# Kimia Deployment

### Pull the latest changes from the git repository
    git pull origin main

### Install/update composer dependecies
    composer install --no-interaction --prefer-dist --optimize-autoloader

### Run database migrations
    php artisan migrate --force

### Clear caches
    php artisan cache:clear

### Clear expired password reset tokens
    php artisan auth:clear-resets

### Clear and cache routes
    php artisan route:clear
    php artisan route:cache

### Clear and cache config
    php artisan config:clear
    php artisan config:cache

### Install node modules
    npm install

### Build assets using Laravel Mix
    npm run production
    

## Seed DB (Don't run this everytime, just run it once on installation)
    php artisan db:seed
