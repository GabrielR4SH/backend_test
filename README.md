# API de Redirecionamento - Documentação dos Endpoints

## 📋 Índice
- [Visão Geral](#visão-geral)
- [Endpoints da API](#endpoints-da-api)
- [Redirecionamento](#redirecionamento)
- [Exemplos de Uso](#exemplos-de-uso)

## 🚀 Visão Geral

Esta API permite gerenciar redirecionamentos de URL com código único, rastreamento de acessos e estatísticas detalhadas.

**URL Base:** `http://localhost:8000/api`

## 📊 Endpoints da API

### 1. 📄 Listar Todos os Redirecionamentos
**GET** `/api/redirects`

Retorna todos os redirecionamentos cadastrados no sistema.

**Response:**
```json
[
    {
        "code": "X1m5W6W",
        "destination_url": "https://exemplo.com",
        "is_active": true,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
]
````

### 2. ➕ Criar Novo Redirecionamento
**POST** `/api/redirects`

Cria um novo redirecionamento com código único gerado automaticamente.

```Body
{
    "destination_url": "https://exemplo.com",
    "is_active": true
}
````

``` Response (201 Created):
{
    "id": 1,
    "code": "X1m5W6W",
    "destination_url": "https://exemplo.com",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
}
````

### 3. 🔍 Buscar Redirecionamento por Código
**GET** `/api/redirects/{code}`

Retorna os detalhes de um redirecionamento específico. <br>
Exemplo: GET `/api/redirects/X1m5W6W`

``` Response:
{
    "id": 1,
    "code": "X1m5W6W",
    "destination_url": "https://exemplo.com",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
}
````

### 4. ✏️ Atualizar Redirecionamento
**PUT** `/api/redirects/{code}`

Atualiza os dados de um redirecionamento existente. <br>
Exemplo: PUT `/api/redirects/X1m5W6W`

``` Body:
{
    "destination_url": "https://novo-exemplo.com",
    "is_active": false
}
````

``` Response:
{
    "id": 1,
    "code": "X1m5W6W",
    "destination_url": "https://novo-exemplo.com",
    "is_active": false,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
}
````

### 5. 🗑️ Excluir Redirecionamento (Soft Delete)
**DELETE ** `/api/redirects/{code}`

Remove um redirecionamento (exclusão lógica). <br>
Exemplo: DELETE  `/api/redirects/X1m5W6W`

### 6. 📈 Estatísticas de Acesso
**GET** `/api/redirects/{code}/stats`

Retorna estatísticas detalhadas dos acessos a um redirecionamento. <br>
Exemplo: GET `/api/redirects/X1m5W6W/stats`

``` Response:
{
    "code": "X1m5W6W",
    "total_accesses": 1,
    "last_access": "2025-09-26 02:38:40",
    "top_referers": [
        {
            "referer": "http://example.com",
            "count": 1
        }
    ]
}
````

### 🔗 Redirecionamento
**GET** `/r/{code}`

Executa o redirecionamento para a URL destino, registrando o acesso e mesclando parâmetros de query. <br>
Exemplo: GET `/r/X1m5W6W?utm_source=email`

<h3>Comportamento:</h3>
<ul>
    <li>Redireciona para a URL destino configurada</li>
    <li>Preserva e mescla parâmetros de query</li>
    <li>Registra estatísticas de acesso (IP, user-agent, referer, etc.)</li>
    <li>Retorna 404 se o redirecionamento não existir ou estiver inativo</li>
</ul>

