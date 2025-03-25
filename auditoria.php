<?php
// Iniciar sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: " . $url_base . "login.php");
    exit;
}

try {
    // Conectar a la base de datos
    $conexion = new PDO("mysql:host=localhost;dbname=app", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los registros de auditoría
    $stmt = $conexion->prepare("SELECT * FROM auditoria ORDER BY fecha DESC");
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

<?php include("templates/header.php"); ?>

<br />

<div class="container mt-4">
    <h1>Registros de Auditoría</h1>
    <table id="tabla_auditoria" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Descripción</th>
                <th>IP del Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verificar si hay registros y mostrarlos en la tabla
            if (!empty($registros)) {
                foreach ($registros as $registro) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($registro['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($registro['usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($registro['accion']) . "</td>";
                    echo "<td>" . htmlspecialchars($registro['descripcion']) . "</td>";
                    echo "<td>" . htmlspecialchars($registro['ip_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($registro['fecha']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay registros de auditoría.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#tabla_auditoria').DataTable(); // Inicializar la tabla con DataTables
});
</script>

<?php include("templates/footer.php"); ?>