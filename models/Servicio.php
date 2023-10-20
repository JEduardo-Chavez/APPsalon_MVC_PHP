<?php

namespace Model;

class Servicio extends ActiveRecord{

    //base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar()
    {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de servicio es obligatorio';
        }

        if(!$this->precio){
            self::$alertas['error'][] = 'El precio es obligatorio';
        }
        //validar si es un numero
        if( !is_numeric($this->precio) ){
            self::$alertas['error'][] = 'El precio tiene que ser un numero';
        }

        return self::$alertas;
    }

}