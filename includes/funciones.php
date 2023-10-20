<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//FUNCITON PARA SUMAR EL PRESIO DE LOS SERVICIOS
function esUltimo(string $actual, string $proximo):bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}

//FUNCION QUE REVISA QUE EL USUARIO ESTE AUTENTICADO
function isAuth () : void{
    if( !isset($_SESSION['login']) ){
        header('Location: /');
    }
}

//FUNCIONA PARA REVISAR QUE SEAN ADMINISTRADORES
function isAdmin() : void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}
