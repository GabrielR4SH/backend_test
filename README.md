# API de Redirecionamento - Documentação

## 📋 Índice
- [Visão Geral](#visão-geral)
- [Endpoints da API](#endpoints-da-api)
- [Redirecionamento](#redirecionamento)
- [Exemplos de Uso](#exemplos-de-uso)

## 🚀 Visão Geral
Esta API permite gerenciar redirecionamentos de URLs com códigos únicos, rastreamento de acessos e estatísticas detalhadas.  
**URL Base:** `http://localhost:8000/api`

## 📊 Endpoints da API

### 1. 📄 Listar Todos os Redirecionamentos
**Método:** `GET /api/redirects`  
**Descrição:** Retorna todos os redirecionamentos cadastrados no sistema.  
**Resposta (200 OK):**
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
```

### 2. ➕ Criar Novo Redirecionamento
**Método:** `POST /api/redirects`  
**Descrição:** Cria um novo redirecionamento com um código único gerado automaticamente.  
**Corpo da Requisição:**
```json
{
  "destination_url": "https://exemplo.com",
  "is_active": true
}
```
**Resposta (201 Created):**
```json
{
  "id": 1,
  "code": "X1m5W6W",
  "destination_url": "https://exemplo.com",
  "is_active": true,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

### 3. 🔍 Buscar Redirecionamento por Código
**Método:** `GET /api/redirects/{code}`  
**Descrição:** Retorna os detalhes de um redirecionamento específico.  
**Exemplo:** `GET /api/redirects/X1m5W6W`  
**Resposta (200 OK):**
```json
{
  "id": 1,
  "code": "X1m5W6W",
  "destination_url": "https://exemplo.com",
  "is_active": true,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

### 4. ✏️ Atualizar Redirecionamento
**Método:** `PUT /api/redirects/{code}`  
**Descrição:** Atualiza os dados de um redirecionamento existente.  
**Exemplo:** `PUT /api/redirects/X1m5W6W`  
**Corpo da Requisição:**
```json
{
  "destination_url": "https://novo-exemplo.com",
  "is_active": false
}
```
**Resposta (200 OK):**
```json
{
  "id": 1,
  "code": "X1m5W6W",
  "destination_url": "https://novo-exemplo.com",
  "is_active": false,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T11:00:00.000000Z"
}
```

### 5. 🗑️ Excluir Redirecionamento
**Método:** `DELETE /api/redirects/{code}`  
**Descrição:** Remove um redirecionamento (exclusão lógica).  
**Exemplo:** `DELETE /api/redirects/X1m5W6W`  
**Resposta:** `204 No Content`

### 6. 📈 Estatísticas de Acesso
**Método:** `GET /api/redirects/{code}/stats`  
**Descrição:** Retorna estatísticas detalhadas dos acessos a um redirecionamento.  
**Exemplo:** `GET /api/redirects/X1m5W6W/stats`  
**Resposta (200 OK):**
```json
{
  "code": "X1m5W6W",
  "total_accesses": 1,
  "last_access": "2025-09-26T02:38:40.000000Z",
  "top_referers": [
    {
      "referer": "http://example.com",
      "count": 1
    }
  ]
}
```

## 🔗 Redirecionamento
**Método:** `GET /r/{code}`  
**Descrição:** Executa o redirecionamento para a URL de destino, registrando o acesso e mesclando parâmetros de query.  
**Exemplo:** `GET /r/X1m5W6W?utm_source=email`  
**Comportamento:**
- Redireciona para a URL de destino configurada.
- Preserva e mescla parâmetros de query.
- Registra estatísticas de acesso (IP, user-agent, referer, etc.).
- Retorna `404 Not Found` se o redirecionamento não existir ou estiver inativo.

