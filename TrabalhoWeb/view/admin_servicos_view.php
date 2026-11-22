<?php require 'view/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Gerenciar serviços</h2>
            <p class="text-muted mb-0">Total de serviços: <?= htmlspecialchars($totalServicos ?? 0) ?></p>
        </div>
        <a href="index.php?acao=admin_servico_form" class="btn btn-danger">Novo serviço</a>
    </div>

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

    <?php if (empty($servicos)): ?>
        <div class="card p-4 text-center">
            <p class="text-muted mb-0">Nenhum serviço cadastrado até o momento.</p>
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
                        <th style="width: 170px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $servico): ?>
                        <tr <?= empty($servico['ativo']) ? 'class="table-secondary"' : '' ?>>
                            <td class="fw-bold">
                                <?= htmlspecialchars($servico['nome']) ?>
                                <?php if (empty($servico['ativo'])): ?>
                                    <span class="badge bg-secondary ms-1">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars($servico['tipo']) ?>
                                </span>
                            </td>
                            <td>R$ <?= number_format($servico['valor'], 2, ',', '.') ?></td>
                            <td>
                                <?php if (empty($servico['horarios'])): ?>
                                    <span class="badge bg-danger">Sem horários</span>
                                <?php else: ?>
                                    <?= htmlspecialchars(implode(', ', $servico['horarios'])) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="index.php?acao=admin_servico_form&id=<?= htmlspecialchars($servico['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger">
                                        Editar
                                    </a>

                                    <form method="POST" 
                                          action="index.php?acao=admin_servico_excluir" 
                                          onsubmit="return confirm('Deseja realmente excluir este serviço?');" 
                                          style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($servico['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require 'view/footer.php'; ?>
