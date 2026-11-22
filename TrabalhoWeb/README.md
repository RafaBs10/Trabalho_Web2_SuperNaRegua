# Super na Régua — Sistema Web em MVC

Trabalho Prático das disciplinas **Aplicações para Web 2 (WEB2)** e **Laboratório de Web 2 (LWEB2)**  
Curso Técnico em Informática — 3º Ano — **CEFET-MG (Unidade Contagem)**  
Professora: **Elizabeth Duane**

---

## Integrantes do Grupo
* **Luan Henrique Neves Miranda**
* **Miguel Silva Rodrigues**
* **Rafael Borges Silva**

---

## Como Rodar o Projeto Localmente (XAMPP / MAMP)

1. **Local dos arquivos:**
   Copie a pasta `TrabalhoWeb` para dentro do diretório `htdocs` do seu XAMPP:
   `C:\xampp\htdocs\TrabalhoWeb`

2. **Iniciar o servidor local:**
   No **XAMPP Control Panel**, inicie os módulos:
   * **Apache** (porta 80)
   * **MySQL** (porta 3306)

3. **Banco de Dados:**
   * Acesse no navegador: `http://localhost/phpmyadmin`
   * Crie uma base de dados com o nome: `banco` (Collation: `utf8mb4_general_ci`)
   * Vá na aba **Importar**, selecione o arquivo `database/banco.sql` e clique em **Executar**.

4. **Conexão:**
   A conexão fica em `config/conexao.php`. O padrão já está configurado para:
   * Host: `localhost`
   * Banco: `banco`
   * Usuário: `root`
   * Senha: *(vazio)*

5. **Abrir no navegador:**
   Acesse: `http://localhost/TrabalhoWeb/index.php`

---

## Usuários de Teste Cadastrados

| Perfil | E-mail | Senha | O que acessa |
|---|---|---|---|
| **Administrador** | `admin@supernaregua.com` | `123456` | Painel de controle, gerenciar serviços (criar, editar e excluir) |
| **Cliente 1** | `joao@email.com` | `123456` | Catálogo, detalhes, agendar corte e ver/cancelar seus agendamentos |
| **Cliente 2** | `maria@email.com` | `123456` | Catálogo, detalhes, agendar corte e ver/cancelar seus agendamentos |

---

## Como o Sistema está Composto e Dividido (Arquitetura MVC)

O sistema segue a separação estrita de responsabilidades em camadas exigida na disciplina:

```
TrabalhoWeb/
├── index.php                  # Ponto único de entrada: inicia a sessão, roda o autoload e escolhe a rota pelo switch
├── .htaccess                  # Configuração de URLs amigáveis com mod_rewrite (item extra)
├── config/
│   └── conexao.php            # Cria e disponibiliza a conexão PDO com o MySQL
├── database/
│   └── banco.sql              # Script SQL com a estrutura das 4 tabelas e dados de teste
├── css/
│   └── style.css              # Estilos personalizados no padrão visual da barbearia
├── assets/                    # Fotos dos cortes/serviços e logotipo
│
├── model/                     # CAMADA MODEL: Onde fica todo e qualquer comando SQL do sistema
│   ├── Model.php              # Classe abstrata base que guarda o $pdo protegido
│   ├── UsuarioModel.php       # Consultas de login e cadastro de usuários com hash
│   ├── ServicoModel.php       # Consultas do catálogo e operações de CRUD e horários
│   └── AgendamentoModel.php   # Criação, listagem por cliente e cancelamento de agendamentos
│
├── controller/                # CAMADA CONTROLLER: Recebe os pedidos, valida regras e chama os models
│   ├── Controller.php         # Classe abstrata base com render(), redirecionar(), exigirLogin() e exigirPerfil()
│   ├── UsuarioController.php  # Controla o fluxo de login, cadastro e logout
│   ├── ServicoController.php  # Controla o catálogo, cookie de filtro e CRUD do admin
│   └── AgendarController.php  # Controla os agendamentos e cancelamentos do cliente
│
└── view/                      # CAMADA VIEW: Templates contendo apenas HTML e exibição com htmlspecialchars
    ├── header.php             # Menu superior dinâmico (muda conforme visitante, cliente ou admin)
    ├── footer.php             # Rodapé da página
    ├── catalogo_view.php      # Lista de serviços com botões de filtro por categoria
    ├── detalhes_view.php      # Foto, descrição e formulário para agendar o serviço
    ├── login_view.php         # Tela de autenticação com avisos de erro/sucesso
    ├── cadastro_view.php      # Formulário de criação de conta de cliente
    ├── meus_agendamentos_view.php # Histórico onde o cliente vê apenas os próprios agendamentos e pode cancelar
    ├── admin_servicos_view.php    # Painel do admin com a tabela de serviços, editar e excluir
    ├── admin_servico_form_view.php # Formulário único usado tanto para novo serviço quanto para edição
    └── 404_view.php           # Tela exibida caso alguém tente acessar uma rota que não existe
```

---

## Regras de Segurança Implementadas
1. **Ponto Único de Entrada:** Nenhuma view ou controller é aberto diretamente pelo navegador; todo pedido passa por `index.php?acao=...`.
2. **Controle de Acesso no Servidor:** Visitantes não abrem páginas de clientes ou admin, e clientes não entram no painel de admin (o PHP barra e redireciona).
3. **Senhas Seguras:** Utilização de `password_hash()` no cadastro e `password_verify()` no login.
4. **Proteção contra Injeção SQL:** 100% das consultas no banco utilizam prepared statements com PDO.
5. **Proteção XSS:** Todo dado exibido nas views passa por `htmlspecialchars()`.
6. **Operações Críticas por POST:** Cadastrar, editar, excluir e cancelar funcionam exclusivamente via requisições POST com redirecionamento (POST-Redirect-GET).
7. **Dono via Sessão:** O ID do cliente vem sempre da variável `$_SESSION['usuario_id']`, nunca de campos do formulário.

---

## Extra: URLs Amigáveis (`mod_rewrite`)

O arquivo `.htaccess` já está incluso na raiz do projeto. Ele permite navegar por rotas limpas como:
* `/catalogo`
* `/detalhes/1`
* `/login`
* `/cadastro`
* `/meus-agendamentos`
* `/admin/servicos`
* `/admin/servicos/novo`

Para ativar no Apache do XAMPP:
1. Abra o arquivo `httpd.conf` do Apache.
2. Certifique-se de que a linha `LoadModule rewrite_module modules/mod_rewrite.so` está sem `#` no início.
3. Garanta que a diretiva `AllowOverride All` esteja habilitada para a pasta `htdocs`.
4. Reinicie o Apache.

*(Se o mod_rewrite não for ativado, o sistema continua funcionando perfeitamente por `index.php?acao=...`)*.
