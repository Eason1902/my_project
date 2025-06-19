<?php
session_start(); // 启动 session

$conexion = new mysqli("localhost", "root", "", "biblioteca");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 获取用户提交的数据
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$tipo = $_POST['tipo']; // usuario 或 admin

// 查询数据库中该用户
$sql = "SELECT password, tipo FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($pass_guardada, $tipo_guardado);
    $stmt->fetch();

    // 检查用户类型是否匹配
    if ($tipo !== $tipo_guardado) {
        echo "<script>alert('⚠️ Este usuario no pertenece al tipo seleccionado.'); window.history.back();</script>";
        exit();
    }

    // 检查密码是否正确（明文对比）
    if ($password === $pass_guardada) {
        $_SESSION['usuario'] = $usuario; // 保存用户名到 session

        // 根据类型跳转到不同页面
        if ($tipo === "admin") {
            echo "<script>alert('✅ Bienvenido administrador'); window.location.href='../html/admin.html';</script>";
        } else {
            echo "<script>alert('✅ Bienvenido usuario'); window.location.href='../html/cliente.html';</script>";
        }
    } else {
        echo "<script>alert('❌ Contraseña incorrecta.'); window.history.back();</script>";
    }

} else {
    echo "<script>alert('❌ Usuario no encontrado.'); window.history.back();</script>";
}

$stmt->close();
$conexion->close();
?>
