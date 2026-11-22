<?php require 'view/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="text-center mb-4">Entrar</h2>

                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['erro']) ?>
                    </div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['sucesso'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['sucesso']) ?>
                    </div>
                    <?php unset($_SESSION['sucesso']); ?>
                <?php endif; ?>

                <form method="POST" action="index.php?acao=login">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="seuemail@exemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Entrar</button>
                </form>

                <div class="text-center mt-3">
                    <span class="text-muted">Ainda não tem conta?</span>
                    <a href="index.php?acao=cadastro" class="text-danger fw-bold text-decoration-none">Cadastre-se</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'view/footer.php'; ?>