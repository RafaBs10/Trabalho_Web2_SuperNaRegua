# Super na Régua — Sistema Web em MVC

Trabalho Prático das disciplinas **Aplicações para Web 2 (WEB2)** e **Laboratório de Web 2 (LWEB2)**  
Curso Técnico em Informática — 3º Ano — CEFET-MG (Unidade Contagem)  
Professora: Elizabeth Duane  

---

## Integrantes do Grupo
* **Luan Henrique Neves Miranda**
* **Miguel Silva Rodrigues**
* **Rafael Borges Silva**

---

## Usuários de Teste

| Perfil | E-mail | Senha |
|---|---|---|
| **Administrador** | `admin@supernaregua.com` | `123456` |
| **Cliente 1** | `joao@email.com` | `123456` |
| **Cliente 2** | `maria@email.com` | `123456` |

---

## Como rodar o projeto localmente (XAMPP / WAMP)

1. **Copiar a pasta do projeto**:
   Copie a pasta `TrabalhoWeb` para dentro do diretório `htdocs` do seu XAMPP:
   `C:\xampp\htdocs\TrabalhoWeb`

2. **Iniciar os serviços**:
   Abra o **XAMPP Control Panel** e clique em **Start** nos módulos:
   * **Apache** (porta padrão: 80 / 443)
   * **MySQL** (porta padrão: 3306)

3. **Configuração do Banco de Dados**:
   * Abra o navegador e acesse o phpMyAdmin: `http://localhost/phpmyadmin`
   * Crie um novo banco de dados chamado `banco` com collation `utf8mb4_general_ci`.
   * Selecione o banco `banco` e vá na aba **Importar**.
   * Escolha o arquivo `database/banco.sql` e clique em **Executar**.
   *(O script cria as tabelas `usuarios`, `servicos`, `horarios` e `agendamentos`, além de carregar os usuários de teste e serviços iniciais)*.

4. **Verificar a conexão**:
   Caso o seu MySQL utilize porta, usuário ou senha diferentes, ajuste o arquivo `config/conexao.php`:
   ```php
   $host = 'localhost';
   $banco = 'banco';
   $usuario = 'root';
   $password = '';
   ```

5. **Acessar o sistema**:
   Abra o navegador e acesse:  
   `http://localhost/TrabalhoWeb/index.php`

---

## Extra: URLs Amigáveis (`mod_rewrite` / `.htaccess`)

O projeto inclui um arquivo `.htaccess` pré-configurado para suportar rotas amigáveis como:
* `/catalogo`
* `/detalhes/1`
* `/login`
* `/cadastro`
* `/logout`
* `/meus-agendamentos`
* `/admin/servicos`
* `/admin/servicos/novo`
* `/admin/servicos/editar/1`

### Como ativar o `mod_rewrite` no Apache (XAMPP):
1. No XAMPP Control Panel, na linha do Apache, clique em **Config** e abra o arquivo `httpd.conf`.
2. Pressione `Ctrl + F` e procure por `rewrite_module`.
3. Certifique-se de que a linha não tem `#` no início:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Na mesma configuração, localize a seção `<Directory "C:/xampp/htdocs">` e confira se está configurado com:
   ```apache
   AllowOverride All
   ```
5. Salve o arquivo e reinicie o Apache.

> **Importante:** Caso o servidor não possua o `mod_rewrite` ativo ou o arquivo `.htaccess` não esteja presente, o sistema funciona integralmente por meio dos parâmetros padrão na URL (`index.php?acao=...`).

---

## Estrutura do Projeto MVC

```
TrabalhoWeb/
├── index.php                  # Ponto único de entrada (autoload, sessão, conexão e rotas)
├── .htaccess                  # Regras de reescrita para URLs amigáveis (extra)
├── config/
│   └── conexao.php            # Conexão PDO com tratamento de exceções
├── database/
│   └── banco.sql              # Estrutura das tabelas e dados iniciais de teste
├── css/
│   └── style.css              # Estilização visual personalizada da barbearia
├── assets/                    # Fotos dos serviços e logotipo
├── model/
│   ├── Model.php              # Classe base abstrata com $pdo protected
│   ├── UsuarioModel.php       # Consultas e cadastros de usuários (password_hash)
│   ├── ServicoModel.php       # CRUD completo e horários de serviços (transações PDO)
│   └── AgendamentoModel.php   # Agendamentos, consultas por dono e cancelamento
├── controller/
│   ├── Controller.php         # Classe base abstrata (render, redirecionar, exigirLogin, exigirPerfil)
│   ├── UsuarioController.php  # Login, cadastro de clientes e logout
│   ├── ServicoController.php  # Catálogo, detalhes, cookie de filtro e CRUD do admin
│   └── AgendarController.php  # Criação, listagem e cancelamento seguro de agendamentos
└── view/
    ├── header.php             # Cabeçalho unificado com navbar dinâmica
    ├── footer.php             # Rodapé padronizado
    ├── catalogo_view.php      # Catálogo com filtros de tipo e lista de serviços
    ├── detalhes_view.php      # Detalhes do serviço com formulário de agendamento
    ├── login_view.php         # Tela de autenticação com tratamento de mensagens
    ├── cadastro_view.php      # Tela de registro de novo cliente
    ├── meus_agendamentos_view.php # Histórico e cancelamento exclusivo do cliente
    ├── admin_servicos_view.php    # Painel administrativo com listagem de serviços
    ├── admin_servico_form_view.php # Formulário único para cadastrar e editar serviços
    └── 404_view.php           # Tela amigável de erro 404
```
