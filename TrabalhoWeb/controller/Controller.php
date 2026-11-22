<?php

abstract class Controller
{
    // carrega a view passando as variaveis extraidas
    public function render($view, $dados = [])
    {
        extract($dados);
        require "view/{$view}.php";
    }

    // redireciona o navegador para a rota desejada
    public function redirecionar($rota)
    {
        header("Location: {$rota}");
        exit;
    }

    // garante que o usuario esteja autenticado
    public function exigirLogin()
    {
        if (!isset($_SESSION["usuario_id"])) {
            $_SESSION['erro'] = "Faça login para continuar.";
            $this->redirecionar('index.php?acao=login');
        }
    }

    // garante que o usuario tenha o perfil exigido
    public function exigirPerfil($perfilnecessario)
    {
        $this->exigirLogin();

        if ($_SESSION['perfil'] !== $perfilnecessario) {
            $_SESSION['erro'] = "Acesso negado: seu perfil não tem permissão para acessar esta área.";
            $this->redirecionar('index.php?acao=catalogo');
        }
    }
}
