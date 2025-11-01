# 🎮 Digitaço - Jogo de Digitação

> **Projeto desenvolvido para a disciplina de Desenvolvimento Web 1**  
> **UFPR - 2º Período**  
> **Professor: Alexander Kutzke**
>
> **Alunos: João Guilherme Pivatto, João Victor Dourado Neiva, Matheus Gabriel Rodrigues Alves, Samuel Natel**

---

## 📋 Sobre o Projeto

O **Digitaço** é um jogo de digitação interativo que permite aos usuários competirem em ligas, acompanhar suas pontuações e melhorar suas habilidades de digitação de forma gamificada.

## 🚀 Funcionalidades

- 👤 **Sistema de Usuários**: Cadastro, login e gerenciamento de perfil
- 🏆 **Sistema de Ligas**: Criação e participação em ligas competitivas  
- 🎯 **Sistema de Pontuação**: Ranking geral e semanal
- 🎮 **Partidas**: Registro e histórico de jogos

---

## 📡 API Documentation

### 👥 Users

#### 📝 POST /users/sign-up
```
REQUEST
{
    "name": "name",
    "email": "user@email.com",
    "password": "!Password123"
}
```

```
RESPONSE 201 (Created)
{
    "message": "Usuário criado com sucesso!"
    "name": "name",
    "email": "user@email.com"
}

RESPONSE 409 (Conflict)
{
    "message": "Email já está em uso!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```

#### 🔑 POST /users/sign-in
```
REQUEST
{
    "email": "user@email.com",
    "password": "!Password123"
}
```
```
RESPONSE 200 (OK)
{
    "message": "Login realizado com sucesso!"
    "name": "name",
    "email": "user@email.com"
}

RESPONSE 401 (Unauthorized)
{
    "message": "Credenciais inválidas!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```

#### 🔒 PATCH /users/change-password
```
REQUEST
{
    "email": "user@email.com",
    "password": "!Password123",
    "confirmPassword": "!Password123"
}
```
```
RESPONSE 200 (OK)
{
    "message": "Senha alterada com sucesso!"
}

RESPONSE 400 (Bad Request)
{
    "message": "Senhas diferentes!"
}

RESPONSE 400 (Bad Request)
{
    "message": "Email não existe!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```

#### 🤝 POST /users/league/{leagueId}
```
RESPONSE 200 (OK)
{
    "message": "Bem-vindo a {leagueName}!"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### ❌ DELETE /users/league/{leagueId}
```
RESPONSE 200 (OK)
{
    "message": "Você saiu da liga: {leagueName}!"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

### 🏆 Leagues

#### ➕ POST /leagues
```
REQUEST
{
    "name": "League Name",
    "password": "!Password123",
} 
```

```
RESPONSE 201 (Created)
{
    "message": "Liga criada com sucesso!"
}

RESPONSE 409 (Conflict)
{
    "message": "Esta liga já existe!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```

#### 📝 GET /leagues?name="League"
- Todas as Ligas
```
RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!"
    [
        { "name": "League One" },
        { "name": "League Two" },
        { "name": "League Three" }
    ]
} 
```

#### 🔍 GET /leagues/{id}
```
RESPONSE 200 (OK)
{
    "message": "Liga encontrada!"
    "name": "League One"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### 👨‍💼 GET /leagues/creator
- Ligas que criou
```
RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!"
    [
        { "name": "League One" },
        { "name": "League Two" },
        { "name": "League Three" }
    ]
} 
```

#### 👤 GET /leagues/included
- Ligas que está incluso
```
RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!"
    [
        { "name": "League One" },
        { "name": "League Two" },
        { "name": "League Three" }
    ]
} 
```

#### 🗑️ DELETE /leagues/{id}
```
RESPONSE 200 (OK)
{
    "message": "Liga excluída com sucesso!"
} 

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### 🏅 GET /leagues/{id}/points
- Ordenar DESC
```
RESPONSE 200 (OK)
{
    "message": "Pontuação Geral da Liga!"
    [
        { 
            "user": {
                "name": "User One"
            },
            "points: 564  
        },
        {
            "user": {
                "name": "User Two"
            },
            "points: 365  
        },
    ]
}
```

#### 📊 GET /leagues/{id}/points-weekly
- Ordenar DESC
```
RESPONSE 200 (OK)
{
    "message": "Pontuação Semanal da Liga!"
    [
        { 
            "user": {
                "name": "User One"
            },
            "points: 564  
        },
        {
            "user": {
                "name": "User Two"
            },
            "points: 365  
        },
    ]
}
```

### 🎯 Matches

#### 🎮 POST /matches
```
REQUEST
{
    "points": 120,
    "leagueId": 1 ?? null,
}
```

```
RESPONSE
{
    "message": "Partida finalizada!"
    "points": 120
}
```


#### 📋 GET /matches
```
RESPONSE
{
    "message": "Partidas!"
    [
        {
            "points": 120,
            "playedAt": "20/12/2025"
        },
        {
            "points": 112,
            "playedAt": "21/12/2025"
        }
    ]
}
```