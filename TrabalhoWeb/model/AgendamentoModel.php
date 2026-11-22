<?php

class AgendamentoModel extends Model
{
    // Insere uma nova solicitacao de agendamento vinculada ao cliente logado
    public function criar($usuario_id, $servico_id, $data_agendamento, $horario, $pessoas, $valor_total)
    {
        $sql = "INSERT INTO agendamentos (usuario_id, servico_id, data_agendamento, horario, pessoas, valor_total, status) 
                VALUES (:usuario_id, :servico_id, :data_agendamento, :horario, :pessoas, :valor_total, 'pendente')";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':servico_id' => $servico_id,
            ':data_agendamento' => $data_agendamento,
            ':horario' => $horario,
            ':pessoas' => $pessoas,
            ':valor_total' => $valor_total
        ]);
    }

    // Lista somente as solicitacoes do cliente logado (filtro obrigatorio por usuario_id)
    public function listarPorUsuario($usuario_id)
    {
        $sql = "SELECT a.*, s.nome AS servico_nome, s.tipo AS servico_tipo, s.valor AS servico_valor_unitario
                FROM agendamentos a 
                JOIN servicos s ON a.servico_id = s.id 
                WHERE a.usuario_id = :usuario_id 
                ORDER BY a.data_agendamento DESC, a.horario DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return $stmt->fetchAll();
    }

    // Cancela uma solicitacao garantindo que ela pertence ao usuario da sessao
    public function cancelar($id, $usuario_id)
    {
        $sql = "UPDATE agendamentos 
                SET status = 'cancelado' 
                WHERE id = :id AND usuario_id = :usuario_id AND status != 'cancelado'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);
    }

    // Busca um agendamento especifico conferindo se pertence ao usuario
    public function buscarPorIdEDono($id, $usuario_id)
    {
        $sql = "SELECT * FROM agendamentos WHERE id = :id AND usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);

        return $stmt->fetch();
    }
}
