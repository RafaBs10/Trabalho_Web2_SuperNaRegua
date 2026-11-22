<?php require 'view/header.php'; ?>

<div class="container mt-4">
    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['erro']) ?>
        </div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['sucesso']) ?>
        </div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php $servico = $servicos ?? null; ?>

    <?php if (empty($servico)): ?>
        <div class="alert alert-warning">
            <h4>Serviço não encontrado</h4>
            <p>O serviço solicitado não está disponível ou não foi encontrado.</p>
            <a href="index.php?acao=catalogo" class="btn btn-primary mt-2">Voltar ao catálogo</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="card-title mb-0"><?= htmlspecialchars($servico['nome']) ?></h2>
                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($servico['tipo']) ?></span>
                        </div>

                        <?php if (!empty($servico['imagem'])): ?>
                            <img src="<?= htmlspecialchars($servico['imagem']) ?>" 
                                 alt="<?= htmlspecialchars($servico['nome']) ?>" 
                                 class="img-fluid rounded mb-3" 
                                 style="max-height: 350px; width: 100%; object-fit: cover;">
                        <?php endif; ?>

                        <p class="card-text text-muted"><?= htmlspecialchars($servico['descricao']) ?></p>
                        <h4 class="text-success fw-bold">
                            R$ <?= number_format($servico['valor'], 2, ',', '.') ?>
                        </h4>

                        <div class="mt-3">
                            <strong>Horários disponíveis:</strong>
                            <p class="mt-1">
                                <?php if (!empty($servico['horarios'])): ?>
                                    <span class="text-secondary"><?= htmlspecialchars(implode(', ', $servico['horarios'])) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Consulte disponibilidade</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Agendar este serviço</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'cliente'): ?>
                                <form method="POST" action="index.php?acao=agendar">
                                    <input type="hidden" name="servico_id" value="<?= htmlspecialchars($servico['id']) ?>">

                                    <div class="mb-3">
                                        <label for="data_agendamento" class="form-label">Data desejada:</label>
                                        <input type="date" 
                                               name="data_agendamento" 
                                               id="data_agendamento" 
                                               class="form-control" 
                                               min="<?= date('Y-m-d') ?>" 
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="horario" class="form-label">Horário:</label>
                                        <select name="horario" id="horario" class="form-select" required>
                                            <option value="">Selecione um horário</option>
                                            <?php if (!empty($servico['horarios'])): ?>
                                                <?php foreach ($servico['horarios'] as $h): ?>
                                                    <option value="<?= htmlspecialchars($h) ?>"><?= htmlspecialchars($h) ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="08:00">08:00</option>
                                                <option value="09:00">09:00</option>
                                                <option value="10:00">10:00</option>
                                                <option value="11:00">11:00</option>
                                                <option value="13:00">13:00</option>
                                                <option value="14:00">14:00</option>
                                                <option value="15:00">15:00</option>
                                                <option value="16:00">16:00</option>
                                                <option value="17:00">17:00</option>
                                                <option value="18:00">18:00</option>
                                                <option value="19:00">19:00</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pessoas" class="form-label">Quantidade de pessoas:</label>
                                        <select name="pessoas" id="pessoas" class="form-select" required>
                                            <option value="1" selected>1 pessoa</option>
                                            <option value="2">2 pessoas</option>
                                            <option value="3">3 pessoas</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        Confirmar Agendamento
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">
                                    Você está conectado como <strong>Administrador</strong>. O agendamento é reservado para perfis de cliente.
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-3">
                                Faça login para realizar o agendamento deste serviço.
                            </div>
                            <div class="d-grid gap-2">
                                <a href="index.php?acao=login" class="btn btn-primary">Entrar com minha conta</a>
                                <a href="index.php?acao=cadastro" class="btn btn-outline-secondary">Cadastre-se</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="index.php?acao=catalogo" class="btn btn-outline-dark w-100">
                        &larr; Voltar ao catálogo
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require 'view/footer.php'; ?>
