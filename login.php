<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) { 
        $_SESSION['usuario'] = $user['usuario'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="estilos.css"><title>Login</title></head>
<body>
<div class="container">
    <h2>🔐 Iniciar Sesión</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <button type="submit">Ingresar</button>
    </form>
</div>
</body>
</html>