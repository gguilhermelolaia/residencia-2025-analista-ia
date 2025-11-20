
<!-- end list -->

````markdown
# 💰 Analista Financeiro IA - Residência 2025

Sistema inteligente de recomendação de investimentos que utiliza Agentes de IA para coletar dados financeiros, buscar notícias de mercado e gerar relatórios de compra/venda com curadoria humana.

Projeto composto por: **Laravel (Site/API)** + **Python (Agentes IA)** + **Docker**.

---

## 🚀 Como rodar o projeto (Passo a Passo)

Siga esta ordem exata para não ter erros.

### 1. Pré-requisitos
Tenha instalado no seu computador:
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Deve estar aberto e rodando).
* [Git](https://git-scm.com/).

### 2. Clonar e Entrar na Pasta
Abra seu terminal e rode:

```bash
git clone https://github.com/gguilhermelolaia/residencia-2025-analista-ia.git
cd residencia-2025-analista-ia
````

### 3\. Configurar as Chaves (Importante\!)

Abra o arquivo `docker-compose.yml` na raiz e procure as linhas do `worker`.
Cole suas chaves reais dentro das aspas na seção environment:

```yaml
    environment:
      SERPER_API_KEY: "COLE_SUA_CHAVE_SERPER_AQUI"
      GEMINI_API_KEY: "COLE_SUA_CHAVE_GEMINI_AQUI"
```

### 4\. Subir o Projeto

No terminal, rode o comando que baixa e liga tudo (pode demorar uns minutos na primeira vez):

```bash
docker-compose up -d --build
```

*(Espere até aparecer "Started" para todos os containers).*

### 5\. Configurar o Banco de Dados

Precisamos criar as tabelas e o usuário Admin. Rode estes dois comandos em sequência:

**A. Criar as tabelas:**

```bash
docker-compose exec app php artisan migrate
```

**B. Criar o Usuário Admin:**

```bash
docker-compose exec app php artisan tinker
```

*(Vai abrir um terminal interativo `>`. Copie e cole o código abaixo e dê Enter):*

```php
\App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@email.com',
    'password' => bcrypt('12345678')
]);
exit
```

-----

## 🖥️ Como Usar o Sistema

### 🔐 Área Administrativa (Para pedir análises)

1.  Acesse: [http://localhost:8000/admin](https://www.google.com/search?q=http://localhost:8000/admin)
2.  **Login:** `admin@email.com`
3.  **Senha:** `12345678`
4.  No campo "Solicitar Nova Análise", digite uma ação (ex: `PETR4` ou `VALE3`) e clique no botão.
5.  **Aguarde uns 15 a 30 segundos**. A IA vai processar e o card vai mudar para "Rascunho".
6.  Revise o texto e clique em **✅ Aprovar**.

### 🌍 Área Pública (Para visitantes)

1.  Acesse: [http://localhost:8000](https://www.google.com/search?q=http://localhost:8000)
2.  Aqui aparecem apenas os relatórios que você aprovou.

-----

## 🛑 Como Parar

Para desligar tudo e liberar memória do computador:

```bash
docker-compose down
```

-----
