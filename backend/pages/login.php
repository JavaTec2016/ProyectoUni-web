<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once('addBootstrap.php') ?>
    <?php include_once('styles.php') ?>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
</head>

<body class="body-center">

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="validar" method="post" id="usuario_div">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="usuario_input" name="usuario_input" placeholder="Nombre de usuario" required>
            </div>
            <div class="form-group" id="pass_div">
                <label for="password">Contraseña</label>
                <input type="password" id="pass_input" name="pass_input" placeholder="Tu contraseña" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6LdZeSIsAAAAADtA2uVZpczpQ8YviYeU4gtwULHg" data-action="LOGIN"></div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
</body>

</html>