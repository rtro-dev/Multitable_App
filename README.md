## Descargar proyecto

git clone https://github.com/rtro-dev/Multitable_App.git

sudo chown -R user:www-data Multitable_App/  
sudo chmod -R 775 Multitable_App/

En phpMyAdmin se crea la Base de Datos y el usuario con privilegios

cd multitableApp/

(Durante la creación, si se ha descargado no hace falta)
php artisan make:model User -mcr
php artisan make:model Category -mcr
php artisan make:model Sale -mcr
php artisan make:model Setting -mcr
php artisan make:model Image -mcr

sudo composer require laravel/ui
php artisan ui:auth

sudo composer install

Actualizar el .env

php artisan migrate

## Inicio del proyecto

Tras crear las migraciones:

1. Se editan los modelos creados
2. Se añaden las rutas