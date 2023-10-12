## Herramientas necesarias

Instalar Docker

## .env
Crear nuevo archivo .env y copiar el contenido del archivo .env.example, dentro de este se encuentran los datos para acceder al contenedor de mysql 

## Crear contenedores docker-compose

Ejecute  ```docker-compose build``` para levantar los contenedores del proyecto

## Uso con Docker

Abra el contenedor de la app ejecutando ```docker-compose exec app sh``` e instale todas las dependencias con el siguiente comando: ``` composer install```, crear nueva key dentro del contenedor app
```
php artisan key:generate
```

Luego ir a `http://localhost:8081/` para poder acceder a las apis expuestas en este proyecto.

## Ejecutar Migraciones, Seed dentro del contenedor app

Abra el contenedor de la aplicación ``` docker-compose exec app sh``` y corras las migraciones ```php artisan migrate```, luego ejecute los seed para cargar los datos de prueba ejecutando:
```php artisan db:seed```.

Detener a los contenedores:
```
docker-compose stop
```

Dando de baja a los contenedores:

```
docker-compose down
```
