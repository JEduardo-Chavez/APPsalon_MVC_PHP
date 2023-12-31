<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {

    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){
        //ALMACENA LAS CITS Y DEVUELVE EL ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id=$resultado['id'];

        //ALMACENA LA CITA Y EL SERVICIO
        $idServicios = explode(',', $_POST['servicios']); //sEPARAR UNA SERIE DE STRINGS EN UN ARREGLO
        foreach($idServicios as $idServicio){ //ITERAR EL ARREGLO CREADO
            $args = [
                'citaId'=> $id,
                'servicioId'=>$idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);

    }

    public static function eliminar(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST')   {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }

}