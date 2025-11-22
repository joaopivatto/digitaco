# 🎮 Digitaço - Jogo de Digitação

> **Projeto desenvolvido para a disciplina de Desenvolvimento Web 1**  
> **UFPR - 2º Período**  
> **Professor: Alexander Kutzke**
>
> **Alunos: João Guilherme Pivatto, João Victor Dourado Neiva, Matheus Gabriel Rodrigues Alves, Samuel Natel**

---

## 📚 Sumário

- [📋 Sobre o Projeto](#-sobre-o-projeto)
- [🚀 Funcionalidades](#-funcionalidades)
- [📡 API Documentation](#-api-documentation)
  - [👥 Users](#-users)
    - [📝 POST /users/sign-up](#-post-userssign-up)
    - [🔑 POST /users/sign-in](#-post-userssign-in)
    - [🔒 PATCH /users/change-password](#-patch-userschange-password)
    - [🤝 POST /users/league/leagueid](#-post-usersleagueleagueid)
    - [❌ DELETE /users/league/leagueid](#-delete-usersleagueleagueid)
  - [🏆 Leagues](#-leagues)
    - [➕ POST /api/leagues/createphp](#-post-apileaguescreatephp)
    - [📝 GET /api/leagues/find-allphp?name{name}](#-get-apileaguesfind-allphpnamename)
    - [🔍 GET /api/leagues/find-by-idphp?id{id}](#-get-apileaguesfind-by-idphpidid)
    - [👨‍💼 GET /api/leagues/creatorphp](#-get-apileaguescreatorphp)
    - [👤 GET /api/leagues/includedphp](#-get-apileaguesincludedphp)
    - [🗑️ DELETE /api/leagues/deletephp?id{id}](#-delete-apileaguesdeletephpidid)
    - [🏅 GET /api/leagues/pointsphp?id{id}](#-get-apileaguespointsphpidid)
    - [➕ POST /api/leagues/insert-language.php](#-post-apileaguesinsert-languagephp)
    - [🗑️ DELETE /api/leagues/delete-language.php?leagueId=17&language=zh](#️-delete-apileaguesdelete-languagephpleagueid17languagezh)
    - [POST /api/leagues/insert-language.php]
  - [🎯 Matches](#-matches)
    - [🎮 POST /matches](#-post-matches)
    - [📋 GET /matches](#-get-matches)

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
```json
REQUEST
{
    "name": "name",
    "email": "user@email.com",
    "password": "!Password123"
}
```

```json
RESPONSE 201 (Created)
{
    "message": "Usuário criado com sucesso!"
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
```json
REQUEST
{
    "email": "user@email.com",
    "password": "!Password123"
}
```
```json
RESPONSE 200 (OK)
{
    "message": "Login realizado com sucesso!",
    "name": "pivatto",
    "email": "pivatto@email.com"
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
```json
REQUEST
{
    "email": "user@email.com",
    "password": "!Password123",
    "confirmPassword": "!Password123"
}
```
```json
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
```json
RESPONSE 200 (OK)
{
    "message": "Bem-vindo a liga: {leagueName}!"
}

RESPONSE 409 (Conflict)
{
    "message": "Usuário já está na liga!"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### ❌ DELETE /users/league/{leagueId}
```json
RESPONSE 200 (OK)
{
    "message": "Usuário removido da liga: {leagueName}!"
}

RESPONSE 409 (Conflict)
{
    "message": "Usuário não está na liga!"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

### 🏆 Leagues

**Sempre que tivermos 'id' neste setor, faz referência ao id de leagues.**

#### ➕ POST /api/leagues/create.php
```json
REQUEST
{
    "name": "League Name",
    "password": "!Password123",
    "languages": [
        "en",
        "pt-br",
        "es"
    ]
} 
```

```json
// Salvar Ligas

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

#### 📝 GET /api/leagues/find-all.php?name={name}
```json
// Retorna todas as ligas
// Name é opcional (usado para filtro)
// O campo included refere-se às ligas que o user faz parte

RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!",
    "data": [
        {
            "id": 3,
            "name": "League One",
            "members": 1,
            "included": false,
            "languages": [
                "en",
                "pt-br"
            ]
        },
        {
            "id": 4,
            "name": "League Two",
            "members": 1,
            "included": false,
            "languages": [
                "en",
                "pt-br"
            ]
        },
        {
            "id": 5,
            "name": "League Three",
            "members": 1,
            "included": false,
            "languages": [
                "en",
                "pt-br"
            ]
        }
    ]
} 
```

#### 🔍 GET /api/leagues/find-by-id.php?id={id}
```json
// Retorna Liga pelo id

RESPONSE 200 (OK)
{
    "message": "Liga encontrada!",
    "league": {
        "id": 8,
        "name": "League Five",
        "members": 1,
        "languages": [
            "en",
            "pt-br",
            "es"
        ]
    }
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### 👨‍💼 GET /api/leagues/creator.php
```json
// Ligas que Criou

RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!",
    "data": [
        {
            "id": 3,
            "name": "League One",
            "members": 1,
            "languages": [
                "en",
                "pt-br",
                "es"
            ]
        },
        {
            "id": 4,
            "name": "League Two",
            "members": 1,
            "languages": [
                "en",
                "pt-br",
                "es"
            ]
        }
    ]
}
```

#### 👤 GET /api/leagues/included.php
```json
// Ligas que o user participa

RESPONSE 200 (OK)
{
    "message": "Ligas encontradas!",
    "data": [
        {
            "id": 5,
            "name": "League Five",
            "members": 1,
            "points": 525,
            "languages": [
                "en",
                "pt-br",
                "es"
            ]
        }
    ]
}
```

#### 🗑️ DELETE /api/leagues/delete.php?id={id}
```json
// Excluir liga

RESPONSE 200 (OK)
{
    "message": "Liga excluída com sucesso!"
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### 🏅 GET /api/leagues/points.php?id={id}
```json
// Buscar tabela de pontuação da liga

RESPONSE 200 (OK)
{
    "message": "Pontuação Geral da Liga!",
    "data": [
        {
            "name": "pivatto",
            "points": 260,
            "matches": 8,
            "average": 880
        },
        {
            "name": "samuel",
            "points": 240,
            "matches": 5,
            "average": 670
        }
    ]
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```

#### 📊 GET /api/leagues/points-weekly.php?id={id}
```json
// Buscar tabela de pontuação semanal da liga

RESPONSE 200 (OK)
{
    "message": "Pontuação Semanal da Liga!",
    "data": [
        {
            "name": "pivatto",
            "points": 260,
            "matches": 8,
            "average": 880
        },
        {
            "name": "samuel",
            "points": 1000,
            "matches": 2,
            "average": 500
        }
    ]
}

RESPONSE 404 (NOT FOUND)
{
    "message": "Liga não encontrada!"
}
```
#### ➕ POST /api/leagues/insert-language.php
```json
REQUEST
{
    "leagueId": 1,
    "language": "zh"
} 
```

```json
// Salvar Idiomas

RESPONSE 201 (Created)
{
    "message": "Idioma adicionado com sucesso!"
}

RESPONSE 409 (Conflict)
{
    "message": "Idioma já adicionado!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```
#### 🗑️ DELETE /api/leagues/delete-language.php?leagueId=17&language=zh

```json
// Remover Idiomas

RESPONSE 200 (OK)
{
    "message": "Idioma removido da liga!"
}

RESPONSE 409 (Conflict)
{
    "message": "Idioma não adicionado!"
}

RESPONSE 422 (Unprocessable Entity)
{
    "message": "Campos Inválidos!"
}
```

### 🎯 Matches

#### 🎮 POST /api/matches/create.php
```json
// Criar Partida

REQUEST
{
    "points": 1130,
    "words": 14,
    "leagueId": 2
}
```

```json
RESPONSE 201 (Created)
{
    "message": "Partida finalizada!"
}
```


#### 📋 GET /api/matches/user-history.php
```json
// Histórico do Usuário

RESPONSE 200 (OK)
{
    "message": "Histórico de Partidas!",
    "userPerformance": {
        "totalMatches": 9,
        "totalWords": 1219,
        "totalPoints": 8170,
        "bestScore": 1130,
        "matches": [
            {
                "points": 1130,
                "words": 14,
                "date": "04/11/2025 15:50"
            }

        ]
    }
}
```

#### 📋 GET /api/matches/global-rating.php
```json
// Ranking Geral

RESPONSE 200 (OK)
{
    "message": "Ranking Geral!",
    "data": [
        {
            "name": "pivatto",
            "points": 8170,
            "matches": 9,
            "average": 907.78
        },
        {
            "name": "samuel",
            "points": 240,
            "matches": 2,
            "average": 120
        }
    ]
}
```

#### 📋 GET /api/matches/global-rating-weekly.php
```json
// Ranking Semanal

RESPONSE 200 (OK)
{
    "message": "Ranking Geral Semanal!",
    "data": [
        {
            "name": "pivatto",
            "points": 8170,
            "matches": 9,
            "average": 907.78
        },
        {
            "name": "samuel",
            "points": 160,
            "matches": 1,
            "average": 160
        }
    ]
}
```