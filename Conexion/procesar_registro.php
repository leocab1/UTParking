<?php
include 'conexion.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo']; 
    $contrasena = $_POST['contrasena'];
    $contrasenaencrypt = md5($contrasena);


    if (empty($correo)) {
        $response['status'] = 'error';
        $response['message'] = 'El campo de correo está vacío.';
    } elseif (empty($contrasena)) {
        $response['status'] = 'error';
        $response['message'] = 'El campo de contraseña está vacío.';
    } else {
        $sql = "INSERT INTO usuarios (correo, contrasena) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $correo, $contrasenaencrypt);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Nuevo registro creado correctamente';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: El formulario no se ha enviado correctamente.';
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>

