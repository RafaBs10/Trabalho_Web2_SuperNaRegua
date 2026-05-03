<?php
require 'dados.php';



$nome = $_POST['nome'];
$pessoas = $_POST['pessoas'];
$horario = $_POST['horario'];
$id = $_POST['id'];




 //como o formulario ainda n foi feito, estou testando com valores dados aleatorios

$nome = "Luan";
$pessoas = 2;
$horario = "09:00";
$id = 1;

$servicoResultado = buscaPorId($id);

$ValorTotalDoCorte = $servicoResultado['valor'] * $pessoas;

?>

<?php require 'header.php'; ?>

<div class ="container mt-4">

<h1>Agendamento Confirmado</h1>
<p>Cliente:<?php echo $nome; ?></p>
<p>Nome do servico: <?php echo $servicoResultado['nome'];?></p>
<p>Tipo de servico: <?php echo $servicoResultado['tipo'];?></p>
<p>Valor do servico: R$ <?php echo number_format($servicoResultado['valor'], 2, ',', '.'); ?></p>
<p>Horario Marcado: <?php echo $horario;?></p>
<p>Quantidade de pessoas: <?php echo $pessoas;?></p>
<p>Valor total: R$ <?php echo number_format($ValorTotalDoCorte, 2, ',', '.'); ?></p>

</div>
<?php require 'footer.php'; ?>
