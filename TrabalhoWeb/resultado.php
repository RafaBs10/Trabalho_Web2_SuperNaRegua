<?php
require 'dados.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$erros = [];

$nome = htmlspecialchars(($_POST['nome'] ?? ''));
if (empty($nome)) {
    $erros[] = 'Nome não encontrado';
}

$pessoas = htmlspecialchars(($_POST['pessoas'] ?? ''));
if (empty($pessoas)) {
    $erros[] = 'Pessoas não encontrado';
}

$horario = htmlspecialchars(($_POST['horario'] ?? ''));
if (empty($horario)) {
    $erros[] = 'Horário não encontrado';
}

$id = (int) ($_POST['id'] ?? 0);

$servicoResultado = buscaPorId($id);
if ($servicoResultado === null) {
    $erros[] = 'Serviço não encontrado';
}

// Valida se o horário enviado pertence aos horários reais do serviço
if ($servicoResultado !== null && !empty($horario)) {
    if (!in_array($horario, $servicoResultado['horarios'])) {
        $erros[] = 'Horário inválido para este serviço';
    }
}

if (!empty($erros)) {
    require 'header.php';
?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="alert alert-danger">
                    <h5 class="fw-bold">Erros encontrados:</h5>
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro): ?>
                            <li><?php echo $erro; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="javascript:history.back()" class="btn btn-dark rounded-pill">Voltar</a>
            </div>
        </div>
    </div>
<?php
    require 'footer.php';
    exit();
}

$ValorTotalDoCorte = $servicoResultado['valor'] * $pessoas;
$temHorarios = !empty($servicoResultado['horarios']);
?>

<?php require 'header.php'; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-3">
                <h1 class="text-center text-success" style="font-family: 'Georgia', serif;">Agendamento Confirmado</h1>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-white text-center" style="font-family: 'Georgia', serif;">
                    Resumo do Agendamento
                </div>
                <ul class="list-group list-group-flush" style="font-family: 'Georgia', serif;">
                    <li class="list-group-item"><strong>Cliente: </strong><?php echo $nome; ?></li>
                    <li class="list-group-item"><strong>Nome do serviço: </strong><?php echo $servicoResultado['nome']; ?></li>
                    <li class="list-group-item"><strong>Tipo de serviço: </strong><?php echo $servicoResultado['tipo']; ?></li>
                    <li class="list-group-item"><strong>Valor do serviço: R$ </strong><?php echo number_format($servicoResultado['valor'], 2, ',', '.'); ?></li>
                    <li class="list-group-item"><strong>Horário Marcado: </strong><?php echo $horario; ?></li>
                    <li class="list-group-item"><strong>Quantidade de pessoas: </strong><?php echo $pessoas; ?></li>
                    <li class="list-group-item">
                        <strong>Disponibilidade: </strong>
                        <?php if ($temHorarios): ?>
                            <span class="badge bg-success">Horários disponíveis</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Sem horários — agendamento sujeito a confirmação</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item list-group-item-success text-end">
                        <strong>Valor total: R$ </strong><?php echo number_format($ValorTotalDoCorte, 2, ',', '.'); ?>
                    </li>
                </ul>
                <div class="text-center mt-3 mb-1">
                    <a href="index.php" class="btn btn-dark rounded-pill" style="font-family: 'Georgia', serif;">Retornar a pagina de serviços</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>