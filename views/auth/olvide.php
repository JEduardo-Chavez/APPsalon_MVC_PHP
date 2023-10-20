<h1 class="nombre-pagina">Olivde mi password</h1>
<p class="descripcion-pagina">Ingresa tu correo para reestablecer tu password.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Ingresa tu correo electronico" name="email">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crea una</a>
    <a href="/">Iniciar Sesion</a>
</div>