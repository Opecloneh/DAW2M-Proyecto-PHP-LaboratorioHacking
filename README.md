# 🟣 VioletBox - Laboratorio Básico de Hacking Ético

## 📝 Descripción del dominio del problema

**VioletBox** es un laboratorio muy básico de hacking ético diseñado para principiantes que no tienen conocimientos o tienen conocimientos muy básicos de ciberseguridad.

La aplicación permite practicar vulnerabilidades comunes en un entorno controlado y educativo, como por ejemplo:

- SQL Injection (SQLi)
- Cross-Site Scripting (XSS)
- IDOR (Insecure Direct Object Reference)
- Subida de archivos insegura

El objetivo es que el usuario entienda cómo funcionan estas vulnerabilidades y cómo prevenirlas.

Además, la aplicación incluye un sistema de gestión de usuarios con roles:

- ROLE_USER → Puede acceder a los laboratorios y al contenido educativo.
- ROLE_ADMIN → Puede acceder al panel de administración y gestionar usuarios (CRUD).

---

## 🚀 Instrucciones para ejecutar la aplicación

### Requisitos

- PHP 8.x
- Symfony CLI
- MySQL
- Composer
- Docker + phpMyAdmin

---

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/Opecloneh/DAW2M-Proyecto-PHP-LaboratorioHacking.git
cd DAW2M-Proyecto-PHP-LaboratorioHacking
```

---

### 2️⃣ Levantar Docker (MySQL + phpMyAdmin)

```bash
docker compose -f docker-compose.yml up -d
```

Esto levantará:

- 🗄 MySQL → Puerto `3307`
- 🖥 phpMyAdmin → http://localhost:8081

Credenciales de la base de datos:

- Usuario: `symfony`
- Contraseña: `symfony`
- Base de datos: `symfony`

---

### 3️⃣ Instalar dependencias

```bash
composer install
```

---

### 4️⃣ Añadir migraciones

```bash
php bin/console make:migration
```

Añadimos las migraciones de las entidades.

---

### 5️⃣ Ejecutar migraciones

```bash
php bin/console doctrine:migrations:migrate
```

Esto creará las tablas necesarias en la base de datos.

---

### 6️⃣ Cargar datos de ejemplo con fixtures

```bash
php bin/console doctrine:fixtures:load
```

Esto cargará los desafios.

---

### 7️⃣ Iniciar el servidor Symfony
```bash
symfony server:start
```

Abrir en el navegador:

```
http://127.0.0.1:8000
```

---

### 🔎 Acceso a phpMyAdmin

```
http://localhost:8081
```

Usuario: `symfony`  
Contraseña: `symfony`


## 👤 Credenciales de prueba

No existen usuarios predefinidos.

### Administrador

Si se crea un usuario con el nombre:

```
admin
```

Ese usuario obtiene permisos de administrador (ROLE_ADMIN) y podrá:

- Acceder al panel de administración
- Crear usuarios
- Editar usuarios
- Eliminar usuarios

### Usuario normal

Cualquier otro usuario registrado tendrá el rol:

```
ROLE_USER
```

Y podrá:

- Acceder a los laboratorios
- Ver tutoriales
- Navegar por la aplicación
- No tendrá acceso al panel de administración

---

## 🛠 Tecnologías utilizadas

- Symfony
- PHP
- Doctrine ORM
- MySQL
- Twig
- HTML + CSS
- phpMyAdmin (mediante Docker Compose)

---

## ⚙ Notas importantes

- La aplicación es únicamente educativa.
- No debe utilizarse en producción.
- Las vulnerabilidades están diseñadas para aprendizaje controlado.
- La base de datos se genera mediante migraciones ORM.

---

## 🎯 Objetivo del proyecto

Proporcionar un entorno sencillo y visual donde una persona sin experiencia pueda:

- Entender cómo funcionan vulnerabilidades básicas
- Ver ejemplos prácticos
- Aprender cómo se previenen
- Familiarizarse con conceptos básicos de hacking ético
