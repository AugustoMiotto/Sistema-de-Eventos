# 🚨 README OR DIE 🚨

Este documento contém os **comandos** para fazer o sistema funcionar perfeitamente na sua máquina. Siga os passos na ordem.

---

## CENÁRIO 1: É a minha primeira vez rodando o projeto (Setup Inicial)

Acabou de clonar o repositório pela primeira vez? Faça **exatamente** o passo a passo abaixo:

### 1. Baixar as Dependências do PHP (Backend)
No terminal, na pasta raiz do projeto, rode:
```bash
composer install
```

### 2. Baixar as Dependências do Node (Frontend)
Para baixar o Tailwind e o Alpine, rode:
```bash
npm install
```

### 3. Configurar o Arquivo de Ambiente (.env)
O Laravel precisa de um arquivo `.env` para saber as senhas e configurações do seu computador.
* Duplique o arquivo `.env.example` e renomeie a cópia para `.env`
* Rode o comando abaixo para gerar a chave de segurança única do seu projeto:
```bash
php artisan key:generate
```

### 4. Configurar o Banco de Dados
Abra o seu arquivo `.env` recém-criado e ajuste a conexão com o banco de dados. Exemplo (MariaDB/MySQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco  
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Criar as Tabelas no Banco
Com o banco configurado no `.env`, rode as migrations para criar toda a estrutura de tabelas:
```bash
php artisan migrate
```

### 6. Criar a Ponte das Imagens (Storage Link)
Para as fotos de perfil e as capas de evento aparecerem no navegador, rode este comando uma única vez:
```bash
php artisan storage:link
```

### 7. Ligar os servers! 
Você precisará de **dois terminais abertos** rodando ao mesmo tempo para trabalhar no projeto:

Terminal 1 (Inicia o servidor do PHP):
```bash
php artisan serve
```

Terminal 2 (Inicia o compilador de CSS/JS em tempo real):
```bash
npm run dev
```

Pronto! Acesse `http://localhost:8000` no seu navegador e comece a codar.

---

## CENÁRIO 2: Acabei de fazer um `git pull` (Atualização Rotineira)

Se você puxou o código novo do GitHub para a sua máquina, o sistema pode quebrar se os arquivos de dependência ou o banco mudaram. Siga este "ritual" rápido sempre que der um pull:

### 1. Puxar o código novo
```bash
git pull
```

### 2. Garantir que ninguém adicionou pacotes novos
Rode estes dois comandos para baixar qualquer biblioteca de PHP ou Node que possa ter sido incluída no projeto:
```bash
composer install
npm install
```

### 3. Rodar novas Migrations
Se alguém criou uma tabela nova ou alterou uma coluna, este comando vai atualizar o seu banco local sem apagar seus dados:
```bash
php artisan migrate
```

### 4. Limpar o Cache (A "Pílula Mágica")
Se o sistema estiver se comportando de forma estranha ou rotas novas não estiverem funcionando, limpe a memória do Laravel:
```bash
php artisan optimize:clear
```

### 5. Voltar ao Trabalho
Ligue os motores novamente:
```bash
php artisan serve
npm run dev
```

---

## Possíveis Complicações (Troubleshooting)

* **O CSS sumiu, a tela está branca com letras pretas ou a cor verde não aparece?**
  Você esqueceu de rodar o `npm run dev` no segundo terminal.
* **Erro 500 ou `No application encryption key has been specified`?**
  Você esqueceu o passo 3 do Setup Inicial (`php artisan key:generate`).
* **As fotos de perfil e capas de evento ficaram com ícone quebrado?**
  Você não tem o atalho da pasta pública. Rode `php artisan storage:link`.
* **Erro vermelho de SQL / `Base table or view not found`?**
  Você esqueceu de rodar as migrations após um git pull (`php artisan migrate`).
