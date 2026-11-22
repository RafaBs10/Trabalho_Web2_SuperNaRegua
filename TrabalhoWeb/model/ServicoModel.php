<?php

class ServicoModel extends Model
{
    // busca servicos ativos, podendo filtrar por tipo
    public function listarAtivos($tipo = null)
    {
        if ($tipo) {
            $sql = "SELECT * FROM servicos WHERE ativo = 1 AND tipo = :tipo ORDER BY nome ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':tipo' => $tipo]);
        } else {
            $sql = "SELECT * FROM servicos WHERE ativo = 1 ORDER BY nome ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // lista todos os servicos para a tabela do administrador
    public function listarTodos()
    {
        $sql = "SELECT * FROM servicos ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // busca um servico especifico pelo id
    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM servicos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // busca a lista de horarios de um determinado servico
    public function buscarHorarios($servico_id)
    {
        $sql = "SELECT hora FROM horarios WHERE servico_id = :servico_id ORDER BY hora ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':servico_id' => $servico_id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // confere se o servico ja tem algum agendamento feito
    public function temAgendamentos($servico_id)
    {
        $sql = "SELECT COUNT(*) FROM agendamentos WHERE servico_id = :servico_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':servico_id' => $servico_id]);

        return ((int) $stmt->fetchColumn()) > 0;
    }

    // cadastra um novo servico e seus horarios usando transacao
    public function criar($nome, $tipo, $valor, $descricao, $imagem, $ativo, $horarios = [])
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO servicos (nome, tipo, valor, descricao, imagem, ativo) 
                    VALUES (:nome, :tipo, :valor, :descricao, :imagem, :ativo)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':tipo' => $tipo,
                ':valor' => $valor,
                ':descricao' => $descricao,
                ':imagem' => $imagem,
                ':ativo' => $ativo
            ]);

            $servico_id = $this->pdo->lastInsertId();

            if (!empty($horarios)) {
                $sqlHora = "INSERT INTO horarios (servico_id, hora) VALUES (:servico_id, :hora)";
                $stmtHora = $this->pdo->prepare($sqlHora);
                foreach ($horarios as $h) {
                    $stmtHora->execute([
                        ':servico_id' => $servico_id,
                        ':hora' => $h
                    ]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    // atualiza os dados do servico e refaz os horarios
    public function atualizar($id, $nome, $tipo, $valor, $descricao, $imagem, $ativo, $horarios = [])
    {
        try {
            $this->pdo->beginTransaction();

            if ($imagem !== null) {
                $sql = "UPDATE servicos 
                        SET nome = :nome, tipo = :tipo, valor = :valor, descricao = :descricao, imagem = :imagem, ativo = :ativo 
                        WHERE id = :id";
                $parametros = [
                    ':id' => $id,
                    ':nome' => $nome,
                    ':tipo' => $tipo,
                    ':valor' => $valor,
                    ':descricao' => $descricao,
                    ':imagem' => $imagem,
                    ':ativo' => $ativo
                ];
            } else {
                $sql = "UPDATE servicos 
                        SET nome = :nome, tipo = :tipo, valor = :valor, descricao = :descricao, ativo = :ativo 
                        WHERE id = :id";
                $parametros = [
                    ':id' => $id,
                    ':nome' => $nome,
                    ':tipo' => $tipo,
                    ':valor' => $valor,
                    ':descricao' => $descricao,
                    ':ativo' => $ativo
                ];
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parametros);

            // limpa os horarios antigos para colocar a nova lista
            $sqlLimpa = "DELETE FROM horarios WHERE servico_id = :id";
            $stmtLimpa = $this->pdo->prepare($sqlLimpa);
            $stmtLimpa->execute([':id' => $id]);

            if (!empty($horarios)) {
                $sqlHora = "INSERT INTO horarios (servico_id, hora) VALUES (:servico_id, :hora)";
                $stmtHora = $this->pdo->prepare($sqlHora);
                foreach ($horarios as $h) {
                    $stmtHora->execute([
                        ':servico_id' => $id,
                        ':hora' => $h
                    ]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    // exclui o servico e seus horarios vinculados
    public function excluir($id)
    {
        try {
            $this->pdo->beginTransaction();

            $sqlHora = "DELETE FROM horarios WHERE servico_id = :id";
            $stmtHora = $this->pdo->prepare($sqlHora);
            $stmtHora->execute([':id' => $id]);

            $sqlServico = "DELETE FROM servicos WHERE id = :id";
            $stmtServico = $this->pdo->prepare($sqlServico);
            $stmtServico->execute([':id' => $id]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }
}
