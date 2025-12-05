# Relatório de Uso de Inteligência Artificial Generativa

Este documento registra todas as interações significativas com ferramentas de IA generativa (como Gemini, ChatGPT, Copilot, etc.) durante o desenvolvimento deste projeto. O objetivo é promover o uso ético e transparente da IA como ferramenta de apoio, e não como substituta para a compreensão dos conceitos fundamentais.

## Política de Uso
O uso de IA foi permitido para as seguintes finalidades:
- Geração de ideias e brainstorming de algoritmos.
- Explicação de conceitos complexos.
- Geração de código boilerplate (ex: estrutura de classes, leitura de arquivos).
- Sugestões de refatoração e otimização de código.
- Debugging e identificação de causas de erros.
- Geração de casos de teste.

É proibido submeter código gerado por IA sem compreendê-lo completamente e sem adaptá-lo ao projeto. Todo trecho de código influenciado pela IA deve ser referenciado neste log.

---

## Registro de Interações

*Copie e preencha o template abaixo para cada interação relevante.*

### Interação 1

- **Data:** 01/11/2025
- **Etapa do Projeto:** Criação do nome do Projeto
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Criar um nome criativo para o projeto

- **Prompt(s) Utilizado(s):**
  1. "Preciso de um nome para esse jogo, me indique nomes criativos e que tenham alguma piada"

- **Resumo da Resposta da IA:**
  ⚡️ Divertidos e com trocadilhos
    1. “Digita aí!” — simples, direto e soa como um desafio entre amigos.
    2. “Deu Tecla!” — trocadilho com “deu treta”.
    3. “Ctrl+Vencedor” — pra quem domina o teclado.
    4. “Digitaço” — como “pisaço” ou “chutaço”, mas de digitar.
    5. “Tecla Quente” — quem digita mais rápido está “quente no jogo”.
    6. “Backspace não!” — brincando com o erro e correção na digitação.
    7. “Os Dez Dedos da Fúria” — paródia de filmes de luta, perfeito pra um ranking competitivo.
    8. “Palavra Mortal” — estilo “Kombat”, mas com palavras.
    9. “Vai com os Dedos” — trocadilho com “vai com os deuses”.
    10. “404 Palavras Não Encontradas” — humor nerd total.

- **Análise e Aplicação:**
  A resposta da IA foi extremamente útil, nos fornecendo várias opções para o nome do projeto.

- **Referência no Código:** Não há qualquer referência no código, visto que é o nome do projeto.

---

### Interação 2

- **Data:** 02/11/2025
- **Etapa do Projeto:** Criação de Queries
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Montar a estrutura de consulta de banco de dados.
- **Prompt(s) Utilizado(s):** 
    1. quais são os parâmetros para ->bind_param
- **Resumo da Resposta da IA:** 
    - Exemplos práticos
        - Um parâmetro inteiro
            - $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            - $stmt->bind_param("i", $id);
        - Vários parâmetros de tipos diferentes
            - $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, idade) VALUES (?, ?, ?)");
            - $stmt->bind_param("ssi", $nome, $email, $idade);
- **Análise e Aplicação:** A resposta da IA foi extremamente útil, auxiliando na estruturação da segurança das queries.
- **Referência no Código:** A lógica inspirada por esta interação foi implementada nos arquivos DAO, responsáveis pelas consultas realizadas ao banco de dados.

---

### Interação 3

- **Data:** 04/11/2025
- **Etapa do Projeto:** Criação tabelas projeto
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Criar as tabelas no banco de dados
- **Prompt(s) Utilizado(s):** 
    1. Sabendo que o meu banco de dados ‘digitaco’ possui as tabelas users (id, name, email, password, created_at), leagues (id, name, password, creator_id, created_at), matches (id, points, league_id, user_id, played_at), league_languages (league_id, language), league_user (id, league_id, user_id).
- **Resumo da Resposta da IA:** A IA respondeu com os comandos para criação das tabelas, um exemplo é a tabela users: 
	```sql
    CREATE TABLE users (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(150) NOT NULL,
        email varchar(150) NOT NULL,
        password varchar(255) NOT NULL,
        created_at timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (id),
        UNIQUE KEY unique_email (email))
        ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ```
    
- **Análise e Aplicação:** A resposta da IA foi extremamente útil, nos fornecendo a estrutura correta dos comandos, porém, como ela não tinha o contexto do projeto, tivemos que realizar algumas alterações nos comandos retornados, para adequar a regra de negócio do projeto.
- **Referência no Código:** A lógica inspirada por esta interação foi no arquivo create_db_tables.php, arquivo utilizado para criação automática das tabelas no banco de dados do usuário que está testando a aplicação.

---

### Interação 4

- **Data:** 06/11/2025
- **Etapa do Projeto:** Implementação de Classes DAO
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Descobrir como pegar o id (gerado pelo banco de dados) no retorno da query.
- **Prompt(s) Utilizado(s):** 
    1. Como pegar o id da liga salva 
    ```php
    $conn = Database::connect(); 
    $sql = $conn->prepare("INSERT INTO leagues (name, password, creator_id) VALUES (?,?,?)"); 
    $hash = password_hash($password, PASSWORD_DEFAULT); 
    $sql->bind_param("ssi", $name, $hash, $creatorId); 
    $sql->execute(); $res = $sql->get_result(); 
    $sql = $conn->prepare("INSERT INTO league_user (league_id, user_id) VALUES (?,?)"); 
    $sql->bind_param("ii", $res['id'], $creatorId); 
    $sql->execute();
    ```
- **Resumo da Resposta da IA:** Falou que para pegar o ID da liga recém-criada, o correto é usar o $conn->insert_id, que retorna o último ID auto increment inserido nessa conexão.
- **Análise e Aplicação:** A resposta da IA foi extremamente útil, nos fornecendo a forma de buscar o id do objeto salvo.
- **Referência no Código:** A lógica inspirada por esta interação foi no arquivo LeaguesDAO.php, arquivo utilizado para conexões com a tabela leagues no banco de dados.

---

### Interação 5

- **Data:** 06/11/2025
- **Etapa do Projeto:** Implementação de Classes DAO
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Descobrir como pegar o id (gerado pelo banco de dados) no retorno da query.
- **Prompt(s) Utilizado(s):** 
    1. Com base na estrutura do bd, ajuste essa query para que retorne um campo que indique se o user está ou não na liga 
    ```php
    $sql = $conn->prepare("SELECT * FROM leagues WHERE name LIKE ?"); 
    $param = "%$name%"; $sql->bind_param("s", $param);
    ```
- **Resumo da Resposta da IA:** Retornou uma query que retornava em um atributo a informação de quantos membros tinham em cada liga (com base na tabela league_user).
- **Análise e Aplicação:** A resposta da IA foi extremamente útil, nos fornecendo a forma de retornar quantidade de membros por liga.
- **Referência no Código:** A lógica inspirada por esta interação foi no arquivo LeaguesDAO.php, arquivo utilizado para conexões com a tabela leagues no banco de dados.

--- 

### Interação 6
- **Data:** 08/11/2025
- **Etapa do Projeto:** Implementação de Classes DAO
- **Ferramenta de IA Utilizada:** Chat GPT
- **Objetivo da Consulta:** Descobrir como pegar o id (gerado pelo banco de dados) no retorno da query.
- **Prompt(s) Utilizado(s):** 
    1. Faça uma query que busque na tabela matches (points, words, league_id, user_id) total de partidas, total de palavras, pontuaçao total, melhor pontuação e a lista com todas as partidas
    2. Isso é mais performático que separar em duas queries diferentes? uma que retorne a lista e outra as informações principais
- **Resumo da Resposta da IA:** Após o primeiro prompt retornou uma query que utilizava SELECT JSON_OBJECT para retornar a lista, porém ao analisar, pensamos que separar em duas queries poderia ser mais performático e foi aí que fizemos o segundo prompt. O segundo prompt nos retornou que o MySQL trataria cada query de forma performática, nos fazendo optar por separar as queries. Além disso nos mostrou a diferença que faria dependendo da quantidade de registros.
- **Análise e Aplicação:** A resposta da IA foi extremamente útil, nos fornecendo a visão de performance e como deveríamos aplicar a query.
- **Referência no Código:** A lógica inspirada por esta interação foi no arquivo PointsDAO.php, arquivo utilizado para conexões com a tabela points no banco de dados.

---

### Interação 7
- **Data:** 08/11/2025
- **Etapa do Projeto:** Configurações Iniciais do projeto no FrontEnd
- **Ferramenta de IA Utilizada:** Chat GPT (Integrada na IDE TRAE)
- **Objetivo da Consulta:** Configurar o framework e as bibliotecas que costumo utilizar (angular com tailwind e primeng) e o proxy.
- **Prompt(s) Utilizado(s):** 
    1. Estou com dificuldades em fazer o tailwind funcionar no meu projeto, realizei os passos listados no site da ferramenta, porém após a instalação, as classes tailwind não estão sendo aplicadas.
- **Resumo da Resposta da IA:** A Inteligência artificial me ajudou a diagnosticar o problema na configuração, testando parte por parte, em que momento da instalação algo havia dado errado. Na parte da configuração do proxy foi curioso pois não foi um pedido meu, portanto não houve um prompt específico, na verdade, a própria IA antecipou que caso eu tentasse conectar o cliente com o host no mesmo endereço o navegador não iria permitir, sendo assim, recomendou a configuração de um proxy para ‘enganar’ o navegador.
- **Análise e Aplicação:** Graças ao contexto fornecido pela IDE, o diagnóstico foi sendo feito em tempo real, de forma que a IA obtinha as respostas dos comandos no terminal, o que foi excelente para agilizar esse processo. Já no proxy, tentei ignorar a recomendação e para a minha surpresa o problema realmente ocorreu, assim, tive que configurar um proxy como sugerido anteriormente pela IA.
- **Referência no Código:** Uma configuração que estava incorreta e foi corrigida está no arquivo postcss.config.mjs e a configuração do proxy está em proxy.conf.json, além do script que criei e adicionei ao package.json como ‘dev’ para executar com o mesmo.

--- 

### Interação 8
- **Data:** 27/11/2025
- **Etapa do Projeto:** Implementação de comportamento do jogo FrontEnd
- **Ferramenta de IA Utilizada:** Chat GPT (Integrada na IDE TRAE)
- **Objetivo da Consulta:** Descobrir se há alguma ferramenta preexistente do angular para lidar com essa situação específica. Até então, minha ideia era utilizar uma função recursiva, que suspeitei não ser uma boa opção
- **Prompt(s) Utilizado(s):** 
    1. Quero adicionar palavras ao array game.ts 21-21 periodicamente e quero que elas iniciam no topo de game.html 21-21 e caiam até o footer da página, qual a melhor forma de implementar esse comportamento?- 
- **Resumo da Resposta da IA:** A IA recomendou a utilização de setInterval para gerar as palavras periodicamente. Para o comportamento de cair na tela, foi sugerido a utilização do ngZone para gerenciar a detecção de mudanças do Angular e garantir performance. No fim, a IA acabou utilizando uma função recursiva para atualizar em px a posição vertical da palavra, mas com algumas ferramentas que eu não conhecia (requestAnimationFrame) para evitar incompatibilidades com o gerenciador de estado do angular.
- **Análise e Aplicação:** Esse momento foi crucial, pois eu tinha ideia de como implementar, mas as ferramentas sugeridas pela IA foram importantíssimas para garantir que possíveis bugs causados por inconsistências no gerenciamento de estado do angular.
- **Referência no Código:** A lógica do jogo foi implementada dentro do componente game, localizado na pasta pages do frontend.

--- 

### Interação 9
- **Data:** 29/11/2025
- **Etapa do Projeto:** Implementação de comportamento do jogo
- **Ferramenta de IA Utilizada:** Chat GPT (Integrada na IDE TRAE)
- **Objetivo da Consulta:** Entender porque, mesmo com a função stopGame() implementada, o comportamento de cair das palavras não parava também.
- **Prompt(s) Utilizado(s):** 
    1. Porque as palavras não estão parando de cair quando o último coração é perdido?
- **Resumo da Resposta da IA:** O que estava acontecendo: O loop de animação ( requestAnimationFrame ) estava sendo reagendado incondicionalmente logo após a chamada de updatePositions() . Mesmo quando stopGame() era chamado (cancelando o frame atual), o código continuava a execução e agendava o próximo frame, mantendo o jogo rodando.
- **Análise e Aplicação:** A IA auxiliou a identificar a falha no código e possibilitou a correção do bug.
- **Referência no Código:** A lógica do jogo foi implementada dentro do componente game, localizado na pasta pages do frontend.

--- 

### Interação 10
- **Data:** 30/11/2025
- **Etapa do Projeto:** Implementação de comportamento do jogo
- **Ferramenta de IA Utilizada:** Chat GPT (Integrada na IDE TRAE)
- **Objetivo da Consulta:** Minha ideia era recarregar a página para começar uma nova partida, o que me parecia uma ideia ruim, então perguntei para saber se a minha suspeita estava correta.
- **Prompt(s) Utilizado(s):** 
    1. Qual a melhor forma de começar um novo jogo? recarregar a página?
- **Resumo da Resposta da IA:** A sugestão foi de que a melhor forma de reiniciar o jogo seria resetar as variáveis de estado para seus valores iniciais, sem recarregar a página inteira. Isso mantém a experiência de aplicação de página única (SPA) e é mais eficiente.
- **Análise e Aplicação:** a IA auxiliou a tomar uma decisão sobre qual lógica implementar para reiniciar uma partida.
- **Referência no Código:** A lógica do jogo foi implementada dentro do componente game, localizado na pasta pages do frontend.

--- 

### Interação 11
- **Data:** 01/12/2025
- **Etapa do Projeto:** Testes do Jogo
- **Ferramenta de IA Utilizada:** Chat GPT (Integrada na IDE TRAE)
- **Objetivo da Consulta:** Estava ocorrendo um bug na aplicação, em que, em algumas partidas, ao salvar as estatísticas, a requisição estava sendo executada duas vezes, gerando duplicidade dos dados, objetivo do prompt era que a IA identificasse o problema e fornecesse um caminho para resolvê-lo.
- **Prompt(s) Utilizado(s):** 
    1. Em algumas partidas a requisição de registrar os dados da partida está ocorrendo duas vezes, como resolver?
- **Resumo da Resposta da IA:** Foi constatado que ocorria a duplicidade porque a chamada para registrar a partida está acoplada ao método stopGame() , e esse método é invocado em mais de um lugar no ciclo de vida do jogo. Como matchEnded permanecia true , cada chamada extra de stopGame() fazia outro create() .
- **Análise e Aplicação:** A IA auxiliou a identificar o problema e ainda sugeriu uma alteração simples, que era a criação de uma variável booleana para acompanhar se o jogo havia terminado ou não, podendo assim, travar a execução do jogo a partir dessa condicional.
- **Referência no Código:** A lógica do jogo foi implementada dentro do componente game, localizado na pasta pages do frontend.