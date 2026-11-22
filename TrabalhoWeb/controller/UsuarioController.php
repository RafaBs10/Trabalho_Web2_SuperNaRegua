<?php

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct($pdo)
    {
        //instanciando o usuario model
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function login()
    {
        // verificando a requisicao
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //pegando a senha e email do post
            $email = $_POST['email'] ?? "";
            $senha = $_POST['senha'] ?? "";

            //chamdno buscar por email para pegar os dados

            $buscar_email = $this->usuarioModel->buscarPorEmail($email);

            //verificando se o usuario nao foi encontrado ou se a senha ta errada

            if (!$buscar_email || !password_verify($senha, $buscar_email['senha'])) {
                $_SESSION['erro'] = "Senha errada ou Usuario nao encontrado";
                $this->redirecionar('index.php?acao=login');
            }

            //se nao caiu no if salva os dados do usuario na sessao
            $_SESSION['usuario_id'] = $buscar_email['id'];
            $_SESSION['usuario_nome'] = $buscar_email['nome'];
            $_SESSION['perfil'] = $buscar_email['perfil'];

            //se o perfil for admin manda pro admin de servicos
            //agora de se for cliente ele manda pro catalogo
            if ($buscar_email['perfil'] == 'admin') {
                $this->redirecionar('index.php?acao=admin_servicos');
            } elseif ($buscar_email['perfil'] == 'cliente') {
                $this->redirecionar('index.php?acao=catalogo');
            }

            return;
        }

        //se for get abra a view do login
        $this->render('login_view');
    }

    public function cadastro()
    {
        //se for post entra no if
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //recuperando os dados pelo post

            $nome = $_POST['nome'] ?? "";
            $email = $_POST['email'] ?? "";
            $senha = $_POST['senha'] ?? "";
            $senha_confirma = $_POST['confirmacao'];

            //verificando se as senha e a confirmacao eh a mesma
            if ($senha !== $senha_confirma) {
                $_SESSION['erro'] = 'As senhas devem ser iguais';
                $this->redirecionar('index.php?acao=cadastro');
            }

            //vendo se o email ja foi cadastrado, o metodo da true se achar no bd

            if ($this->usuarioModel->buscarPorEmail($email)) {
                $_SESSION['erro'] = 'Email ja cadastrado';
                $this->redirecionar('index.php?acao=cadastro');
            }

            //gerando a senha hash
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            //salvar no banco
            $this->usuarioModel->cadastrar($nome, $email, $senha_hash);

            //guardando a mensagem que o cadastro foi feito com sucesso
            $_SESSION['sucesso'] = 'Cadastrado com sucesso';
            $this->redirecionar('index.php?acao=login');
            return;
        }

        // se vier por get abre o cadastro view
        $this->render('cadastro_view');
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirecionar('index.php?acao=login');
    }
}