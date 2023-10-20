let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre:'',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp(){
    //mostrar y ocultar las secciones
    mostrarSeccion()
    //Cambiar las secciones que se van a mostrar dependiendo que tab se dio clic
    tabs();
    //agregar y quitar los botones del paginador
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
    //CONSULTAR LA API EN EL BACKEND PHP
    consultaAPI();

    //agregar el nombre para agendar la cita
    nombreCliente();
    idCliente();

    //seleccionar fecha de la cita al objeto
    seleccionarFecha();
    //Seleccionar la hora de la cita al objeto;
    seleccionarHora();
    mostrarResumen();

};

function mostrarSeccion(){
    //ocultar la seccion mostrada
    const seccionOcultar = document.querySelector('.mostrar');
    if (seccionOcultar) {
        seccionOcultar.classList.remove('mostrar');
    }
    //seleccionar la seccion con respectivo paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //quietar el hover
    const tabOcultar = document.querySelector('.actual');
    if(tabOcultar){
        tabOcultar.classList.remove('actual');
    }

    //resaltar el hover seleccionado
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}


function tabs(){
    const botones = document.querySelectorAll('.tabs button' );
    //cuando se tiene un querySelectorAll no se puede usar addEventListener, asi que se usa un ForEach para iterar el nodelist
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            //seleccionar el boton al que se le esta dando clic mediante el atripbuto personalizado
            paso = parseInt( e.target.dataset.paso );
            //mostrar la seccion
            mostrarSeccion();
            botonesPaginador();
        });
    });
    
};

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');

    }
    mostrarSeccion();
}

function paginaAnterior(){

    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso<=pasoInicial) return;
        paso --;
        botonesPaginador();
    });
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso>=pasoFinal) return;
        paso ++;
        botonesPaginador();
    });
}

async function consultaAPI(){

    try {
        //obtener la url de la apo
        const url = '/api/servicios';
        //consumir los datos de la api
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        //mostrarlos en la interfaz
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

//FUNCION PARA MOSTRAR LOS SERVICIOS EN PATNALLA
function mostrarServicios(servicios){

    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;
        
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const divServicio = document.createElement('DIV');
        divServicio.classList.add('servicio');
        divServicio.dataset.idServicio = id;
        divServicio.onclick = function(){
            seleccionarServicio(servicio);
        };

        divServicio.appendChild(nombreServicio);
        divServicio.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(divServicio);
        
    });

}

//funcion para seleccionar el servicio al dar click
function seleccionarServicio(servicio){
    const { id } = servicio;
    const { servicios } = cita;
    //identificar cual elemento se le dio click
    const serviciosDiv = document.querySelector(`[data-id-servicio="${id}"]`);

    //COMPROBAR SI UN OBJETO YA ESTA AGREGADO
    if( servicios.some( agregado => agregado.id === id) ){ //itera en un arreglo y retorna un true o false en caso de que ya exista en el arreglo 
        //Eliminarlo porque YA ESTA AGREGADO
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        serviciosDiv.classList.remove('seleccionado');
    } else{
        //AGREGARLO porque no esta en el arreglo
        cita.servicios = [...servicios, servicio];
        serviciosDiv.classList.add('seleccionado');
    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;
}

function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;
}


function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){

        const dia = new Date(e.target.value).getUTCDay();

        if( [6,0].includes(dia) ){ //metodo que identifica si un dato esta en el arreglo
            e.target.value = '';
            mostrarAlerta('Fines de semana no abrimos, selecciona un dia de lunes a viernes', 'error', '.formulario');
        }else{
            cita.fecha = e.target.value;
        }
    });
}
function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){

        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Horario fuera de servicio. Nuestro horario es de 10am a 6pm', 'error', '.formulario');
        }else{
            cita.hora = e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento){
    const alertPrevia = document.querySelector('.alerta');
    if (alertPrevia) return;

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    setTimeout(() => {
        alerta.remove();
    }, 3000);
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }
    
    // Object.values(cita) -- Muestra los valores de algun objeto
    if( Object.values(cita).includes('') || cita.servicios.length === 0 ){
        mostrarAlerta('Recuerda seleccionar al menos 1 servicio, la fecha y hora de tu cita', 'error', '.contenido-resumen');
        return;
    }

    //Formatear el Div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    //HEADER DE SECCION RESUMEN
    const headingResumen = document.createElement('H2');
    headingResumen.textContent = 'Confirmar tu cita';
    const parrafoResumen = document.createElement('P');
    parrafoResumen.classList.add('text-center')
    parrafoResumen.textContent = 'Revisa que los datos de la cita esten correctos';
    resumen.appendChild(headingResumen);
    resumen.appendChild(parrafoResumen);
    //HEADING SRVICIO
    const headServicio = document.createElement('H3');
    headServicio.textContent = 'Servicios';
    resumen.appendChild(headServicio);

    //ITERANDO Y MOSTRANDO LOS SERVICIOS
    servicios.forEach(servicio =>{
        const {id, nombre, precio} = servicio;
        const contenidoServicio = document.createElement('DIV');
        contenidoServicio.classList.add('contenido-servicios');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenidoServicio.appendChild(textoServicio);
        contenidoServicio.appendChild(precioServicio);

        resumen.appendChild(contenidoServicio);
    })

    //HEADING informacion de cita
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Informacion de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //DARLE FORMATO LA FECHA
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));

    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span>${hora} hrs`;

    //BOTON PARA CREAR LA CITA
    const botonReserva = document.createElement('BUTTON');
    botonReserva.classList.add('boton');
    botonReserva.textContent = 'Reservar cita';
    botonReserva.onclick = reservarCita;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReserva);
}

async function reservarCita(){
    const {id, fecha, hora, servicios} = cita;
    const idServicio = servicios.map( servicio => servicio.id);

    const datos = new FormData();
    //AGREGAR DATOS A EL fromData
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicio);

    try {
        //PETICION HACIA LA API
        const url = '/api/citas';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        console.log(resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita agendada!',
                text: 'Tu cita ha sido agendada.',
                button: 'OK!',
                width: 300
            }).then( () =>{
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Ocurrio un error al agendar la cita.',
            button: 'OK!'
        })   
    }

    //CONSULTAR QUE SE ESTA ENVIANDO EN EL fromData
    // console.log([...datos]);
    
}