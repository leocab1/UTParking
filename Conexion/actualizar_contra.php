<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las contraseñas coinciden
    if ($_POST['password'] == $_POST['confirm_password']) {
        // Validar el token de seguridad
        $token = $_POST['token'];
        
        // Conectar a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        
        // Preparar la consulta para verificar el token y su validez
        $sql = "SELECT correo FROM usuarios WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // El token es válido, proceder con la actualización de la contraseña
            $row = $result->fetch_assoc();
            $email = $row['correo'];
            $new_password = md5($_POST['password']); // Hashear la contraseña antes de guardarla
            
            // Preparar y ejecutar la consulta para actualizar la contraseña
            $sql_update = "UPDATE usuarios SET contrasena = ?, token = NULL WHERE correo = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $new_password, $email);
            
            if ($stmt_update->execute()) {
                // Contraseña cambiada exitosamente
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo '    Swal.fire({';
                echo '        icon: "success",';
                echo '        title: "Contraseña cambiada",';
                echo '        text: "La contraseña se ha cambiado exitosamente."';
                echo '    }).then(function() {';
                echo '        window.location.href = "/UTParking/home.html";';
                echo '    });';
                echo '});';
                echo '</script>';
                exit();
            } else {
                // Error al actualizar la contraseña en la base de datos
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo '    Swal.fire({';
                echo '        icon: "error",';
                echo '        title: "Error",';
                echo '        text: "Error al actualizar la contraseña: ' . $stmt_update->error . '"';
                echo '    });';
                echo '});';
                echo '</script>';
            }
        } else {
            // Token inválido o expirado
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo '    Swal.fire({';
            echo '        icon: "error",';
            echo '        title: "Error",';
            echo '        text: "El enlace de restablecimiento de contraseña no es válido o ha expirado."';
            echo '    });';
            echo '});';
            echo '</script>';
        }
        
        // Cerrar la conexión a la base de datos
        $conn->close();
    } else {
        // Contraseñas no coinciden
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        icon: "warning",';
        echo '        title: "Contraseñas no coinciden",';
        echo '        text: "Por favor, asegúrate de ingresar la misma contraseña en ambos campos."';
        echo '    });';
        echo '});';
        echo '</script>';
    }
}
?>