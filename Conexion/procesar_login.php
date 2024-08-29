<?php
include 'conexion.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $contrasenaencrypt = md5($contrasena);

    if (empty($correo) || empty($contrasena)) {
        $response['status'] = 'error';
        $response['message'] = 'Todos los campos son obligatorios.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
            if ($stmt === false) {
                throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
            }

            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if ($contrasenaencrypt == $row['contrasena']) {
                    session_start();
                    $_SESSION['correo'] = $correo;
                    $response['status'] = 'success';
                    $response['message'] = 'Inicio de sesión exitoso';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Credenciales inválidas. Por favor, verifica tu correo electrónico y contraseña.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Credenciales inválidas. Por favor, verifica tu correo electrónico y contraseña.';
            }

            $stmt->close();
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . htmlspecialchars($e->getMessage());
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: El formulario no se ha enviado correctamente.';
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>

