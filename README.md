🚀 ToDoAPI-Laravel-CLI es una aplicación básica de gestión de tareas desarrollada con Laravel que incluye una API REST y una interfaz de línea de comandos (CLI). Permite a los usuarios registrarse, iniciar sesión y administrar sus tareas de manera sencilla.

# Características
✅ Autenticación de usuarios con tokens  
✅ CRUD de tareas (crear, leer, actualizar y eliminar)  
✅ Búsqueda de tareas por título  
✅ Marcar tareas como completadas  
✅ Interfaz en línea de comandos (CLI) para gestionar tareas sin necesidad de un frontend  

# Tecnologías
🔹 Laravel 11  
🔹 MySQL  
🔹 API REST con autenticación mediante tokens  
🔹 Interfaz de línea de comandos (CLI) con Laravel Artisan  

# Instalación

1. Clonar el repositorio

```bash
git clone https://github.com/DannerVelaF/ToDoAPI-Laravel.git
```

2. Instalar dependencias con Composer Asegúrate de tener Composer instalado y ejecuta:

```bash
cd ToDoAPI-Laravel
composer install
```

3. Configurar el archivo .env Copia el archivo .env.example a .env:

```bash
cp .env.example .env
```

4. php artisan key:generate

```bash
php artisan key:generate
```

5. Configurar la base de datos Asegúrate de configurar las credenciales de MySQL en el archivo .env.

6. Ejecutar migraciones

```bash
php artisan migrate
```

7. Crea la clave para JWT

```bash
php artisan jwt:secret
```

8. Inicializa el servidor
```bash
php artisan serve
```

Con estos pasos podras hacer uso de la api.

# Uso de la CLI

La aplicación incluye una linea de comandos (CLI) para gestionar las tareas a través de la consola.

* Accede a la linea de comandos y prueba las opciones
```bash
php artisan app:toDO
```
![image](https://github.com/user-attachments/assets/bbbaaf08-e042-4e60-a810-2b8a22eb3ac9)


