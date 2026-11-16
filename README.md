# Registro de Retrospectivas Ágiles

Aplicación web para el registro y seguimiento de retrospectivas Scrum, desarrollada con **PHP 8+ (MySQLi)**, **MySQL/MariaDB** y arquitectura **MVC tradicional** con inyección de dependencias.

## Características

- ✅ Gestión de Sprints (crear, editar, eliminar, listar)
- ✅ Registro de items de retrospectiva por categorías:
  - **Logros** (¿Qué funcionó bien?)
  - **Impedimentos** (¿Qué necesita mejorar?)
  - **Acciones** (Compromisos para el próximo sprint)
  - **Comentarios** (Observaciones adicionales)
- ✅ Seguimiento de cumplimiento de acciones
- ✅ Carga automática de acciones del sprint anterior para revisar cumplimiento
- ✅ Historial completo de retrospectivas con filtros
- ✅ Interfaz responsive y moderna
- ✅ Sin necesidad de login (ideal para uso educativo)
- ✅ Arquitectura limpia: Entidades, Repositorios, Controladores, Mappers, Config

## Requisitos

- **Servidor web**: Apache/Nginx con PHP 7.4+
- **Base de datos**: MySQL 5.7+ / MariaDB 10.3+
- **Extensiones PHP**: mysqli

## Instalación

### 1. Colocar los archivos

Coloca los archivos en tu directorio web (ej: `htdocs/retrospectivas` o `var/www/html/retrospectivas`)

### 2. Configurar la base de datos

Ejecutar en MySQL/MariaDB:

```sql
-- Ejecutar el archivo database.sql incluido
SOURCE database.sql;
```

O ejecutar manualmente:

```sql
CREATE DATABASE IF NOT EXISTS registro_retro_db;
USE registro_retro_db;

-- Tabla de Sprints
CREATE TABLE sprints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla única para items de retrospectiva
CREATE TABLE retro_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sprint_id INT NOT NULL,
    categoria ENUM('accion', 'logro', 'impedimento', 'comentario', 'otro') NOT NULL,
    descripcion TEXT NOT NULL,
    cumplida BOOLEAN DEFAULT NULL,
    fecha_revision DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sprint_id) REFERENCES sprints(id) ON DELETE CASCADE
);
```

### 3. Configurar conexión a base de datos

Edita `models/database/RetrospectiveDB.php` con tus credenciales:

```php
private string $host_db = "localhost";
private string $user_db = "root";
private string $pwd_db = "";
private string $name_db = "registro_retro_db";
private int $port_db = 3306;
```

### 4. Configurar servidor web

**Apache** - Asegúrate de que `mod_rewrite` esté habilitado (opcional, para URLs limpias):

```apache
<VirtualHost *:80>
    DocumentRoot "/ruta/a/retrospectivas"
    ServerName retrospectivas.local
    
    <Directory "/ruta/a/retrospectivas">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. Probar la aplicación

Accede a `http://localhost/retrospectivas` (o tu dominio configurado)

## Estructura del Proyecto

```
retrospectivas/
├── index.php                    # Entry point (redirige a views/sprints.php)
├── database.sql                 # Script de base de datos
├── css/
│   └── styles.css              # Estilos CSS
├── models/
│   ├── entities/
│   │   ├── Sprint.php          # Entidad Sprint
│   │   └── RetroItem.php       # Entidad Item Retrospectiva
│   ├── database/
│   │   ├── RetrospectiveDBInterface.php  # Interfaz BD
│   │   └── RetrospectiveDB.php           # Implementación MySQLi
│   ├── mappers/
│   │   ├── SprintMapper.php    # Mapper BD -> Entidad Sprint
│   │   └── RetroItemMapper.php # Mapper BD -> Entidad RetroItem
│   ├── repositories/
│   │   ├── sprint_repository/
│   │   │   ├── SprintRepositoryInterface.php
│   │   │   └── SprintRepository.php
│   │   └── retro_item_repository/
│   │       ├── RetroItemRepositoryInterface.php
│   │       └── RetroItemRepository.php
│   └── config/
│       └── ModelsConfig.php    # Factory de modelos/repositorios
├── controllers/
│   ├── mappers/
│   │   ├── SprintMapperController.php    # Mapper Request -> Entidad
│   │   └── RetroItemMapperController.php
│   ├── sprints/
│   │   ├── SprintControllerInterface.php
│   │   └── SprintController.php
│   ├── retro_items/
│   │   ├── RetroItemControllerInterface.php
│   │   └── RetroItemController.php
│   └── config/
│       └── ControllersConfig.php # Factory de controladores
├── views/
│   ├── sprints.php             # Listado de sprints
│   ├── sprint_form.php         # Formulario crear/editar sprint
│   ├── retrospectiva.php       # Formulario retrospectiva por sprint
│   ├── item_form.php           # Formulario crear/editar item
│   ├── historial.php           # Historial de retrospectivas
│   └── operaciones/
│       ├── sprints/
│       │   ├── registrar_sprint.php
│       │   ├── modificar_sprint.php
│       │   └── borrar_sprint.php
│       └── retro_items/
│           ├── registrar_item.php
│           ├── modificar_item.php
│           ├── borrar_item.php
│           └── actualizar_accion_cumplida.php
```

## Uso

### 1. Crear un Sprint
- Ve a **Sprints** (página principal)
- Click en **"Nuevo Sprint"**
- Completa nombre y fechas
- Guarda

### 2. Registrar Retrospectiva
- Ve a la pestaña **"Retrospectiva"**
- Selecciona el sprint en el desplegable
- Si hay acciones del sprint anterior, aparecerán arriba para revisar cumplimiento
- Click en **"Agregar"** en cada sección (Logros, Impedimentos, Acciones, Comentarios)
- Completa la descripción
- Para acciones: marca si está cumplida y fecha de revisión
- Guarda

### 3. Consultar Historial
- Ve a la pestaña **"Historial"**
- Filtra por sprint si deseas
- Verás todas las retrospectivas organizadas por sprint y categoría

## Arquitectura

La aplicación sigue el patrón **MVC con inyección de dependencias**:

```
Request → View → Controller → Repository → Database
                ↑              ↑
           Mapper         Mapper
          (Request)      (Database)
```

### Capas:

1. **Entities** (`models/entities/`): Objetos de dominio puros (Sprint, RetroItem)
2. **Database** (`models/database/`): Conexión MySQLi y ejecución de queries
3. **Mappers** (`models/mappers/`): Conversión BD ↔ Entidad
4. **Repositories** (`models/repositories/`): Lógica de acceso a datos
5. **Controller Mappers** (`controllers/mappers/`): Conversión Request ↔ Entidad
6. **Controllers** (`controllers/*/`): Lógica de negocio y orquestación
7. **Config** (`*config/`): Factories para inyección de dependencias
8. **Views** (`views/`): PHP templates con HTML embebido

### Principios aplicados:
- **Dependency Inversion**: Controllers dependen de interfaces, no de implementaciones
- **Single Responsibility**: Cada clase tiene una responsabilidad única
- **Interface Segregation**: Interfaces específicas por repositorio/controlador
- **No ORM**: SQL directo para simplicidad y control

## Tecnologías

- **Backend**: PHP 8+ (POO, Namespaces, Interfaces, Type Hints)
- **Base de datos**: MySQL/MariaDB (MySQLi prepared statements)
- **Frontend**: HTML5, CSS3 (Custom Properties, Flexbox/Grid), Vanilla JS mínimo
- **Iconos**: Font Awesome 6 (CDN)

## Licencia

MIT - Libre para uso educativo y comercial.

## Autor

Desarrollado para facilitar el aprendizaje y práctica de retrospectivas Scrum, siguiendo principios de arquitectura limpia y el patrón MVC tradicional de PHP.