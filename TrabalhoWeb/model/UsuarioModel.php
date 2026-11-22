<?php

class UsuarioModel extends Model
{
    //metodo q busca usuario pelo email
    public function buscarPorEmail($email)
    {
        //comando sql para buscar pelo email

        $sql = "SELECT * FROM usuarios WHERE email = :email";
  
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([':email' => $email]);

        return $stmt->fetch();
    }

    //metodo que insere cliente no bd
    public function cadastrar($nome, $email, $senhaHash)
    {
        //comando sql q eh usado para inserir no banco de dados


        $sql = "INSERT INTO usuarios (nome,email,senha,perfil) VALUES (:nome, :email, :senha, 'cliente')";

        $stmt = $this->pdo->prepare($sql);

        $resultado = $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash

        ]);


        return $resultado;
    }
}