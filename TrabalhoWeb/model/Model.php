<?php

// classe base para os models do sistema guardando a conexao pdo
abstract class Model
{
    protected $pdo; // conexao compartilhada com as classes filhas

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
}
