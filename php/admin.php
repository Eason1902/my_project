<?php
// Mostrar errores

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$usuario_admin = $_SESSION['usuario'] ?? 'Administrador';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "biblioteca");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de usuarios
$sqlUsuarios = "SELECT usuario, password, tipo FROM usuarios";
$resultadoUsuarios = $conexion->query($sqlUsuarios);

// Consultas estadísticas
$total     = $conexion->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$admins    = $conexion->query("SELECT COUNT(*) AS admins FROM usuarios WHERE tipo='admin'")->fetch_assoc()['admins'];
$ultima    = $conexion->query("SELECT MAX(fecha_registro) AS ultima FROM usuarios")->fetch_assoc()['ultima'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Datos</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #ffffff; }
        h2 { color: #212121; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #FF820C; color: white; }
        .seccion { margin-bottom: 50px; }
    </style>
    <style>
        td {
            position: relative;
        }
        .toggle-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1em;
            color: #212121;
        }
        .pw {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            display: inline-block;
            padding-right: 30px; /* 留出空间给图标 */
        }
    </style>

</head>

<body>
<h1 style="color:#212121;">👋 Bienvenido, <?= htmlspecialchars($usuario_admin) ?></h1>

<!-- Gestión de Usuarios -->
<div class="seccion">
    <h2>🔐 Gestión de Usuarios</h2>
    <table>
        <thead>
        <tr>
            <th>Usuario</th>
            <th>Contraseña</th>
            <th>Tipo</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($resultadoUsuarios->num_rows > 0) {
            while ($fila = $resultadoUsuarios->fetch_assoc()) {
                echo "<tr>
                        <td>{$fila['usuario']}</td>
                       <td>
                    <span class='pw' data-pw='{$fila['password']}'>••••••</span>
                    <button class='toggle-btn'>👁️</button>
                        </td>
                        <td>{$fila['tipo']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay usuarios registrados</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Estado del sistema -->
<div class="seccion" id="estado">
    <h2>📊 Estado del Sistema</h2>
    <table>
        <tr><th>Métrica</th><th>Valor</th></tr>
        <tr><td>Total de usuarios</td><td><?= $total ?></td></tr>
        <tr><td>Usuarios tipo admin</td><td><?= $admins ?></td></tr>
        <tr><td>Último registro</td><td><?= $ultima ?></td></tr>
    </table>
</div>



<script>
    document.querySelectorAll(".toggle-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            const span = this.previousElementSibling;
            const realPw = span.dataset.pw;
            if (span.textContent === "••••••") {
                span.textContent = realPw;
            } else {
                span.textContent = "••••••";
            }
        });
    });
</script>

</body>
</html>
