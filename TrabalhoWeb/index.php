<?php
require 'dados.php';

if (isset($_GET['tipo'])) {
    $tipoFiltro = $_GET['tipo'];
    $listaFiltrada = array_filter($servicos, function ($servico) use ($tipoFiltro) {
        return $servico["tipo"] == $tipoFiltro;
    });
} else {
    $listaFiltrada = $servicos;
}


$totalServicos = count($listaFiltrada);

$listaTipos = [];
foreach ($servicos as $servico) {
    if (!in_array($servico["tipo"], $listaTipos)) {
        $listaTipos[] = $servico["tipo"];
    }
}
?>

<?php require 'header.php'; ?>

<div class="container mt-4">
    <h1>Serviços</h1>
    <p>Total de serviços: <?php echo $totalServicos; ?></p>

    <div class="mb-3">
        <a href="index.php" class="btn <?php echo !isset($_GET['tipo']) ? 'btn-dark' : 'btn-outline-dark'; ?>">
            Todos
        </a>
        <?php foreach ($listaTipos as $tipo): ?>
            <a href="index.php?tipo=<?php echo $tipo; ?>"
                class="btn <?php echo isset($_GET['tipo']) && $_GET['tipo'] == $tipo ? 'btn-dark' : 'btn-outline-dark'; ?>">
                <?php echo $tipo; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Horários</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaFiltrada as $servico): ?>
                    <tr <?php echo empty($servico["horarios"]) ? 'class="table-secondary"' : ''; ?>>
                        <td><?php echo htmlspecialchars($servico["nome"]); ?></td>
                        <td>
                            <span class="badge bg-primary">
                                <?php echo htmlspecialchars($servico["tipo"]); ?>
                            </span>
                        </td>
                        <td>R$ <?php echo number_format($servico["valor"], 2, ',', '.'); ?></td>
                        <td>
                            <?php if (empty($servico["horarios"])): ?>
                                <span class="badge bg-danger">Sem horários</span>
                            <?php else: ?>
                                <?php echo implode(', ', $servico["horarios"]); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="detalhes.php?id=<?php echo $servico["id"]; ?>" class="btn btn-sm btn-primary">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <div>
            <div class="card-body">

                <h2 class="mb-4">Agendamento</h2>

                <form action="resultado.php" method="post">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" name="nome" id="nome" placeholder="Seu nome" required maxlength="30"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="pessoa" class="form-label">Quantidade de pessoas:</label>
                        <select name="pessoa" id="pessoa" class="form-select" required>
                            <option value="">Selecione</option>
                            <option value="1">1 pessoa</option>
                            <option value="2">2 pessoas</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="horario" class="form-label">Horário:</label>
                        <input type="time" name="horario" id="horario" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Serviço:</label>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico1" value="1" required>
                            <label for="servico1" class="form-check-label">Corte degradê — R$ 30,00</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico2" value="2">
                            <label for="servico2" class="form-check-label">Corte social — R$ 25,00</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico3" value="3">
                            <label for="servico3" class="form-check-label">Barba completa — R$ 20,00</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico4" value="4">
                            <label for="servico4" class="form-check-label">Corte + barba — R$ 45,00</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico5" value="5">
                            <label for="servico5" class="form-check-label">Sobrancelha — R$ 10,00</label>
                        </div>

                        <div class="form-check">
                            <input type="radio" name="id" id="servico6" value="6">
                            <label for="servico6" class="form-check-label">Pigmentação — R$ 35,00</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Enviar inscrição</button>

                </form>

            </div>
        </div>

    </div>



    <?php require 'footer.php'; ?>