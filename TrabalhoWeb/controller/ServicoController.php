<?php

class ServicoController extends Controller
{
    private $servicoModel;

    // recebe conexao repassada pelo ponto de entrada index
    public function __construct($pdo)
    {
        $this->servicoModel = new ServicoModel($pdo);
    }

    // catalogo de servicos com suporte a filtro e cookie
    public function catalogo()
    {
        $lista_tipos = ['Corte', 'Barba', 'Combo', 'Extra', 'Premium'];
        $tipo_filtrado = null;

        // confere se o usuario escolheu um filtro pela url
        if (isset($_GET['tipo'])) {
            $tipo_param = trim($_GET['tipo']);

            if ($tipo_param === 'todos' || $tipo_param === '') {
                // remove cookie quando seleciona ver todos
                setcookie('ultimo_tipo_servico', '', time() - 3600, '/');
                $tipo_filtrado = null;
            } elseif (in_array($tipo_param, $lista_tipos, true)) {
                $tipo_filtrado = $tipo_param;
                // guarda a preferencia em cookie valido por 30 dias
                setcookie('ultimo_tipo_servico', $tipo_filtrado, time() + (30 * 24 * 60 * 60), '/');
            }
        } elseif (isset($_COOKIE['ultimo_tipo_servico'])) {
            // se nao veio pela url reaplica o valor guardado no cookie caso seja valido
            $cookie_valor = trim($_COOKIE['ultimo_tipo_servico']);
            if (in_array($cookie_valor, $lista_tipos, true)) {
                $tipo_filtrado = $cookie_valor;
            }
        }

        // busca os servicos ativos no model
        $servicos = $this->servicoModel->listarAtivos($tipo_filtrado);

        // carrega os horarios de cada um para exibir na tela
        foreach ($servicos as &$item) {
            $item['horarios'] = $this->servicoModel->buscarHorarios($item['id']);
        }
        unset($item);

        $contador = count($servicos);

        // envia para a visualizacao
        $this->render('catalogo_view', [
            'servicos' => $servicos,
            'totalServicos' => $contador,
            'listaTipos' => $lista_tipos,
            'tipoSelecionado' => $tipo_filtrado
        ]);
    }

    // exibe os detalhes completos de um servico
    public function detalhes()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirecionar('index.php?acao=catalogo');
            return;
        }

        $servico = $this->servicoModel->buscarPorId($id);

        if ($servico) {
            $servico['horarios'] = $this->servicoModel->buscarHorarios($id);
        }

        $this->render('detalhes_view', ['servicos' => $servico]);
    }

    // painel de gerenciamento exclusivo do administrador
    public function admin_servicos()
    {
        $this->exigirPerfil('admin');

        $servicos = $this->servicoModel->listarTodos();

        foreach ($servicos as &$item) {
            $item['horarios'] = $this->servicoModel->buscarHorarios($item['id']);
        }
        unset($item);

        $this->render('admin_servicos_view', [
            'servicos' => $servicos,
            'totalServicos' => count($servicos)
        ]);
    }

    // tela com formulario para cadastrar ou editar servico
    public function admin_servico_form()
    {
        $this->exigirPerfil('admin');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $servico = null;
        $horariosString = '';

        if ($id) {
            $servico = $this->servicoModel->buscarPorId($id);

            if (!$servico) {
                $_SESSION['erro'] = "Serviço não encontrado.";
                $this->redirecionar('index.php?acao=admin_servicos');
                return;
            }

            $horarios = $this->servicoModel->buscarHorarios($id);
            $horariosString = implode(', ', $horarios);
        }

        $lista_tipos = ['Corte', 'Barba', 'Combo', 'Extra', 'Premium'];

        $this->render('admin_servico_form_view', [
            'servico' => $servico,
            'horariosString' => $horariosString,
            'listaTipos' => $lista_tipos
        ]);
    }

    // processa gravacao do servico vindo do formulario por post
    public function admin_servico_salvar()
    {
        $this->exigirPerfil('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('index.php?acao=admin_servicos');
            return;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $valor_bruto = str_replace(',', '.', trim($_POST['valor'] ?? ''));
        $valor = is_numeric($valor_bruto) ? (float) $valor_bruto : 0;
        $descricao = trim($_POST['descricao'] ?? '');
        $horarios_campo = trim($_POST['horarios'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        $lista_tipos = ['Corte', 'Barba', 'Combo', 'Extra', 'Premium'];

        // validacoes de campos obrigatorios
        if ($nome === '' || mb_strlen($nome) > 100) {
            $_SESSION['erro'] = "Informe um nome válido com até 100 caracteres.";
            $destino = $id ? "index.php?acao=admin_servico_form&id={$id}" : "index.php?acao=admin_servico_form";
            $this->redirecionar($destino);
            return;
        }

        if (!in_array($tipo, $lista_tipos, true)) {
            $_SESSION['erro'] = "Selecione um tipo de serviço válido.";
            $destino = $id ? "index.php?acao=admin_servico_form&id={$id}" : "index.php?acao=admin_servico_form";
            $this->redirecionar($destino);
            return;
        }

        if ($valor <= 0) {
            $_SESSION['erro'] = "Informe um valor válido maior que zero.";
            $destino = $id ? "index.php?acao=admin_servico_form&id={$id}" : "index.php?acao=admin_servico_form";
            $this->redirecionar($destino);
            return;
        }

        if ($descricao === '') {
            $_SESSION['erro'] = "Preencha a descrição do serviço.";
            $destino = $id ? "index.php?acao=admin_servico_form&id={$id}" : "index.php?acao=admin_servico_form";
            $this->redirecionar($destino);
            return;
        }

        // trata a lista de horarios digitada separada por virgula
        $horarios_processados = [];
        if ($horarios_campo !== '') {
            $pedacos = explode(',', $horarios_campo);
            foreach ($pedacos as $p) {
                $h = trim($p);
                // aceita formato 09:00 ou similar
                if (preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $h)) {
                    if (!in_array($h, $horarios_processados, true)) {
                        $horarios_processados[] = $h;
                    }
                }
            }
            sort($horarios_processados);
        }

        // tratamento da foto enviada no formulario
        $imagem_final = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extensao, $extensoes_permitidas, true)) {
                $pasta_assets = __DIR__ . '/../assets/';
                if (!is_dir($pasta_assets)) {
                    mkdir($pasta_assets, 0777, true);
                }
                $nome_arquivo = 'servico_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extensao;
                $destino_arquivo = $pasta_assets . $nome_arquivo;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino_arquivo)) {
                    $imagem_final = 'assets/' . $nome_arquivo;
                }
            }
        }

        // se for edicao e nenhuma imagem nova subiu mantem a que ja estava
        if ($id) {
            $ok = $this->servicoModel->atualizar($id, $nome, $tipo, $valor, $descricao, $imagem_final, $ativo, $horarios_processados);
            if ($ok) {
                $_SESSION['sucesso'] = "Serviço atualizado com sucesso!";
            } else {
                $_SESSION['erro'] = "Não foi possível atualizar o serviço.";
            }
        } else {
            // para novos registros se nao mandou imagem usa a padrao
            if ($imagem_final === null) {
                $imagem_final = 'assets/logo.jpeg';
            }
            $ok = $this->servicoModel->criar($nome, $tipo, $valor, $descricao, $imagem_final, $ativo, $horarios_processados);
            if ($ok) {
                $_SESSION['sucesso'] = "Serviço cadastrado com sucesso!";
            } else {
                $_SESSION['erro'] = "Não foi possível cadastrar o serviço.";
            }
        }

        $this->redirecionar('index.php?acao=admin_servicos');
    }

    // processa exclusao do servico caso nao tenha agendamentos vinculados
    public function admin_servico_excluir()
    {
        $this->exigirPerfil('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('index.php?acao=admin_servicos');
            return;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['erro'] = "Serviço não encontrado.";
            $this->redirecionar('index.php?acao=admin_servicos');
            return;
        }

        $servico = $this->servicoModel->buscarPorId($id);
        if (!$servico) {
            $_SESSION['erro'] = "Serviço não encontrado.";
            $this->redirecionar('index.php?acao=admin_servicos');
            return;
        }

        // bloqueia caso haja agendamentos para preservar o historico
        if ($this->servicoModel->temAgendamentos($id)) {
            $_SESSION['erro'] = "Este serviço possui agendamentos e não pode ser excluído.";
            $this->redirecionar('index.php?acao=admin_servicos');
            return;
        }

        $ok = $this->servicoModel->excluir($id);
        if ($ok) {
            $_SESSION['sucesso'] = "Serviço excluído com sucesso!";
        } else {
            $_SESSION['erro'] = "Não foi possível excluir o serviço.";
        }

        $this->redirecionar('index.php?acao=admin_servicos');
    }

    // pagina 404 amigavel
    public function naoEncontrado()
    {
        $this->render('404_view');
    }
}
