# Api de puntos X,Y

## Requirimientos
- PHP >= 7.2.5
- Composer
- MySQL
- Postman
- Navegador Web
- Git

## Instalacion
- Crear una base de datos en mysql.
- Clonar repositorio
    ```
    $ git clone https://github.com/cesarlanz/points.git
    ```
- Editar los siguientes datos ubicados en el archivo .env del proyecto segun su servidor mysql.
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=points
    DB_USERNAME=root
    DB_PASSWORD=
    ```
- Ejecutar los siguientes comandos en la carpeta del proyecto:
    ```
    $ cd puntos 
    $ composer install
    $ php artisan migrate --seed
    $ php artisan serve
    ```
- Ir a la direccion especificada en el navegador, generalmente es: [http://localhost:8000](http://localhost:8000) Alli vera la especificacion de la api mediante swagger.
- Tambien puede importar la siguiente coleccion en postman y usar: [https://www.getpostman.com/collections/ce2621a2f0b34519ec96](https://www.getpostman.com/collections/ce2621a2f0b34519ec96)
    
