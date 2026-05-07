<?php

require "dados.php";

$id = $_GET['id'] ?? 0;

$procurarservico = buscaPorId($id);


if ($procurarservico == null) {
    echo 'servico nao encontrado';
    exit();
}




?>





<?php require "header.php" ?>


<div class="container mt-4">


    <div class="card mb-4">
        <div class="card-body">

            <h2>DETALHES:</h2>

            <?php
            //exibe info das id, o implode pega a info de array e converte, o number formata o numero
            echo '<p><strong>Corte:</strong> ' . $procurarservico['nome'] . '</p>';
            echo '<p><strong>Tipo:</strong> ' . $procurarservico['tipo'] . '</p>';
            echo '<p><strong>Valor:</strong> R$ ' . number_format($procurarservico['valor'], 2, ',', '.') . '</p>';
            echo '<p><strong>Horários:</strong> ' . implode(", ", $procurarservico["horarios"]) . '</p>';
            ?>

        </div>
    </div>

    <div class="card-body">
        <form action="resultado.php" method="post">


            <h2>Formulario de Agendamento</h2>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" class="form-control" required>

            <label for="pessoas">Pessoas:</label>
            <select name="pessoas" class="form-select" required>

                <option value="">Selecionar</option>
                <option value="1">1</option>
                <option value="2">2</option>

            </select>

            <label for="horario">Horário desejado:</label>
            <select name="horario" id="horario" class="form-select" required>
                <option value="">Selecione um horário</option>
                <?php foreach ($procurarservico['horarios'] as $horarios): ?>
                    <option value="<?php echo $horarios; ?>"><?php echo $horarios; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="id" value="<?php echo $procurarservico['id']; ?>">
            <button type="submit" class="btn btn-primary">Enviar</button>

        </form>
    </div>
</div>



<?php require "footer.php" ?>