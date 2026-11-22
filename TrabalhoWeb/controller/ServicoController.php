<?php

class ServicoController extends Controller
{
    private $servicoModel;

    // O construtor recebe a conexão que veio lá do index.php
    public function __construct($pdo)
    {
        //instancia de servico model

        $this->servicoModel = new ServicoModel($pdo);
    }

    // acao catalogo para visitantes
    public function catalogo()
    {
        //chamando o metod que busca os servicos ativos
        $listador = $this->servicoModel->listarAtivos();
        $contador = count($listador);
        $lista_cortes = ['Corte', 'Barba', 'Combo', 'Extra', 'Premium'];

        //passando array com servicos encontrados
        $this->render('catalogo_view', 
        [
            'servicos' => $listador,
            'totalServicos' => $contador,
            'listaTipos' => $lista_cortes
        ]);
    }

    // acao de ver detalhs de algum corte
    public function detalhes()
    {
        //puxando o id
        $id = $_GET['id'] ?? "";

        //chamando o metodo de buscar por id
        $buscar = $this->servicoModel->buscarPorId($id);

        //abre o detalhes passando o detalhes do servico encontrado pelo id
        $this->render('detalhes_view', ['servicos' => $buscar]);
    }
}