<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
    $id_categoria = $_POST['categoria'];
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $descripcion = empty($_POST['descripcion']) ? null : trim($_POST['descripcion']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $errores = [];
    if (empty($nombre)) $errores[] = "Nombre requerido";
    if ($precio === false || $precio <= 0) $errores[] = "Precio inválido";
    if ($stock === false || $stock < 0) $errores[] = "Stock inválido";

    if (empty($errores)) {
        $sql = "INSERT INTO productos (nombre, precio, id_categoria, stock, descripcion, disponible) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $precio, $id_categoria, $stock, $descripcion, $disponible]);
        $mensaje = "✅ Producto agregado";
    }
}

$productos = $pdo->query("SELECT p.*, c.nombre as cat_nombre FROM productos p JOIN categorias c ON p.id_categoria = c.id ORDER BY p.nombre")->fetchAll();
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="estilos.css"><title>Admin - Despensa</title></head>
<body>
<div class="container">
    <h1>🧑‍🌾 Panel de Administración</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> | <a href="logout.php">Cerrar sesión</a></p>

    <h2>➕ Agregar nuevo producto</h2>
    <?php if(isset($mensaje)) echo "<p class='success'>$mensaje</p>"; ?>
    <?php if(!empty($errores)) foreach($errores as $e) echo "<p class='error'>$e</p>"; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre del producto" required><br>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required><br>

        <select name="categoria" required>
            <option value="">Seleccione categoría</option>
            <?php foreach($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="number" name="stock" placeholder="Stock" required><br>
        <textarea name="descripcion" placeholder="Descripción (opcional)"></textarea><br>

        <label>
            <input type="checkbox" name="disponible" checked> Disponible para venta
        </label><br>

        <button type="submit" name="agregar">Guardar producto</button>
    </form>

    <h2>📦 Productos registrados</h2>
    <table border="1">
        <tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Disponible</th><th>Descripción</th></tr>
        <?php foreach($productos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['cat_nombre']) ?></td>
            <td>$<?= number_format($p['precio'],2) ?></td>
            <td><?= $p['stock'] ?></td>
            <td><?= $p['disponible'] ? '✅ Sí' : '❌ No' ?></td>
            <td><?= $p['descripcion'] ?? '<i>NULL</i>' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>