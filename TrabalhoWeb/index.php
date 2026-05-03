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
</div>

<?php require 'footer.php'; ?>