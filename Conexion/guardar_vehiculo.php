<?php
include 'conexion.php';

$placa = $_POST['placa'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$tipo = $_POST['vehicle-type'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? '';

// Establecer el encabezado de respuesta como JSON
header('Content-Type: application/json');

// Validación básica de campos vacíos
if (empty($placa) || empty($marca) || empty($modelo) || empty($tipo) || empty($tipo_usuario)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Todos los campos son obligatorios.'
    ]);
    exit();
}

try {
    // Verificar la conexión a la base de datos
    if (!$conn) {
        throw new Exception('No se ha establecido la conexión a la base de datos.');
    }

    // Consulta preparada para insertar datos
    $stmt = $conn->prepare("INSERT INTO vehiculos (placa, marca, modelo, tipo, tipo_usuario) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception('Error en la preparación de la consulta: ' . $conn->error);
    }

    // Vincular parámetros a la consulta preparada
    $stmt->bind_param('sssss', $placa, $marca, $modelo, $tipo, $tipo_usuario);

    // Ejecutar la consulta preparada
    $stmt->execute();

    // Verificar si se afectaron filas (es decir, si se insertó correctamente)
    if ($stmt->affected_rows > 0) {
        // Incluir datos del vehículo en la respuesta
        echo json_encode([
            'status' => 'success',
            'message' => '¡Registro Exitoso! El vehículo ha sido registrado correctamente.',
            'data' => [
                'placa' => $placa,
                'marca' => $marca,
                'modelo' => $modelo,
                'tipo' => $tipo,
                'tipo_usuario' => $tipo_usuario
            ]
        ]);
    } else {
        throw new Exception('Error: No se pudo registrar el vehículo.');
    }

    // Cerrar la consulta preparada
    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => htmlspecialchars($e->getMessage())
    ]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
