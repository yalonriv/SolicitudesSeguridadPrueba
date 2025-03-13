# Proyecto: Solicitudes de Seguridad

## Instalación de Herramientas de Desarrollo

### 1. Instalación de Dependencias

Para desarrollar este proyecto, se requieren las siguientes herramientas:
- **XAMPP** (Versión 8.2.12) - Descarga desde [Apache Friends](https://www.apachefriends.org/es/download.html)
- **Composer** (Versión 2.8.6) - Descarga desde [Composer](https://getcomposer.org/download/)
- **Node.js** - Descarga desde [Node.js](https://nodejs.org/es)

### 2. Instalación del Backend (Laravel)

Ejecuta el siguiente comando en la carpeta donde deseas copiar el proyecto:
```sh
composer create-project laravel/laravel backsolicitudes-app
```

Para verificar que el servidor funcione, ejecuta:
```sh
php artisan serve
```

### 3. Creación de la Base de Datos

1. Accede a MySQL desde la terminal:
   ```sh
   mysql -u root -p
   ```
   *Nota:* No se requiere contraseña si no ha sido establecida.

2. Crea la base de datos:
   ```sql
   CREATE DATABASE solicitudes_seg_bd;
   ```

3. Verifica que la base de datos fue creada:
   ```sql
   SHOW DATABASES;
   ```

4. Configura el archivo `.env` en el proyecto Laravel:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=solicitudes_seg_bd
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Ejecuta las migraciones para crear las tablas predeterminadas:
   ```sh
   php artisan migrate
   ```

6. Crea las migraciones necesarias para las tablas del proyecto:
   ```sh
   php artisan make:migration create_candidatos_table
   php artisan make:migration create_solicitudes_table
   php artisan make:migration create_tipos_estudio_table
   ```

7. Edita las migraciones según los campos necesarios y ejecuta nuevamente:
   ```sh
   php artisan migrate
   ```

8. Confirma que las tablas fueron creadas:
   ```sql
   SHOW TABLES;
   ```

### 4. Instalación del Frontend (Angular)

1. Instala Angular CLI de manera global:
   ```sh
   npm install -g @angular/cli
   ```

2. Verifica la instalación:
   ```sh
   ng version
   ```

3. Crea el proyecto frontend:
   ```sh
   ng new frontsolicitudes-app
   ```

4. Navega al proyecto y ejecuta:
   ```sh
   cd frontsolicitudes-app
   ng serve
   ```

5. Instala Bootstrap para mejorar los estilos:
   ```sh
   npm install bootstrap
   ```
   Luego, agrega Bootstrap en `angular.json`:
   ```json
   "styles": [
     "src/styles.css",
     "node_modules/bootstrap/dist/css/bootstrap.min.css"
   ]
   ```

---
## Despliegue del Proyecto

1. Para iniciar el **backend**, navega a `backsolicitudes-app` y ejecuta:
   ```sh
   php artisan serve
   ```
2. Para iniciar el **frontend**, navega a `frontsolicitudes-app` y ejecuta:
   ```sh
   ng serve
   ```

*Nota:* Asegúrate de que el servidor MySQL esté iniciado en XAMPP para que las peticiones funcionen correctamente.

Si deseas probar los endpoints sin frontend, puedes usar Postman o la extensión **Thunder Client** en Visual Studio Code.

---
## Estructura del Proyecto

La estructura general del proyecto es la siguiente:
```
solicitudesSeguridadPrueba/
├── backsolicitudes-app/
├── frontsolicitudes-app/
```

### Estructura del Backend (Laravel)
```
backsolicitudes-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── CandidatosController.php
│   │   │   ├── SolicitudesController.php
│   │   │   ├── TiposEstudioController.php
│   │   ├── Requests/
│   │   │   ├── FiltroSolicitudDTO.php
│   ├── Models/
│   │   ├── Candidato.php
│   │   ├── Solicitud.php
│   │   ├── TipoEstudio.php
│   │   ├── User.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_tipos_estudio_table.php
│   │   ├── create_candidatos_table.php
│   │   ├── create_solicitudes_table.php
├── routes/
│   ├── api.php
│   ├── web.php
├── .env
```

### Estructura del Frontend (Angular)
```
frontsolicitudes-app/
├── src/
│   ├── app/
│   │   ├── components/
│   │   │   ├── footer/
│   │   │   ├── header/
│   │   ├── pages/
│   │   │   ├── dashboard/
│   │   │   ├── candidatos/
│   │   │   ├── solicitudes/
│   │   │   ├── login/
│   │   ├── services/
│   │   │   ├── auth.service.ts
│   │   │   ├── candidatos.service.ts
│   │   │   ├── solicitudes.service.ts
│   │   ├── models/
│   │   │   ├── candidato-dto.ts
│   │   │   ├── solicitud-dto.ts
│   │   │   ├── tipo-estudio-dto.ts
│   │   │   ├── filtro-solicitud-dto.ts
│   │   │   ├── solicitudes-estado-dto.ts
│   │   ├── guards/
│   │   │   ├── auth.guard.ts
│   ├── environments/
│   ├── assets/
│   ├── styles.css
│   ├── index.html
│   ├── app.module.ts
│   ├── app-routing.module.ts
```

---
## Tecnologías y Librerías Utilizadas
- **Laravel** (Backend)
- **Angular 17.3.12** (Frontend)
- **XAMPP** (PHP 8.2.12 y MySQL)
- **Composer 2.8.6**
- **Bootstrap** (Estilos)
- **Thunder Client** (Extensión para pruebas de API en VS Code)

---
## Decisiones Técnicas
- Se eligió MySQL en lugar de SQLite para mejorar la integridad de datos y restricciones.
- Se implementó un **Auth Guard** en Angular para restringir el acceso a las rutas protegidas.

---
## Credenciales de Prueba
- **Usuario:** admin@example.com
- **Contraseña:** admin

