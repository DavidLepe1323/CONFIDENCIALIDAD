<?php
session_start();
$url_base = "http://localhost/app/";

// Si el usuario no está logueado, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location:" . $url_base . "login.php");
    exit;
}

// Asumimos que se guardó el rol del usuario en la sesión (ejemplo: "Administrador", "Editor", "Usuario Regular")
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : "";
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js">
    </script>
    <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <!-- Navbar aquí -->
    </header>
    <nav class="navbar navbar-expand navbar-light bg-light">
        <ul class="nav navbar-nav">
            <!-- Siempre se muestra el enlace a "Sistema Web" -->
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo $url_base; ?>">Sistema Web<span
                        class="visually-hidden">(current)</span></a>
            </li>
            <!-- Mostrar empleados y puestos para Administrador y Editor -->
            <?php if ($rol === "Administrador" || $rol === "Editor"): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base; ?>secciones/empleados/">Empleados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base; ?>secciones/puestos/">Puestos</a>
            </li>
            <?php endif; ?>
            <!-- Mostrar Usuarios y Auditoría solo para Administrador -->
            <?php if ($rol === "Administrador"): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base; ?>secciones/usuarios/">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base; ?>auditoria.php">Auditoría</a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar sesión</a>
            </li>
        </ul>
    </nav>
    <main class="container">
        <?php if(isset($_GET['mensaje'])) { ?>
        <script>
        Swal.fire({
            icon: "success",
            title: "<?php echo $_GET['mensaje']; ?>"
        });
        </script>
        <?php } ?>