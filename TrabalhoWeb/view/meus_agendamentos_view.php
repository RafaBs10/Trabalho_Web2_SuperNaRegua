<?php require 'view/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Meus Agendamentos</h2>
        <a href="index.php?acao=catalogo" class="btn btn-primary">Novo Agendamento</a>
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

    <?php if (empty($agendamentos)): ?>
        <div class="card p-4 text-center">
            <h5 class="text-muted">Você ainda não realizou nenhum agendamento.</h5>
            <p class="text-muted">Explore nossos serviços e agende seu horário com a gente!</p>
            <div>
                <a href="index.php?acao=catalogo" class="btn btn-outline-primary">Ver serviços</a>
            </div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Serviço</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Pessoas</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($agendamento['servico_nome']) ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($agendamento['servico_tipo']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?></td>
                            <td><?= htmlspecialchars($agendamento['horario']) ?></td>
                            <td><?= htmlspecialchars($agendamento['pessoas']) ?></td>
                            <td class="text-success fw-bold">
                                R$ <?= number_format($agendamento['valor_total'], 2, ',', '.') ?>
                            </td>
                            <td>
                                <?php if ($agendamento['status'] === 'pendente'): ?>
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                <?php elseif ($agendamento['status'] === 'confirmado'): ?>
                                    <span class="badge bg-success">Confirmado</span>
                                <?php elseif ($agendamento['status'] === 'cancelado'): ?>
                                    <span class="badge bg-secondary">Cancelado</span>
                                <?php else: ?>
                                    <span class="badge bg-info"><?= htmlspecialchars($agendamento['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($agendamento['status'] !== 'cancelado'): ?>
                                    <form method="POST" 
                                          action="index.php?acao=cancelar_agendamento" 
                                          onsubmit="return confirm('Deseja realmente cancelar este agendamento?');" 
                                          style="display:inline;">
                                        <input type="hidden" name="agendamento_id" value="<?= htmlspecialchars($agendamento['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Cancelar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">Nenhuma ação</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require 'view/footer.php'; ?>
