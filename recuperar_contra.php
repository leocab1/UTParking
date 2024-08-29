<?php
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost"; -
$username = "root";
$password = "";
$database_db = "";

$conn = new mysqli($servername, $username, $password, $database_db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$message = '';
$message_type = '';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo electrónico del formulario
    $email = $_POST['email'];

    $sql = "SELECT correo FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generar un token único
        $sql_update_token = "UPDATE usuarios SET token = ? WHERE correo = ?";
        $stmt = $conn->prepare($sql_update_token);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; // Tu dirección de correo electrónico de Gmail
            $mail->Password = ''; // Tu contraseña de aplicación generada
            $mail->SMTPSecure = 'tls'; // Usar TLS para una conexión segura
            $mail->Port = 587; // Puerto SMTP de Gmail para TLS

            // Desactivar la verificación del certificado SSL/TLS
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Configurar el remitente
            $mail->setFrom('', 'Soporte UTP');

            // Configurar el destinatario
            $mail->addAddress($email);

            // Configurar el asunto y el cuerpo del correo electrónico
            $mail->Subject = "Restablecer Contraseña";
            $mail->Body = "Hola,\n\nPara restablecer tu contraseña, haz clic en el siguiente enlace:\n\nhttp://localhost:8089/UTParking/nuevacontra.php?token=$token";

            // Enviar el correo electrónico
            $mail->send();
            
            // Mostrar SweetAlert de éxito y redirigir
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo '    Swal.fire({';
            echo '        icon: "success",';
            echo '        title: "Correo enviado",';
            echo '        text: "Se ha enviado un enlace de restablecimiento de contraseña a tu correo electrónico."';
            echo '    }).then((result) => {';
            echo '        if (result.isConfirmed) {';
            echo '            window.location.href = "/UTParking/home.html";'; // Reemplaza con la ruta deseada
            echo '        }';
            echo '    });';
            echo '});';
            echo '</script>';
            
        } catch (Exception $e) {
            // Mostrar SweetAlert de error
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo '    Swal.fire({';
            echo '        icon: "error",';
            echo '        title: "Error",';
            echo '        text: "Error al enviar el correo electrónico: ' . $mail->ErrorInfo . '"';
            echo '    });';
            echo '});';
            echo '</script>';
        }
    } else {
        // Mostrar SweetAlert de advertencia
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        icon: "warning",';
        echo '        title: "Correo no encontrado",';
        echo '        text: "El correo electrónico no existe en la base de datos."';
        echo '    });';
        echo '});';
        echo '</script>';
    }
}
// Cerrar la conexión a la base de datos
$conn->close();
?>
