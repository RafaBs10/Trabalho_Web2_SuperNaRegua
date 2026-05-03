<?php
$servicos = [
    ["id" => 1, "nome" => "Corte degradê", "tipo" => "Corte", "valor" => 30, "horarios" => ["09:00", "10:00", "11:00"]],
    ["id" => 2, "nome" => "Corte social", "tipo" => "Corte", "valor" => 25, "horarios" => ["13:00", "14:00"]],
    ["id" => 3, "nome" => "Barba completa", "tipo" => "Barba", "valor" => 20, "horarios" => ["09:30", "15:00"]],
    ["id" => 4, "nome" => "Corte + barba", "tipo" => "Combo", "valor" => 45, "horarios" => ["10:30", "16:00"]],
    ["id" => 5, "nome" => "Sobrancelha", "tipo" => "Extra", "valor" => 10, "horarios" => ["11:30", "17:00"]],
    ["id" => 6, "nome" => "Pigmentação", "tipo" => "Premium", "valor" => 35, "horarios" => ["14:30", "18:00"]]
];

function buscaPorId($id)
{
    global $servicos;
    foreach ($servicos as $servico) {
        if ($id == $servico["id"]) {
            return $servico;
        }
    }
    return null;
}
