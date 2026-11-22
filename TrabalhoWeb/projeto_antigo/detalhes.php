<?php

require "dados.php";

$id = $_GET['id'] ?? 0;

$procurarservico = buscaPorId($id);

if ($procurarservico == null) {
    echo 'Serviço não encontrado';
    exit();
}

$temHorarios = !empty($procurarservico['horarios']);

?>

<?php require "header.php" ?>

<div class="container mt-4">

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($procurarservico['nome']); ?></h2>
                    <p><?php echo htmlspecialchars($procurarservico['descricao']); ?></p>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($procurarservico['tipo']); ?></p>
                    <p><strong>Valor:</strong> R$ <?php echo number_format($procurarservico['valor'], 2, ',', '.'); ?></p>
                    <p><strong>Horários:</strong>
                        <?php if ($temHorarios): ?>
                            <?php echo implode(", ", $procurarservico["horarios"]); ?>
                        <?php else: ?>
                            <span class="badge bg-danger">Sem horários disponíveis</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <img src="<?php echo htmlspecialchars($procurarservico['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($procurarservico['nome']); ?>"
                        style="width: 100%; border-radius: 14px;">
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">

            <?php if (!$temHorarios): ?>
                <div class="alert alert-warning">
                    <strong>Atenção:</strong> Este serviço não possui horários disponíveis no momento. O agendamento está temporariamente desabilitado.
                </div>
            <?php endif; ?>

            <form action="resultado.php" method="post">

                <h2>Formulário de Agendamento</h2>

                <label for="nome">Nome:</label>
                <input type="text" name="nome" class="form-control mb-2" <?php echo !$temHorarios ? 'disabled' : ''; ?> required>

                <label for="pessoas">Pessoas:</label>
                <select name="pessoas" class="form-select mb-2" <?php echo !$temHorarios ? 'disabled' : ''; ?> required>
                    <option value="">Selecionar</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>

                <label for="horario">Horário desejado:</label>
                <select name="horario" id="horario" class="form-select mb-2" <?php echo !$temHorarios ? 'disabled' : ''; ?> required>
                    <option value="">Selecione um horário</option>
                    <?php foreach ($procurarservico['horarios'] as $horarios): ?>
                        <option value="<?php echo $horarios; ?>"><?php echo $horarios; ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="id" value="<?php echo $procurarservico['id']; ?>">

                <button type="submit" class="btn btn-primary mt-3" <?php echo !$temHorarios ? 'disabled' : ''; ?>>
                    Enviar
                </button>

            </form>
        </div>
    </div>

</div>

<?php require "footer.php" ?>