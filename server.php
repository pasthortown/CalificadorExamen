<?php
include_once('./args.php');
include_once('./generador.php');

class BDD
{
    private $Conexion;
    private $servidor;
    private $usuario;
    private $clave;

    public function __construct($servidor, $usuario, $clave){
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->clave = $clave;
    }

    public function ejecutarConsulta($sql,$parametros){
        $this->Conexion = new PDO("mysql:host=$this->servidor;charset=utf8", $this->usuario, $this->clave,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $stmt = $this->Conexion->prepare($sql);
        $stmt->execute($parametros);
        $salida = array();
        $cuenta = $stmt->rowCount();
        if($cuenta>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $salida[]=$row;
            }
        }else{
            $salida[]=$cuenta;
        }
        $this->Conexion = null;
        return $salida;
    }
}

class BDD2
{
    private $Conexion;
    private $servidor;
    private $usuario;
    private $clave;
    private $baseDatos;

    public function __construct($servidor, $usuario, $clave, $baseDatos){
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->baseDatos = $baseDatos;
    }

    public function ejecutarConsulta($sql,$parametros){
        $this->Conexion = new PDO("mysql:host=$this->servidor;dbname=$this->baseDatos;charset=utf8", $this->usuario, $this->clave,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $stmt = $this->Conexion->prepare($sql);
        $stmt->execute($parametros);
        $salida = array();
        $cuenta = $stmt->rowCount();
        if($cuenta>0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $salida[]=$row;
            }
        }else{
            $salida[]=$cuenta;
        }
        $this->Conexion = null;
        return $salida;
    }
}

header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

function route(){
    $uri = $_SERVER['REQUEST_URI'];
    $route_path = explode('?',$uri)[0];
    $route_parts = explode('/',$route_path);

    $ipBDD = "192.168.20.10";
    $usuarioBDD = "prueba";
    $claveBDD = "12345678";

    $action = strtolower($route_parts[count($route_parts)-1]);
    $apiGenerator = new ApiGenerator($ipBDD, $usuarioBDD, $claveBDD);
    return json_encode($apiGenerator->$action(getArgs()));
}



echo route();

