<?php
$servicos = [
    ["id" => 1, "nome" => "Corte degradê",       "tipo" => "Corte",   "valor" => 30, "horarios" => ["09:00", "10:00", "11:00"], "imagem" => "assets/degradee.jpg",   "descricao" => "O corte degradê é uma técnica moderna que cria uma transição suave entre os comprimentos do cabelo, deixando o visual limpo e estiloso."],
    ["id" => 2, "nome" => "Corte social",         "tipo" => "Corte",   "valor" => 25, "horarios" => ["13:00", "14:00"],           "imagem" => "assets/social.jpg",     "descricao" => "O corte social é clássico e elegante, ideal para ambientes formais e do dia a dia. Acabamento preciso e bem definido."],
    ["id" => 3, "nome" => "Barba completa",       "tipo" => "Barba",   "valor" => 20, "horarios" => ["09:30", "15:00"],           "imagem" => "assets/barba.jpg",      "descricao" => "Modelagem e aparamento completo da barba com toalha quente, produtos hidratantes e acabamento impecável."],
    ["id" => 4, "nome" => "Corte + barba",        "tipo" => "Combo",   "valor" => 45, "horarios" => ["10:30", "16:00"],           "imagem" => "assets/combo.jpg",      "descricao" => "Combo completo unindo corte de cabelo e barba completa com desconto especial. O melhor custo-benefício da barbearia."],
    ["id" => 5, "nome" => "Sobrancelha",          "tipo" => "Extra",   "valor" => 10, "horarios" => ["11:30", "17:00"],           "imagem" => "assets/sobrancelha.jpg", "descricao" => "Design e alinhamento de sobrancelha com pinça e navalha para um visual mais definido e harmonioso."],
    ["id" => 6, "nome" => "Pigmentação",          "tipo" => "Premium", "valor" => 35, "horarios" => ["14:30", "18:00"],           "imagem" => "assets/pigmentacao.jpg", "descricao" => "Pigmentação capilar para disfarçar falhas e uniformizar a cor do cabelo e barba, com resultado natural."],
    ["id" => 7, "nome" => "Relaxamento capilar",  "tipo" => "Premium", "valor" => 50, "horarios" => [],                          "imagem" => "assets/relaxamento.jpg", "descricao" => "Tratamento para alisar e reduzir o volume dos fios, proporcionando mais maciez e facilidade no dia a dia."]
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
