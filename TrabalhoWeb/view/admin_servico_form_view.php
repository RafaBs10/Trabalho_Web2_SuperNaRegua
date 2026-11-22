<?php require 'view/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">
                    <?= !empty($servico['id']) ? 'Editar serviço' : 'Novo serviço' ?>
                </h2>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($_SESSION['erro']) ?>
                    </div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>

                <form method="POST" action="index.php?acao=admin_servico_salvar" enctype="multipart/form-data">
                    <?php if (!empty($servico['id'])): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($servico['id']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" 
                               name="nome" 
                               id="nome" 
                               class="form-control" 
                               value="<?= htmlspecialchars($servico['nome'] ?? '') ?>" 
                               placeholder="Ex.: Corte degradê" 
                               maxlength="100" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="">Selecione o tipo</option>
                            <?php foreach ($listaTipos as $tipo): ?>
                                <option value="<?= htmlspecialchars($tipo) ?>" 
                                    <?= (isset($servico['tipo']) && $servico['tipo'] === $tipo) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipo) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor (R$):</label>
                        <input type="number" 
                               step="0.01" 
                               min="0.01" 
                               name="valor" 
                               id="valor" 
                               class="form-control" 
                               value="<?= htmlspecialchars($servico['valor'] ?? '') ?>" 
                               placeholder="Ex.: 30.00" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <textarea name="descricao" 
                                  id="descricao" 
                                  rows="3" 
                                  class="form-control" 
                                  placeholder="Detalhes sobre o serviço prestado..." 
                                  required><?= htmlspecialchars($servico['descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="horarios" class="form-label">Horários (separados por vírgula):</label>
                        <input type="text" 
                               name="horarios" 
                               id="horarios" 
                               class="form-control" 
                               value="<?= htmlspecialchars($horariosString ?? '') ?>" 
                               placeholder="Ex.: 09:00, 10:00, 11:00">
                        <small class="text-muted">Informe os horários no formato HH:MM separados por vírgula.</small>
                    </div>

                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem:</label>
                        <?php if (!empty($servico['imagem'])): ?>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="<?= htmlspecialchars($servico['imagem']) ?>" 
                                     alt="Imagem atual" 
                                     class="rounded" 
                                     style="width: 70px; height: 70px; object-fit: cover;">
                                <small class="text-muted">Imagem atual (envie outra só se quiser trocar)</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" 
                               name="ativo" 
                               id="ativo" 
                               class="form-check-input" 
                               value="1" 
                               <?= (!isset($servico['ativo']) || $servico['ativo'] == 1) ? 'checked' : '' ?>>
                        <label for="ativo" class="form-check-label">Serviço ativo</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            <?= !empty($servico['id']) ? 'Salvar alterações' : 'Cadastrar' ?>
                        </button>
                        <a href="index.php?acao=admin_servicos" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'view/footer.php'; ?>
