<?php 
    @include_once __DIR__ . '/../templates/barra.php';
?>

<h1 class="nombre-pagina">Crear una cita</h1>
<p class="descripcion-pagina">Selecciona los datos para una cita</p>


<div class="app">

    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Informacion Cita</button>
        <button type="button" data-paso="3">Confirmar cita</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Selecciona los serivicos que buscas</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Agenda tu cita</h2>
        <p class="text-center">Coloca tus datos y fecha para tu cita</p>
        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Nombre del cliente" value="<?php echo $nombre; ?>" disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora">
            </div>
            <input type="hidden" id="id" value=" <?php echo $id; ?> ">
        </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">

    </div>

    <!-- PAGINADOR -->
    <div class="paginacion">
        <button id="anterior" class="boton"> &laquo; Anterior </button>
        <button id="siguiente" class="boton">  Siguiente &raquo; </button>
    </div>

</div>

<?php
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    "
?>
