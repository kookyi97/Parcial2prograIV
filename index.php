<?php
require_once 'conexion.php';
$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id
        ORDER BY p.nombre ASC";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>La Despensa de Don Juan - Productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>🛒 La Despensa de Don Juan</h1>
        <p>Bienvenido visitante. <a href="login.php">Iniciar sesión</a> para administrar.</p>

        <h2>Lista de productos disponibles</h2>
        <table>
            <tr>
                <th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Descripción</th>
            </tr>
            <?php foreach($productos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
                <td>$<?= number_format($p['precio'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><?= $p['descripcion'] ?? '<em>Sin descripción</em>' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>