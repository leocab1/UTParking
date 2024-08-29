<?php
require 'vendor/autoload.php'; 

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (isset($_POST['placa'])) {
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $tipo_vehiculo = $_POST['vehicle-type'];

    $data = "Placa: $placa\nMarca: $marca\nModelo: $modelo\nTipo de Usuario: $tipo_usuario\nTipo de Vehículo: $tipo_vehiculo";

    $qrCode = QrCode::create($data);
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    header('Content-Type: image/png');
    echo $result->getString();
    exit;
}
?>
