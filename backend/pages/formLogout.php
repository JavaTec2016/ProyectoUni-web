<form class="d-flex" action="../controller/cerrar_sesion.php">
    <p>Bienvenido <?php echo $_SESSION['usuario'] ?></p>
    <button class="btn btn-outline-danger" type="submit">Cerrar sesión</button>
</form>