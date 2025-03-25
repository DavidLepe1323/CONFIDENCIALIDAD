<?php 
include("../../bd.php");

if($_POST){
   // Recolectamos los datos del método POST
   $usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
   $password = isset($_POST["password"]) ? $_POST["password"] : "";
   $correo = isset($_POST["correo"]) ? $_POST["correo"] : "";
   $role_id = isset($_POST["role_id"]) ? $_POST["role_id"] : null;

   // Preparar la inserción de los datos, incluyendo el role_id
   $sentencia = $conexion->prepare("INSERT INTO tbl_usuarios (id, usuario, password, correo, role_id)
       VALUES (NULL, :usuario, :password, :correo, :role_id)");
   $sentencia->bindParam(":usuario", $usuario);
   $sentencia->bindParam(":password", $password);
   $sentencia->bindParam(":correo", $correo);
   $sentencia->bindParam(":role_id", $role_id);
   $sentencia->execute();
   
   $mensaje = "Registro agregado";
   header("Location:index.php?mensaje=" . $mensaje);
   exit;
}
?>
<?php include("../../templates/header.php"); ?>

<br />

<div class="card">
    <div class="card-header">
        Datos del usuario
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre del usuario:</label>
                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nombre del usuario">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" id="password"
                    placeholder="Escriba su contraseña">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" name="correo" id="correo" placeholder="Escriba su correo">
            </div>

            <!-- Opción 2: Menú desplegable estático -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Rol:</label>
                <select name="role_id" id="role_id" class="form-select">
                    <option value="1">Administrador</option>
                    <option value="2">Editor</option>
                    <option value="3">Usuario Regular</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>