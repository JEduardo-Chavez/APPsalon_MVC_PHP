<h1 class="nombre-pagina">Crear una cuenta</h1>
<p class="descripcion-pagina">Ingresa tus datos para crear una cuenta.</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" placeholder="Ingresa tu nombre" name="nombre" value="<?php echo s($usuario->nombre);?>">
    </div>
    <div class="campo">
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellido" placeholder="Ingresa tu apellido" name="apellido" value="<?php echo s($usuario->apellido);?>">
    </div>
    <div class="campo">
        <label for="telefono">Telefono o celular</label>
        <input type="tel" id="telefono" placeholder="Ingresa tu telefono" name="telefono" value="<?php echo s($usuario->telefono);?>">
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Ingresa tu correo electronico" name="email" value="<?php echo s($usuario->email);?>">
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Ingresa tu password" name="password">
    </div>
    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesion</a>
    <a href="/olvide">Olvide mi password</a>
</div>