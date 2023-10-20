<?php

namespace Model;

class Usuario extends ActiveRecord{

    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token',];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null; 
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //MENSAJES DE AVISO DE VALIDACION DE CREAR CUENTA
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'Los apellidos son obligatorios';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'El numero de telefono es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 7){
            self::$alertas['error'][] = "El password debe contener al menos 7 caracteres";
        }
        return self::$alertas;
    }

    //MENSAJES DE AVISO DE VALIDACION DE nueva password
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 7){
            self::$alertas['error'][] = "El password debe contener al menos 7 caracteres";
        }
        return self::$alertas;
    }

    //METODO PARA VALIDAR AL INICIAR SESION
    public function validarLogin(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        return self::$alertas;
    }
    
    //METODO PARA VALIDAR EL EMAIL
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = "El email es incorrecto";
        }
        return self::$alertas;
    }


    //revisar si ya exite usuario
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'Ya existe un usuario registrado con ese correo';
        }

        return $resultado;
    }

    //Hashear una contra
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function token(){
        $this->token = uniqid();
    }

    public function verificarPasswordAndVerificado($password) {

        $resultado = password_verify($password, $this->password);
        
        if(!$resultado || !$this->confirmado){
          self::$alertas['error'][] = "El password es incorrecto o tu cuenta no ha sido confirmada";
        }else{
            return true;
        }
    }
    


}