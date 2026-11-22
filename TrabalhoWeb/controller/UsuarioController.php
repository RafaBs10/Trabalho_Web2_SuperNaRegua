<?php

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct($pdo)
    {
        // instanciando o usuario model
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function login()
    {
        // se o usuario ja estiver autenticado manda para a pagina inicial
        if (isset($_SESSION['usuario_id'])) {
            $this->redirecionar('index.php?acao=catalogo');
            return;
        }

        // verificando a requisicao
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // pegando a senha e email do post
            $email = trim($_POST['email'] ?? "");
            $senha = $_POST['senha'] ?? "";

            if ($email === "" || $senha === "") {
                $_SESSION['erro'] = "Preencha todos os campos.";
                $this->redirecionar('index.php?acao=login');
                return;
            }

            // chamando buscar por email para pegar os dados
            $buscar_email = $this->usuarioModel->buscarPorEmail($email);

            // verificando se o usuario nao foi encontrado ou se a senha ta errada
            if (!$buscar_email || !password_verify($senha, $buscar_email['senha'])) {
                $_SESSION['erro'] = "Senha errada ou Usuario nao encontrado";
                $this->redirecionar('index.php?acao=login');
                return;
            }

            // se nao caiu no if salva os dados do usuario na sessao
            $_SESSION['usuario_id'] = $buscar_email['id'];
            $_SESSION['usuario_nome'] = $buscar_email['nome'];
            $_SESSION['perfil'] = $buscar_email['perfil'];

            // se o perfil for admin manda pro painel de servicos
            // agora se for cliente ele manda pro catalogo
            if ($buscar_email['perfil'] === 'admin') {
                $this->redirecionar('index.php?acao=admin_servicos');
            } else {
                $this->redirecionar('index.php?acao=catalogo');
            }

            return;
        }

        // se for get abra a view do login
        $this->render('login_view');
    }

    public function cadastro()
    {
        // se o usuario ja estiver logado redireciona para a home
        if (isset($_SESSION['usuario_id'])) {
            $this->redirecionar('index.php?acao=catalogo');
            return;
        }

        // se for post entra no if
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // recuperando os dados pelo post
            $nome = trim($_POST['nome'] ?? "");
            $email = trim($_POST['email'] ?? "");
            $senha = $_POST['senha'] ?? "";
            $senha_confirma = $_POST['confirmacao'] ?? "";

            if ($nome === "" || $email === "" || $senha === "" || $senha_confirma === "") {
                $_SESSION['erro'] = 'Preencha todos os campos obrigatórios';
                $this->redirecionar('index.php?acao=cadastro');
                return;
            }

            if (strlen($senha) < 6) {
                $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres';
                $this->redirecionar('index.php?acao=cadastro');
                return;
            }

            // verificando se a senha e a confirmacao sao iguais
            if ($senha !== $senha_confirma) {
                $_SESSION['erro'] = 'As senhas devem ser iguais';
                $this->redirecionar('index.php?acao=cadastro');
                return;
            }

            // vendo se o email ja foi cadastrado
            if ($this->usuarioModel->buscarPorEmail($email)) {
                $_SESSION['erro'] = 'Email ja cadastrado';
                $this->redirecionar('index.php?acao=cadastro');
                return;
            }

            // gerando a senha hash
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // grava o novo usuario no banco
            $this->usuarioModel->cadastrar($nome, $email, $senha_hash);

            // guardando a mensagem que o cadastro foi feito com sucesso
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
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        $this->redirecionar('index.php?acao=login');
    }
}
