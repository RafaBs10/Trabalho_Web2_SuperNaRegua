<?php
require 'dados.php';



$nome = htmlspecialchars($_POST['nome']);
$pessoas = htmlspecialchars($_POST['pessoas']);
$horario = htmlspecialchars($_POST['horario']);
$id = (int) $_POST['id'];




//como o formulario ainda n foi feito, estou testando com valores dados aleatorios
$nome = "Luan";
$pessoas = 2;
$horario = "09:00";
$id = 1;
$servicoResultado = buscaPorId($id);

$ValorTotalDoCorte = $servicoResultado['valor'] * $pessoas;

?>

<?php require 'header.php'; ?>
<div class="container mt-4">     <!-- o container da parte principal da pagina, deixando tudo dentro dele centralizado -->
    <div class="row justify-content-center">      <!-- o row sendo utilizado para centralizar as colunas no centro -->
        <div class="col-md-5">      <!-- colunas de tamanho 5 por 12  -->
            <div class="text-center mb-3">      <!-- afata o titulo do card -->
                <h1 class="text-center text-danger" style="font-family: 'Georgia', serif;">Agendamento Confirmado</h1>
            </div>
               <!-- todos os elementos do formulario vao entrar dentro desse card -->
            <div class="card">
                <!-- define o cabeçalho do card, colocando a cor de fundo como preta e o texto branco -->
                <div class="card-header bg-dark text-white text-center" style="font-family: 'Georgia', serif;">
                    Resumo do Agendamento
                </div>
                <!-- dentro do card é criada uma lista sem enumeraçao, com todos os elementos -->
                <ul class="list-group  list-group-flush" style="font-family: 'Georgia', serif;">
                <li class=" list-group-item"> <strong>Cliente: </strong><?php echo $nome; ?> </li>
                <li class=" list-group-item"> <strong>Nome do servico: </strong><?php echo $servicoResultado['nome']; ?> </li>
                <li class=" list-group-item"> <strong>Tipo de servico: </strong><?php echo $servicoResultado['tipo']; ?> </li>
                <li class=" list-group-item"> <strong>Valor do servico: R$ </strong><?php echo number_format($servicoResultado['valor'], 2, ',', '.'); ?> </li>
                <li class=" list-group-item"> <strong>Horario Marcado: </strong><?php echo $horario; ?> </li>
                <li class=" list-group-item"> <strong>Quantidade de pessoas: </strong><?php echo $pessoas; ?> </li>
                <!-- cor verde no valor total, deixando a linha toda na cor verde -->
                <li class=" list-group-item list-group-item-success text-end"> <strong>Valor total: R$  </strong><?php echo number_format($ValorTotalDoCorte, 2, ',', '.'); ?> </li>
                </ul>

                <!-- botao para retornar a pagina inicial -->
                <div class="text-center mt-3 mb-3">
                    <a href = "index.php" class = "btn btn-dark" style="font-family: 'Georgia', serif;"> Retornar a pagina de serviços</a>
                </div>
            </div>

        </div>
    </div>
</div>


<?php require 'footer.php'; ?>