<?php
// Incluir el autoload de Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el correo del usuario desde el formulario
    $correo = $_POST['correo'];

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();  // Usar SMTP
        $mail->Host       = 'smtp.gmail.com';  // Servidor SMTP de Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'davidlehdz@gmail.com';  // Tu correo de Gmail
        $mail->Password   = 'kbjp rtof edvw wtij';  // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Usar STARTTLS
        $mail->Port       = 587;  // Puerto para STARTTLS

        // Remitente y destinatario
        $mail->setFrom('davidlehdz@gmail.com', 'Tu Nombre');
        $mail->addAddress($correo, 'Usuario');  // Usar el correo del usuario

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';
        $mail->Body    = 'Este es el cuerpo del correo con las instrucciones para recuperar tu contraseña.';
        $mail->AltBody = 'Este es el cuerpo del correo en texto plano para clientes que no soportan HTML.';

        // Enviar el correo
        if ($mail->send()) {
            $mensaje = "El correo de recuperación ha sido enviado a $correo.";
        }
    } catch (Exception $e) {
        $mensaje = "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Recuperación de Contraseña
                    </div>
                    <div class="card-body">
                        <?php if (isset($mensaje)) { ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                        <?php } ?>

                        <form action="recuperar_contrasenia.php" method="POST">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" name="correo" id="correo" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Recuperar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
</body>

</html>