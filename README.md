🔐 Autenticación con JWT
Los usuarios pueden registrarse e iniciar sesión para obtener un token JWT. Este token se usa para acceder a las rutas protegidas del API.

📦 CRUD de Productos
Los usuarios autenticados pueden crear, leer, actualizar y eliminar sus propios productos.

🔗 Relación: Usuario - Productos
Un usuario puede tener muchos productos (hasMany), y cada producto pertenece a un único usuario (belongsTo).

🗃️ Base de datos: SQLite
La aplicación usa SQLite como base de datos. Configuración en .env:
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/backend/database/database.sqlite


