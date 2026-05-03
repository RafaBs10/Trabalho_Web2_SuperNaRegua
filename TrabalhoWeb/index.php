<?php
require 'dados.php';

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $filtrados = array_filter($servicos, function ($servico) use ($tipo) {
        return $servico["tipo"] == $tipo;
    });
} else {
    $filtrados = $servicos;
}
$total = count($filtrados);

$tipos = [];
foreach ($servicos as $valor) {
    if (!in_array($valor["tipo"], $tipos)) {
        $tipos[] = $valor["tipo"];
    }
}
?>

<?php require 'header.php'; ?>

<div class="container mt-4">
    <h1>Serviços</h1>
    <p>Total de serviços: <?php echo $total; ?></p>

    <div class="mb-3">
        <a href="index.php" class="btn <?php echo !isset($_GET['tipo']) ? 'btn-dark' : 'btn-outline-dark'; ?>">
            Todos
        </a>
        <?php foreach ($tipos as $t): ?>
            <a href="index.php?tipo=<?php echo $t; ?>"
                class="btn <?php echo isset($_GET['tipo']) && $_GET['tipo'] == $t ? 'btn-dark' : 'btn-outline-dark'; ?>">
                <?php echo $t; ?>
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
            <?php foreach ($filtrados as $servico): ?>
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