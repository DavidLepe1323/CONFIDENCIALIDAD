<?php 
include("../../bd.php");

// Obtener la lista de roles para el selector
$roles_query = $conexion->prepare("SELECT * FROM roles");
$roles_query->execute();
$lista_roles = $roles_query->fetchAll(PDO::FETCH_ASSOC);

// Procesar la actualización del rol si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role_id'];

    $update_query = $conexion->prepare("UPDATE tbl_usuarios SET role_id = :role_id WHERE id = :user_id");
    $update_query->bindParam(":role_id", $new_role);
    $update_query->bindParam(":user_id", $user_id);
    $update_query->execute();

    $mensaje = "Rol actualizado";
    header("Location:index.php?mensaje=" . urlencode($mensaje));
    exit;
}

// Procesar la eliminación de un usuario
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];
    $sentencia = $conexion->prepare("DELETE FROM tbl_usuarios WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . urlencode($mensaje));
    exit;
}

// Obtener la lista de usuarios con su rol actual
$sentencia = $conexion->prepare("SELECT u.id, u.usuario, u.correo, r.id AS role_id, r.nombre AS rol 
    FROM tbl_usuarios u 
    LEFT JOIN roles r ON u.role_id = r.id");
$sentencia->execute();
$lista_tbl_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>
<br />

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Agregar usuarios</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Cambiar Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_tbl_usuarios as $registro) { ?>
                    <tr>
                        <td scope="row"><?php echo htmlspecialchars($registro['id']); ?></td>
                        <td><?php echo htmlspecialchars($registro['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($registro['correo']); ?></td>
                        <td><?php echo htmlspecialchars($registro['rol']); ?></td>
                        <td>
                            <a class="btn btn-info" href="editar.php?txtID=<?php echo urlencode($registro['id']); ?>"
                                role="button">Editar</a>
                            |
                            <a class="btn btn-danger" href="javascript:void(0);"
                                onclick="confirmarEliminacion(<?php echo urlencode($registro['id']); ?>);"
                                role="button">Eliminar</a>
                        </td>
                        <td>
                            <form method="POST" action="index.php">
                                <input type="hidden" name="user_id"
                                    value="<?php echo htmlspecialchars($registro['id']); ?>">
                                <select name="role_id" class="form-select">
                                    <?php foreach($lista_roles as $rol) { ?>
                                    <option value="<?php echo $rol['id']; ?>"
                                        <?php echo ($rol['id'] == $registro['role_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($rol['nombre']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <button type="submit" name="update_role" class="btn btn-secondary mt-1">Actualizar
                                    Rol</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de eliminar este usuario?')) {
        window.location.href = 'index.php?txtID=' + id;
    }
}
</script>

<?php include("../../templates/footer.php"); ?>