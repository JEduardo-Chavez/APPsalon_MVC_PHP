<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    //metodo de login
    public static function login(Router $router){
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin(); 
            
            if( empty($alertas) ){
                //comprobar que exista el usuario
                $usuario  = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //verificar el password y confirmado
                    if( $usuario->verificarPasswordAndVerificado($auth->password ) ){
                        //AUTENTICAR EL USUARIO
                        if( isset($_SESSION) ){
                            session_start();
                        }

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else{
                            header('Location: /cita');
                        }

                    }
                } else{
                    Usuario::setAlerta('error', "El usuario no existe");
                }
            } 
        }
        //obtener las alertas
        $alertas = Usuario::getAlertas();

        $router->render('/auth/login', [
            'alertas'=>$alertas
        ]);
    }

    //METODO PARA CERRAR SESION
    public static function logout(Router $router){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    //METODO DE OLVIDAR CONTRA
    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if( empty($alertas) ){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === '1'){
                    //GENERAN UN TOKEN
                    $usuario->token();
                    $usuario->guardar();
                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstruccion();
                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Las instrucciones fueron enviadas a tu correo');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }

        //FIN DEL $_SERVER    
        }

        $alertas=Usuario::getAlertas();
        
        $router->render('/auth/olvide', [
            'alertas'=>$alertas
        ]);
    }

    //METODO PARA RECUPERAR CONTRA
    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //BUSCAR UN USUARIO POR TOKEN
        $usuario = Usuario::where('token', $token);
        if( empty($usuario) ){
            Usuario::setAlerta('error', 'Solicitud no valida');
            $error=true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //LEER NUEVO PASSWORD Y VALIDAR
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            //GUARDAR NUEVO PASSWORD
            if( empty($alertas) ){
                //borramos pass anterior
                $usuario->password = null;
                //agregamos la pass en POST en el objeto de usuario
                $usuario->password = $password->password;
                //hasheamos
                $usuario->hashPassword();
                //volvemos el token a null
                $usuario->token = null;
                //guardamos la nueva pass
                $resultado = $usuario->guardar();

                //redirecionamos a login
                if($resultado){
                    header('Location: /');
                }
            }
        }
        
        $alertas=Usuario::getAlertas();
        $router->render('/auth/recuperar-pass', [
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    //METODO PARA CREAR UNA CUENTA
    public static function crear(Router $router){
        $usuario = new Usuario;

        //alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que alerta este vacio
            if(empty($alertas)){
                //verificar que el usuario no esta registrado anteriormente
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //hashar password
                    $usuario->hashPassword();

                    //generar un TOKEN
                    $usuario->token();

                    //enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //CREAR EL USUARIO
                    $resultado = $usuario->guardar();
                    // debuguear($usuario);
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }
        
        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    //METODO DEL MENSAJE DE CONFIRMACION
    public static function mensaje(Router $router){

        $router->render('/auth/mensaje');

    }

    //METODO DESPUES DE CONFIRMAR
    public static function confirmar(Router $router){

        $alertas = [];
        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);
        if( empty($usuario) ){
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no es valido');
        }else{
            //cambiar a usuario confirmado en bd
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Tu cuenta fue confirmada correctamente');
        }
        //obtener la alertas
        $alertas = Usuario::getAlertas();
        //renderizar la vista
        $router->render('/auth/confirmar-cuenta', [
            'alertas'=>$alertas
        ]);

    }

}