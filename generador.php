<?php

class ApiGenerator
{
    private $ipBDD;
    private $usuarioBDD;
    private $claveBDD;

    public function __construct($ipBDD, $usuarioBDD, $claveBDD) {
      $this->ipBDD = $ipBDD;
      $this->usuarioBDD = $usuarioBDD;
      $this->claveBDD = $claveBDD;
    }

    public function bases() {
        $bdd = new BDD($this->ipBDD,$this->usuarioBDD, $this->claveBDD);
        return $bdd->ejecutarConsulta('SHOW databases;',array());
    }

    public function tablas($args) {
        $dataBase = $args['dataBase'];
        $bdd2 = new BDD2($this->ipBDD,$this->usuarioBDD,$this->claveBDD,$dataBase);
        $respuestaTablas = $bdd2->ejecutarConsulta('SHOW tables;',array());
        $tablas = [];
        foreach($respuestaTablas as $SelectTabla) {
            $nombreTabla = $SelectTabla["Tables_in_".$dataBase];
            array_push($tablas,["nombreTabla"=>$nombreTabla]);
        }
        return $tablas;
    }

    public function usuarios($args) {
        $dataBase = $args['dataBase'];
        $bdd2 = new BDD2($this->ipBDD,$this->usuarioBDD,$this->claveBDD,$dataBase);
        $usuarios = $bdd2->ejecutarConsulta('SELECT * FROM Usuarios;',array());
        return $usuarios;
    }
}
