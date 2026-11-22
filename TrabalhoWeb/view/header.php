<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupernaRégua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="assets/logo.jpeg">
</head>

<body>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="assets/logo.jpeg" alt="SupernaRégua">
                SupernaRégua
            </a>

            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span class="text-white">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>

                    <?php if ($_SESSION['perfil'] === 'admin'): ?>
                        <a href="index.php?acao=admin_servicos" class="btn btn-sm btn-outline-light">Painel</a>
                    <?php else: ?>
                        <a href="index.php?acao=meus_agendamentos" class="btn btn-sm btn-outline-light">Meus agendamentos</a>
                    <?php endif; ?>

                    <a href="index.php?acao=logout" class="btn btn-sm btn-danger">Sair</a>
                <?php else: ?>
                    <a href="index.php?acao=cadastro" class="btn btn-sm btn-outline-light">Cadastre-se</a>
                    <a href="index.php?acao=login" class="btn btn-sm btn-light">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</body>