<?php

class ServicoModel extends Model
{
    //metodo q busca servicos ativos
    public function listarAtivos()
    {
        //comando sql que busca os servicos ativos e ordenando pelo nome

        $sql = "SELECT * FROM servicos WHERE ativo = 1 ORDER BY nome ASC";

        //usando a conexao herdada pelo model
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        //retorna todos os registros
        return $stmt->fetchAll();
    }

    //busca servico pelo id
    public function buscarPorId($id)
    {
        //usando comando sql para buscar o id e compara se eh igual ao buscado
        $sql = "SELECT * FROM servicos WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        //executando passando o id recebida pelo parametro
        $stmt->execute([

            ':id' => $id

        ]);

        //usando aq o fetch normal pq ele retorna apenas um registro
        return $stmt->fetch();
    }
}