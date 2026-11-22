<?php

class AgendarController extends Controller
{
    private $agendamentoModel;
    private $servicoModel;

    public function __construct($pdo)
    {
        $this->agendamentoModel = new AgendamentoModel($pdo);
        $this->servicoModel = new ServicoModel($pdo);
    }

    // acao para processar a solicitacao de agendamento (Cliente)
    public function agendar()
    {
        $this->exigirLogin();

        if ($_SESSION['perfil'] !== 'cliente') {
            $_SESSION['erro'] = "Apenas clientes podem realizar agendamentos.";
            $this->redirecionar('index.php?acao=catalogo');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servico_id = filter_input(INPUT_POST, 'servico_id', FILTER_VALIDATE_INT);
            $data_agendamento = $_POST['data_agendamento'] ?? '';
            $horario = trim($_POST['horario'] ?? '');
            $pessoas = filter_input(INPUT_POST, 'pessoas', FILTER_VALIDATE_INT) ?: 1;

            if (!$servico_id || empty($data_agendamento) || empty($horario)) {
                $_SESSION['erro'] = "Preencha todos os campos para agendar.";
                $this->redirecionar('index.php?acao=detalhes&id=' . ($servico_id ?: ''));
                return;
            }

            // busca informacoes do servico no banco para calcular o valor
            $servico = $this->servicoModel->buscarPorId($servico_id);

            if (!$servico) {
                $_SESSION['erro'] = "Serviço não encontrado.";
                $this->redirecionar('index.php?acao=catalogo');
                return;
            }

            // o dono vem obrigatoriamente da sessao
            $usuario_id = $_SESSION['usuario_id'];
            $valor_total = $servico['valor'] * $pessoas;

            $sucesso = $this->agendamentoModel->criar(
                $usuario_id,
                $servico_id,
                $data_agendamento,
                $horario,
                $pessoas,
                $valor_total
            );

            if ($sucesso) {
                $_SESSION['sucesso'] = "Agendamento realizado com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro ao processar agendamento. Tente novamente.";
            }

            $this->redirecionar('index.php?acao=meus_agendamentos');
            return;
        }

        $this->redirecionar('index.php?acao=catalogo');
    }

    // acao para listar apenas as proprias solicitacoes do usuario logado
    public function meus_agendamentos()
    {
        $this->exigirLogin();

        $usuario_id = $_SESSION['usuario_id'];
        $agendamentos = $this->agendamentoModel->listarPorUsuario($usuario_id);

        $this->render('meus_agendamentos_view', [
            'agendamentos' => $agendamentos
        ]);
    }

    // acao para cancelar solicitacao pertencente ao usuario logado (somente por POST)
    public function cancelar_agendamento()
    {
        $this->exigirLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('index.php?acao=meus_agendamentos');
            return;
        }

        $agendamento_id = filter_input(INPUT_POST, 'agendamento_id', FILTER_VALIDATE_INT);
        $usuario_id = $_SESSION['usuario_id'];

        if ($agendamento_id) {
            $this->agendamentoModel->cancelar($agendamento_id, $usuario_id);
            $_SESSION['sucesso'] = "Agendamento cancelado com sucesso!";
        } else {
            $_SESSION['erro'] = "Agendamento inválido.";
        }

        $this->redirecionar('index.php?acao=meus_agendamentos');
    }
}
