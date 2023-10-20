<h1 class="nombre-pagina">Reestablecer password</h1>
<p class="descripcion-pagina">Ingresa tu nueva password en el siguiente campo:</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return; ?>
<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Nueva Password</label>
        <input type="password" id="password" placeholder="Ingresa tu nueva password" name="password">
    </div>
    <input type="submit" class="boton" value="Guardar Nueva Password">
</form>

<div class="acciones">
    <a href="/"> ¿Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/crear-cuenta"> ¿Aun no tienes cuenta? Crear cuenta</a>
</div>