<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// 检查密码长度
if (strlen($password) < 6) {
    echo "<script>alert('❌ La contraseña debe tener al menos 6 caracteres.'); window.history.back();</script>";
    exit();
}

// 不加密密码，直接存入数据库（仅演示）
$sql = "INSERT INTO usuarios (usuario, password) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $usuario, $password);

if ($stmt->execute()) {
    echo "<script>alert('✅ Registro exitoso.'); window.location.href='login.html';</script>";
} else {
    echo "<script>alert('❌ El usuario ya existe.'); window.history.back();</script>";
}

$stmt->close();
$conexion->close();
?>
