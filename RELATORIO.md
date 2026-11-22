# Relatório de Implementação: Sistema em MVC — Super na Régua

**Disciplinas:** Aplicações para Web 2 (WEB2) e Laboratório de Web 2 (LWEB2)  
**Curso:** Técnico em Informática — 3º Ano — CEFET-MG Unidade Contagem  
**Professora:** Elizabeth Duane  

---

## 1. Identificação

* **Nome do sistema:** Super na Régua (Barbearia)
* **Integrantes do grupo:**
  1. Luan Henrique Neves Miranda
  2. Miguel Silva Rodrigues
  3. Rafael Borges Silva
* **URL do sistema publicado:** `https://supernaregua.gamer.gd/index.php`
* **Link do vídeo de apresentação (YouTube/Drive):** `[COLE_AQUI_O_LINK_DO_SEU_VIDEO]`
* **Contas e senhas dos usuários de teste:**

| Perfil | E-mail | Senha | Observação |
|---|---|---|---|
| **Administrador** | `admin@supernaregua.com` | `123456` | Acesso total ao painel administrativo e CRUD |
| **Cliente 1** | `joao@email.com` | `123456` | Cliente com agendamento ativo |
| **Cliente 2** | `maria@email.com` | `123456` | Cliente com agendamento pendente |

---

## 2. O que foi implementado

### 2.1 Comparação com a proposta do 3º bimestre

| Funcionalidade Planejada | Status | Descrição da Implementação |
|---|---|---|
| **Controle de Acesso e Perfis** | Concluído | Verificação no servidor com `exigirLogin()` e `exigirPerfil('admin')`. Visitante não acessa telas restritas e cliente não acessa área admin. |
| **Autenticação Segura** | Concluído | Senha criptografada com `password_hash()` no cadastro e verificada via `password_verify()` no login. Sessão PHP gerencia identificação. |
| **Catálogo de Serviços** | Concluído | Listagem dinâmica dos serviços ativos com filtros por categoria (Corte, Barba, Combo, Extra, Premium). |
| **Cookie de Preferência** | Concluído | Cookie `ultimo_tipo_servico` com validade de 30 dias que memoriza a categoria filtrada, removido ao selecionar "Todos". |
| **Detalhes do Serviço** | Concluído | Exibição da foto, descrição, preço e horários disponíveis, com formulário integrado para agendamento. |
| **Solicitação de Agendamento** | Concluído | Processamento seguro via POST. O ID do cliente é capturado obrigatoriamente de `$_SESSION['usuario_id']`, calculando o valor total no servidor. |
| **Histórico e Cancelamento** | Concluído | Tela `meus_agendamentos` lista exclusivamente as solicitações do usuário logado. Cancelamento é feito estritamente via POST com conferência de dono. |
| **CRUD do Administrador** | Concluído | Listagem geral, tela unificada de cadastro e edição (com horários e upload de foto), e exclusão via POST com trava para serviços com agendamentos vinculados. |
| **Padrão POST-Redirect-GET** | Concluído | Todas as ações de alteração redirecionam via `Location` passando mensagens por `$_SESSION['sucesso']` e `$_SESSION['erro']`. |
| **Conferência das Camadas** | Concluído | Nenhuma ocorrência de SQL em controllers, nenhuma tag HTML em models/controllers, nenhum echo/header em models e nenhum SQL em views. |

### 2.2 O que mudou e por quê
1. **Confirmação de Agendamento:** Na proposta preliminar havia sido cogitada uma tela separada `resultado.php`. Na arquitetura MVC, adotou-se o padrão mais seguro de **POST-Redirect-GET**, redirecionando o cliente diretamente para a tela `meus_agendamentos` acompanhado de alerta flash de sucesso. Isso evita reenvio acidental de formulário ao atualizar a página e permite que o cliente já confira sua reserva no histórico.
2. **Cadastro e Edição de Horários:** Para facilitar a usabilidade do administrador, os horários de atendimento de cada serviço são informados em um campo de texto no formulário separados por vírgula (ex.: `09:00, 10:00, 11:00`). O controlador valida o formato e o modelo realiza a inserção e sincronização relacional na tabela `horarios` dentro de uma transação com commit/rollback.

### 2.3 O que ficou faltando
Nenhum item do escopo obrigatório ou planejado ficou pendente. Todas as 5 telas esboçadas, as tabelas relacionais, regras de segurança e fluxos de CRUD foram implementados integralmente.

### 2.4 Extra realizado
* **URLs Amigáveis (`mod_rewrite`):** Foi implementado e testado o arquivo `.htaccess`, permitindo o acesso por rotas limpas como `/catalogo`, `/login`, `/meus-agendamentos`, `/admin/servicos`, etc. Caso o servidor não possua o módulo ativo ou o arquivo seja removido, o sistema continua funcionando perfeitamente via `index.php?acao=...`.

---

## 3. O Modelo MVC do Sistema

### 3.1 Estrutura de Pastas

```
TrabalhoWeb/
├── index.php                      # Ponto único de entrada (autoload, sessão, conexão e rotas)
├── .htaccess                      # Módulo de reescrita para URLs amigáveis
├── config/
│   └── conexao.php                # Instância da conexão PDO
├── database/
│   └── banco.sql                  # Script DDL/DML com tabelas e dados iniciais
├── css/
│   └── style.css                  # Folha de estilos personalizada
├── assets/                        # Imagens dos serviços e logotipo
├── model/
│   ├── Model.php                  # Classe abstrata base com $pdo protected
│   ├── UsuarioModel.php           # Consultas e cadastros de usuários
│   ├── ServicoModel.php           # Consultas, transações e CRUD de serviços/horários
│   └── AgendamentoModel.php       # Consultas por usuário, inserção e cancelamento
├── controller/
│   ├── Controller.php             # Classe abstrata base (render, redirecionar, autorização)
│   ├── UsuarioController.php      # Login, cadastro de usuário e logout
│   ├── ServicoController.php      # Catálogo, detalhes, cookie e operações do admin
│   └── AgendarController.php      # Criação, listagem e cancelamento de agendamentos
└── view/
    ├── header.php                 # Cabeçalho com navbar dinâmica
    ├── footer.php                 # Rodapé
    ├── catalogo_view.php          # Catálogo de serviços com filtros
    ├── detalhes_view.php          # Detalhes e formulário de agendamento
    ├── login_view.php             # Formulário de login
    ├── cadastro_view.php          # Formulário de cadastro de cliente
    ├── meus_agendamentos_view.php # Histórico do cliente com opção de cancelar
    ├── admin_servicos_view.php    # Painel com tabela de serviços do admin
    ├── admin_servico_form_view.php# Formulário de cadastro e edição de serviços
    └── 404_view.php               # Tela de erro para rotas inexistentes
```

---

### 3.2 Diagrama das Classes

#### Diagrama de Classes — Models
```
+-------------------------------------------------------------+
|                     Model (Abstrata)                        |
+-------------------------------------------------------------+
| # pdo : PDO                                                 |
+-------------------------------------------------------------+
| + __construct(pdo : PDO)                                    |
+-------------------------------------------------------------+
                               ^
                               |
         +---------------------+---------------------+
         |                                           |
+----------------------------------+   +---------------------------------------+
|          UsuarioModel            |   |              ServicoModel             |
+----------------------------------+   +---------------------------------------+
| + buscarPorEmail(email) : array  |   | + listarAtivos(tipo) : array          |
| + cadastrar(nome, email,         |   | + listarTodos() : array               |
|             senhaHash) : bool    |   | + buscarPorId(id) : array             |
+----------------------------------+   | + buscarHorarios(servico_id) : array  |
                                       | + temAgendamentos(servico_id) : bool  |
                                       | + criar(nome, tipo, valor, descricao, |
                                       |         imagem, ativo, horarios): bool|
                                       | + atualizar(id, nome, tipo, valor,    |
                                       |             descricao, imagem, ativo, |
                                       |             horarios) : bool          |
                                       | + excluir(id) : bool                  |
                                       +---------------------------------------+
                                                         ^
                                                         |
                                       +---------------------------------------+
                                       |           AgendamentoModel            |
                                       +---------------------------------------+
                                       | + criar(usuario_id, servico_id, data, |
                                       |         horario, pessoas, total): bool|
                                       | + listarPorUsuario(usuario_id): array |
                                       | + cancelar(id, usuario_id) : bool     |
                                       | + buscarPorIdEDono(id, usuario_id)    |
                                       +---------------------------------------+
```

#### Diagrama de Classes — Controllers
```
+-------------------------------------------------------------+
|                   Controller (Abstrata)                     |
+-------------------------------------------------------------+
| + render(view : string, dados : array) : void               |
| + redirecionar(rota : string) : void                        |
| + exigirLogin() : void                                      |
| + exigirPerfil(perfilNecessario : string) : void            |
+-------------------------------------------------------------+
                               ^
                               |
         +---------------------+---------------------+
         |                                           |
+----------------------------------+   +---------------------------------------+
|        UsuarioController         |   |           ServicoController           |
+----------------------------------+   +---------------------------------------+
| - usuarioModel : UsuarioModel    |   | - servicoModel : ServicoModel         |
+----------------------------------+   +---------------------------------------+
| + __construct(pdo)               |   | + __construct(pdo)                    |
| + login() : void                 |   | + catalogo() : void                   |
| + cadastro() : void              |   | + detalhes() : void                   |
| + logout() : void                |   | + admin_servicos() : void             |
+----------------------------------+   | + admin_servico_form() : void         |
                                       | + admin_servico_salvar() : void       |
                                       | + admin_servico_excluir() : void      |
                                       | + naoEncontrado() : void              |
                                       +---------------------------------------+
                                                         ^
                                                         |
                                       +---------------------------------------+
                                       |           AgendarController           |
                                       +---------------------------------------+
                                       | - agendamentoModel : AgendamentoModel |
                                       | - servicoModel : ServicoModel         |
                                       +---------------------------------------+
                                       | + __construct(pdo)                    |
                                       | + agendar() : void                    |
                                       | + meus_agendamentos() : void          |
                                       | + cancelar_agendamento() : void       |
                                       +---------------------------------------+
```

---

### 3.3 Tabela de Rotas

| Ação (`acao`) | Controller | Método | Model Utilizado | View / Redirecionamento | Método HTTP | Permissão |
|---|---|---|---|---|---|---|
| `catalogo` (padrão) | `ServicoController` | `catalogo()` | `ServicoModel` | `catalogo_view.php` | GET | Público |
| `detalhes` | `ServicoController` | `detalhes()` | `ServicoModel` | `detalhes_view.php` | GET | Público |
| `login` | `UsuarioController` | `login()` | `UsuarioModel` | `login_view.php` (GET) / Redireciona (POST) | GET / POST | Visitante |
| `cadastro` | `UsuarioController` | `cadastro()` | `UsuarioModel` | `cadastro_view.php` (GET) / Redireciona (POST) | GET / POST | Visitante |
| `logout` | `UsuarioController` | `logout()` | — | Redireciona `index.php?acao=login` | GET | Logado |
| `agendar` | `AgendarController` | `agendar()` | `AgendamentoModel`, `ServicoModel` | Redireciona `meus_agendamentos` | POST | Cliente |
| `meus_agendamentos` | `AgendarController` | `meus_agendamentos()` | `AgendamentoModel` | `meus_agendamentos_view.php` | GET | Logado |
| `cancelar_agendamento`| `AgendarController` | `cancelar_agendamento()` | `AgendamentoModel` | Redireciona `meus_agendamentos` | POST | Cliente |
| `admin_servicos` | `ServicoController` | `admin_servicos()` | `ServicoModel` | `admin_servicos_view.php` | GET | Administrador |
| `admin_servico_form` | `ServicoController` | `admin_servico_form()` | `ServicoModel` | `admin_servico_form_view.php` | GET | Administrador |
| `admin_servico_salvar`| `ServicoController` | `admin_servico_salvar()`| `ServicoModel` | Redireciona `admin_servicos` | POST | Administrador |
| `admin_servico_excluir`| `ServicoController`| `admin_servico_excluir()`| `ServicoModel` | Redireciona `admin_servicos` | POST | Administrador |
| *(rota inexistente)* | `ServicoController` | `naoEncontrado()` | — | `404_view.php` (HTTP 404) | GET / POST | Público |

---

### 3.4 O Caminho de um Pedido (Passo a Passo)

Para exemplificar o fluxo completo de uma requisição passando por cada camada, detalha-se o caminho da ação **"Cadastrar um novo serviço pelo Administrador"**:

1. **Clique no Navegador:**
   O administrador está em `index.php?acao=admin_servico_form`, preenche o formulário com Nome, Tipo, Valor, Descrição, Horários, anexa uma foto e clica no botão **Cadastrar**.
2. **Disparo da Requisição HTTP:**
   O navegador envia uma requisição `POST` para `index.php?acao=admin_servico_salvar`, transportando os dados em `multipart/form-data`.
3. **Ponto de Entrada (`index.php`):**
   * Inicia a sessão com `session_start()`.
   * Registra o `spl_autoload_register`, que carrega dinamicamente as classes de `controller/` e `model/`.
   * Inclui `config/conexao.php` e obtém o objeto `$pdo`.
   * Avalia a variável `$acao` (`'admin_servico_salvar'`) no `switch`.
   * Instancia `new ServicoController($pdo)` e invoca `$controller->admin_servico_salvar()`.
4. **Camada Controller (`ServicoController`):**
   * Chama `$this->exigirPerfil('admin')`, que verifica na sessão se o usuário está logado e se seu perfil é `admin`. Se não for, barra a execução e redireciona.
   * Confere se o método HTTP é `POST`.
   * Filtra e higieniza os campos enviados: `nome`, `tipo`, `valor`, `descricao` e `horarios`.
   * Realiza a validação das regras de negócio (campos obrigatórios, valor positivo, tipo permitido).
   * Processa o upload da imagem, validando extensão e movendo para a pasta `assets/`.
   * Divide e sanitiza a string de horários em um array de strings no formato `HH:MM`.
   * Chama o método do Model: `$this->servicoModel->criar(...)`.
5. **Camada Model (`ServicoModel`):**
   * Inicia uma transação com `$this->pdo->beginTransaction()`.
   * Prepara o SQL de inserção na tabela `servicos` usando prepared statement com placeholders nomeados (`:nome`, `:tipo`, `:valor`, `:descricao`, `:imagem`, `:ativo`).
   * Executa a query com `$stmt->execute(...)`.
   * Recupera o ID gerado via `$this->pdo->lastInsertId()`.
   * Itera sobre a lista de horários informados e insere cada um na tabela relacional `horarios` com prepared statement.
   * Confirma a transação com `$this->pdo->commit()`. Em caso de qualquer falha, executa `$this->pdo->rollBack()`.
   * Retorna `true` ao controlador.
6. **Retorno e Redirecionamento (POST-Redirect-GET):**
   * O controlador grava a mensagem de feedback na sessão: `$_SESSION['sucesso'] = "Serviço cadastrado com sucesso!"`.
   * Invoca `$this->redirecionar('index.php?acao=admin_servicos')`.
   * O navegador recebe o cabeçalho `Location` e faz uma nova requisição `GET` para a listagem.
7. **Exibição da View (`admin_servicos_view.php`):**
   * `index.php` roteia para `admin_servicos()`, que busca os serviços via `ServicoModel::listarTodos()`.
   * O controller invoca `$this->render('admin_servicos_view', ...)`.
   * A view monta a tabela HTML escapando todo e qualquer dado com `htmlspecialchars()`, exibe o alerta verde com a mensagem de sucesso e remove a mensagem da sessão com `unset($_SESSION['sucesso'])`.

---

## 4. Atribuições de Cada Integrante

| Integrante | Atribuições e Arquivos sob sua Responsabilidade |
|---|---|
| **Luan Henrique Neves Miranda** | * Criação do banco de dados relacional (`database/banco.sql`), tabelas `usuarios`, `servicos`, `horarios` e `agendamentos`.<br>* Implementação da classe base abstrata `model/Model.php` e do `model/AgendamentoModel.php`.<br>* Implementação da classe base abstrata `controller/Controller.php` e do `controller/AgendarController.php`.<br>* Criação das views `view/detalhes_view.php` e `view/meus_agendamentos_view.php`.<br>* Fluxo completo de agendamento e cancelamento seguro pelo cliente. |
| **Miguel Silva Rodrigues** | * Implementação da autenticação e criptografia em `model/UsuarioModel.php` e `controller/UsuarioController.php`.<br>* Desenvolvimento das telas `view/login_view.php` e `view/cadastro_view.php`.<br>* Criação do layout compartilhado: `view/header.php`, `view/footer.php` e folha de estilos `css/style.css`.<br>* Configuração do ponto único de entrada `index.php`, sessão PHP e mecanismo de autoload sem require manual de classes. |
| **Rafael Borges Silva** | * Implementação do CRUD completo do administrador no `model/ServicoModel.php` (operações com transações PDO) e no `controller/ServicoController.php`.<br>* Desenvolvimento das views administrativas: `view/admin_servicos_view.php` e `view/admin_servico_form_view.php`.<br>* Implementação do filtro de categorias no catálogo e persistência do cookie `ultimo_tipo_servico` (validade de 30 dias).<br>* Configuração das URLs amigáveis com `.htaccess` (`mod_rewrite`) e testes rigorosos de conferência de camadas. |

---

## 5. Registro do Uso de IA

| Ferramenta / Modelo | Pedido ou Tarefa | O que foi aproveitado | Como o grupo verificou ou corrigiu a resposta |
|---|---|---|---|
| **Claude IA** | Simulação visual dos esboços das 5 telas do sistema no 3º bimestre. | A estrutura visual e distribuição dos campos nas telas. | Ajustamos as cores para a paleta da barbearia (azul marinho e vermelho) e integramos com o Bootstrap 5. |
| **ChatGPT** | Apoio na estruturação das fichas de operação do CRUD (CREATE, READ, UPDATE, DELETE). | Esboço de descrições dos métodos HTTP, mensagens de feedback e tratamento de erros. | Conferimos com os requisitos do HackMD e corrigimos os nomes das colunas e queries SQL. |
| **Antigravity (Google DeepMind)** | Revisão das camadas MVC, conferência estrita contra palavras proibidas (Ctrl+F) e estruturação do `.htaccess` para URLs amigáveis. | Validação das classes abstratas base, verificação automatizada de camadas e documentação do roteiro de apresentação. | O grupo testou localmente no XAMPP com PHP 8.2 e rodou script automatizado de auditoria para certificar que nenhum controller possui SQL e nenhuma view possui PDO. |

---

## 6. Referências Consultadas

1. **PHP Documentation.** Prepared Statements and PDO.  
   `https://www.php.net/manual/pt_BR/book.pdo.php`
2. **PHP Documentation.** Password Hashing (`password_hash`, `password_verify`).  
   `https://www.php.net/manual/pt_BR/function.password-hash.php`
3. **Curso de PHP #40 - Sistema de Login com sessões.** YouTube.  
   `https://www.youtube.com/watch?v=kSaUIuzLK9M`
4. **Cadastro e Login em PHP & MYSQL - #33 Logout e Níveis de Acesso.** YouTube.  
   `https://www.youtube.com/watch?v=saWpiWB8vRQ`
5. **Apache Module mod_rewrite.** Apache HTTP Server Documentation.  
   `https://httpd.apache.org/docs/current/mod/mod_rewrite.html`
