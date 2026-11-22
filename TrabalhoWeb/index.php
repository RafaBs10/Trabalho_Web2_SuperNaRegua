<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// autoload para carregar automaticamente as classes de controller e model sem require manual
spl_autoload_register(function ($classe) {
    $pastas = ['controller/', 'model/'];

    foreach ($pastas as $pasta) {
        $arquivo = __DIR__ . '/' . $pasta . $classe . '.php';
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});

require_once 'config/conexao.php';

// rota padrao se nenhuma acao for informada
$acao = $_GET['acao'] ?? 'catalogo';

// tabela de rotas do sistema
switch ($acao) {
    case 'catalogo':
        $controller = new ServicoController($pdo);
        $controller->catalogo();
        break;

    case 'detalhes':
        $controller = new ServicoController($pdo);
        $controller->detalhes();
        break;

    case 'login':
        $controller = new UsuarioController($pdo);
        $controller->login();
        break;

    case 'cadastro':
        $controller = new UsuarioController($pdo);
        $controller->cadastro();
        break;

    case 'logout':
        $controller = new UsuarioController($pdo);
        $controller->logout();
        break;

    case 'admin_servicos':
        $controller = new ServicoController($pdo);
        $controller->admin_servicos();
        break;

    case 'admin_servico_form':
        $controller = new ServicoController($pdo);
        $controller->admin_servico_form();
        break;

    case 'admin_servico_salvar':
        $controller = new ServicoController($pdo);
        $controller->admin_servico_salvar();
        break;

    case 'admin_servico_excluir':
        $controller = new ServicoController($pdo);
        $controller->admin_servico_excluir();
        break;

    case 'agendar':
        $controller = new AgendarController($pdo);
        $controller->agendar();
        break;

    case 'meus_agendamentos':
        $controller = new AgendarController($pdo);
        $controller->meus_agendamentos();
        break;

    case 'cancelar_agendamento':
        $controller = new AgendarController($pdo);
        $controller->cancelar_agendamento();
        break;

    default:
        http_response_code(404);
        $controller = new ServicoController($pdo);
        $controller->naoEncontrado();
        break;
}
