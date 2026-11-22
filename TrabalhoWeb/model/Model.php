<?php
//guardar conexao pdo para qualquer model filho, pra fazer novas consultas sem conexao nova toda hora
//aq nao pode post, session, header ou echo
abstract class Model{

    protected $pdo; //usado para guardar conexao, e filhas enxergarem

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

}
?>