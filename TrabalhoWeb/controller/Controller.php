<?php

abstract class Controller{


    public function render($view , $dados = []){
        //dados e opcional, se nao for recebido, gera um array vazio
        extract($dados);//extrai o dados convertendo array
        require "view/{$view}.php";
    }

    public function redirecionar($rota){
        header("Location: {$rota}");
        exit;
    }

    public function exigirLogin(){
        if (!isset($_SESSION["usuario_id"])) {
            $_SESSION['erro'] = "Faca login para continuar";
            $this->redirecionar('index.php?acao=login');
        }
    }

    public function exigirPerfil($perfilnecessario){
        $this->exigirLogin();
        if ($_SESSION['perfil'] !== $perfilnecessario) {
            $_SESSION['erro'] = "Perfil nao logado";
            $this->redirecionar('index.php?acao=catalogo');
        }
    }


}



?>