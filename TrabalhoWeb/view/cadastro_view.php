<?php require 'view/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="text-center mb-4">Criar conta</h2>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['erro']) ?>
                    </div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>

                <form method="POST" action="index.php?acao=cadastro">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Seu nome completo" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="seuemail@exemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="mínimo 6 caracteres" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmacao" class="form-label">Confirmar senha:</label>
                        <input type="password" name="confirmacao" id="confirmacao" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Cadastrar</button>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted">Já tem conta?</span>
                    <a href="index.php?acao=login" class="text-danger fw-bold text-decoration-none">Entrar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'view/footer.php'; ?>