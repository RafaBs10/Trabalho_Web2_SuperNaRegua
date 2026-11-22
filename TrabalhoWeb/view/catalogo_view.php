<?php require 'view/header.php'; ?>

<div class="container mt-4">
    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['sucesso']) ?>
        </div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['erro']) ?>
        </div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <h1 class="mb-1">Serviços</h1>
    <p class="text-muted">Total de serviços: <?= htmlspecialchars($totalServicos ?? 0) ?></p>

    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="index.php?acao=catalogo&tipo=todos" 
           class="btn <?= empty($tipoSelecionado) ? 'btn-dark' : 'btn-outline-dark' ?>">
            Todos
        </a>
        <?php if (!empty($listaTipos)): ?>
            <?php foreach ($listaTipos as $tipo): ?>
                <a href="index.php?acao=catalogo&tipo=<?= urlencode($tipo) ?>"
                   class="btn <?= (isset($tipoSelecionado) && $tipoSelecionado === $tipo) ? 'btn-dark' : 'btn-outline-dark' ?>">
                    <?= htmlspecialchars($tipo) ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (empty($servicos)): ?>
        <div class="alert alert-info">
            Nenhum serviço encontrado nesta categoria.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Horários</th>
                        <th style="width: 120px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $servico): ?>
                        <tr <?= empty($servico["horarios"]) ? 'class="table-secondary"' : '' ?>>
                            <td class="fw-bold"><?= htmlspecialchars($servico["nome"]) ?></td>
                            <td>
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars($servico["tipo"]) ?>
                                </span>
                            </td>
                            <td>R$ <?= number_format($servico["valor"], 2, ',', '.') ?></td>
                            <td>
                                <?php if (empty($servico["horarios"])): ?>
                                    <span class="badge bg-danger">Sem horários</span>
                                <?php else: ?>
                                    <?= htmlspecialchars(implode(', ', $servico["horarios"])) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?acao=detalhes&id=<?= htmlspecialchars($servico['id']) ?>" class="btn btn-sm btn-primary">
                                    Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require 'view/footer.php'; ?>
