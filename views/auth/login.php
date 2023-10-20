<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Ingresa tu correo electronico" name="email">
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Ingresa tu password" name="password">
    </div>
    <input type="submit" class="boton" value="Iniciar Sesion">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crea una</a>
    <a href="/olvide">Olvide mi password</a>
</div>