migrate-up:
	php artisan migrate

migrate-down:
	php artisan migrate:rollback

seed-up:
	php artisan db:seed --class=InitSeeder

seed-down:
	php artisan db:seed-down --class=InitSeeder

install:
	npm install
	composer install

clear: 
	php artisan cache:clear
	php artisan view:clear
	php artisan config:clear
	php artisan route:clear

link:
	ln -s storage/app/public public/storage