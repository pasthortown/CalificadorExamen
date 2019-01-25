<?php

function getArgs() {
    $argumentos = $_REQUEST;
    $mensaje_header = getallheaders();
    $mensaje_body = json_decode(file_get_contents('php://input'),true);
    if($mensaje_header == null){
        $mensaje_header = array();
    }
    if($mensaje_body == null){
        $mensaje_body = array();
    }
    if($argumentos == null){
        $argumentos = array();
    }

    $args = array_merge($argumentos, $mensaje_header, $mensaje_body);
    return $args;
}
