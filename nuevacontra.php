<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/contraseña.css">
    <link rel="icon" href="img/utpicon.png">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <form class="form_main" id="formMain" method="post" action="conexion/actualizar_contra.php">
                <h2 class="heading">Ingresa tu nueva contraseña</h2>
                <div class="inputContainer">
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                    <input type="password" name="password" placeholder="Nueva contraseña" class="inputField" required>
                </div>
                <div class="inputContainer">
                    <input type="password" name="confirm_password" placeholder="Confirma tu nueva contraseña" class="inputField" required>
                </div>
                <button type="submit" id="button">Actualizar contraseña</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="js/formulario.js"></script>
</body>
</html>
