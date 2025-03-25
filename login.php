<?php
session_start();
include("./bd.php");

if ($_POST) {
    $usuario = $_POST["usuario"];
    $contrasenia = $_POST["contrasenia"];

    // Consulta para verificar las credenciales y obtener información del usuario
    $sentencia = $conexion->prepare("SELECT u.*, r.nombre AS rol FROM tbl_usuarios u LEFT JOIN roles r ON u.role_id = r.id WHERE u.usuario = :usuario AND u.password = :password");
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":password", $contrasenia);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // Credenciales correctas: iniciar sesión
        $_SESSION['id_usuario'] = $registro["id"];
        $_SESSION['usuario'] = $registro["usuario"];
        $_SESSION['rol'] = $registro["rol"];
        $_SESSION['logueado'] = true;

        // Registrar el inicio de sesión en la tabla de auditoría
        $accion = "Inicio de sesión";
        $descripcion = "El usuario $usuario inició sesión en el sistema.";
        $ip_usuario = $_SERVER['REMOTE_ADDR'];

        $sentencia_auditoria = $conexion->prepare("INSERT INTO auditoria (id_usuario, usuario, accion, descripcion, ip_usuario) VALUES (:id_usuario, :usuario, :accion, :descripcion, :ip_usuario)");
        $sentencia_auditoria->bindParam(":id_usuario", $_SESSION['id_usuario']);
        $sentencia_auditoria->bindParam(":usuario", $usuario);
        $sentencia_auditoria->bindParam(":accion", $accion);
        $sentencia_auditoria->bindParam(":descripcion", $descripcion);
        $sentencia_auditoria->bindParam(":ip_usuario", $ip_usuario);
        $sentencia_auditoria->execute();

        header("Location: index.php");
        exit;
    } else {
        // Credenciales incorrectas: mostrar mensaje de error
        $mensaje = "Error: El usuario o contraseña son incorrectos";
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <br /><br />
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php if(isset($mensaje)){ ?>
                        <div class="alert alert-danger" role="alert">
                            <strong><?php echo $mensaje; ?></strong>
                        </div>
                        <?php } ?>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" name="usuario" id="usuario"
                                    placeholder="Escriba su usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="contrasenia" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" name="contrasenia" id="contrasenia"
                                    placeholder="Escriba su contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Entrar al sistema</button>
                            <div class="mt-3">
                                <a href="recuperar_contrasenia.php">¿Olvidaste tu contraseña?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
</body>

</html>