# 🧾 La Despensa de Don Juan - Sistema de Tienda en Línea

Usuario: admin
Contraseña:1234

Mayerlin Yisel Aguilar Cruz - SMSS067424
Marleny Jamileth Martinez Mendez - SMSS018924

## ¿Cómo manejan la conexión a la BD y qué pasa si algunos de los datos son incorrectos? Justifiquen la manera de validación de la conexión.

Usamos **PDO** con manejo de excepciones (`ERRMODE_EXCEPTION`). Si los datos de conexión son incorrectos (host, usuario, contraseña o BD), el `catch` captura el error y muestra un mensaje amigable, evitando que se muestren detalles internos del servidor. Esto es seguro porque **no exponemos credenciales ni estructura de la BD al usuario**.

En cuanto a datos incorrectos (ej. precio negativo, stock vacío), se validan en PHP con `filter_var` y se muestran errores específicos antes de ejecutar el INSERT. Así aseguramos que solo datos válidos lleguen a la BD.

## ¿Cuál es la diferencia entre $\_GET y $\_POST? ¿Cuándo es más apropiado usar cada uno? Da un ejemplo real de tu proyecto.

- **$\_GET**: envía datos por URL (visible). Se usa para filtrar, buscar o paginar. En nuestro proyecto podría usarse para `index.php?categoria=Granos`.
- **$\_POST**: envía datos en el cuerpo de la petición (ocultos). Se usa para formularios sensibles como login, agregar productos o modificar datos. En **nuestro proyecto**, usamos `$_POST` en el login y en el dashboard para agregar productos porque manejan contraseñas y datos privados.

## Tu app va a usarse en una empresa de la zona oriental. ¿Qué riesgos de seguridad identificas y cómo mitigarlos?

### Riesgos:

1. **Inyección SQL** (si concatenamos queries).
2. **XSS** (Cross-site scripting).
3. **Contraseñas en texto plano** (actualmente 'admin' con '1234').
4. **Sesiones secuestradas**.
5. **Acceso no autorizado a datos**.

### Mitigaciones aplicadas:

- **SQL Injection**: usamos `prepare()` con parámetros (PDO).
- **XSS**: usamos `htmlspecialchars()` en todas las salidas.
- **Contraseñas**: en producción usar `password_hash()` y `password_verify()`.
- **Sesiones**: controlamos `session_start()` y verificamos existencia de `$_SESSION['usuario']` en cada página privada.
- **Validación estricta**: con `filter_var` para números y tipos esperados.

---

## Diccionario de datos

### Tabla: `usuarios`

| Columna  | Tipo         | Límite | ¿Nulo? | Descripción               |
| -------- | ------------ | ------ | ------ | ------------------------- |
| id       | INT          | 11     | NO     | Identificador único       |
| usuario  | VARCHAR(50)  | 50     | NO     | Nombre de usuario         |
| password | VARCHAR(100) | 100    | NO     | Contraseña (hash en prod) |

### Tabla: `categorias`

| Columna | Tipo        | Límite | ¿Nulo? | Descripción         |
| ------- | ----------- | ------ | ------ | ------------------- |
| id      | INT         | 11     | NO     | Identificador único |
| nombre  | VARCHAR(50) | 50     | NO     | Nombre de categoría |

### Tabla: `productos`

| Columna      | Tipo          | Límite | ¿Nulo? | Descripción               |
| ------------ | ------------- | ------ | ------ | ------------------------- |
| id           | INT           | 11     | NO     | Clave primaria            |
| nombre       | VARCHAR(100)  | 100    | NO     | Nombre del producto       |
| precio       | DECIMAL(10,2) | -      | NO     | Precio en dólares         |
| id_categoria | INT           | 11     | SÍ     | Relación con categorías   |
| stock        | INT           | 11     | NO     | Cantidad disponible       |
| descripcion  | TEXT          | 65535  | **SÍ** | Descripción (acepta NULL) |
| disponible   | BOOLEAN       | -      | NO     | Producto a la venta o no  |
