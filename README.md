# Proceso de instalación
Deberán tener PHP configurado en el PATH del sistema a nivel global.

Clonar el repositorio mediante git clone en el folder del servidor Web que se tenga configurado.

Ejecutar el siguiente comando en la raíz del proyecto para recontruir e instalar lo paquetes necesarios:
```bash
php composer.phar install
```
En el gestor de bases de datos se deberá configurar un usuario root con password root para probar el proyecto.

Ejecutar el siguiente comando para crear la base de datos:
```bash
php bin/console doctrine:database:create
```

Ejecutar el siguiente comando para crear lo necesario para el sistema:
```bash
php bin/console doctrine:migrations:migrate
```

Acceder a la URL definida.
