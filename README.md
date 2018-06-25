# Teste EPICS
### TripAdvisor Parser

## Dependências

### PHP & Extensões
- PHP 7.0+
- PHP-Gearman
- PHP-Redis
- PHP-Memcached

### Servidor
- Node
- NPM
- composer
- Gearmand
- Redis
- Memcached

## Introdução

Este Teste foi desenvolvido utilizando algumas das habilidades previamente citadas por mim.
Para poder demonstrar os conhecimentos em Front-end, Back-end, e SO criei o código de uma maneira que fosse necessária expertise nos três.

O processo do teste funciona da seguinte maneira:
- Usuário entra no site e insere uma URL do TripAdvisor;
- Servidor recebe a requisição e valida se a URL é válida;
- Servidor então envia um Job para o Worker do Gearman rodando em background no servidor;
- O Worker faz um parse da url requisitada e envia via Redis para o NodeJS;
- O NodeJS por sua vez, através de uma WebSocket, envia os resultados para o usuário;

Todas as pesquisas são salvas em Cache através do Memcached para que não sejam refeitas requisições lentas de CURL. Agilizando o tempo de resposta para o usuário final.

## Tecnologias utilizadas
- PHP 7.x
- Redis
- Gearman
- Node.JS
- JS
- Gulp
- SASS

## Instalação

Para instalar as dependências do código basta utilizar os comandos, na pasta raiz do repositório:

```sh
composer install
cd server/
npm install
```

## Rodando o Node e Worker background

Para rodar o node e o worker em background basta executar o seguinte comando na pasta raiz do repositório:

```sh
composer run start
```

## Para facilitar a leitura

Para facilitar a encontrar os arquivos onde é feita a lógica de todo o teste, vou listar abaixo os caminhos:

#### JavaScript & SASS

[resources/](https://github.com/aledefreitas/teste-tripadvisor-parser/tree/master/resources)

Aqui temos todos os códigos do JavaScript executado na página do teste, e o SASS. Seguindo o padrão do NPM, temos o `package.json` com todas as dependências do código.

#### PHP

[src/Controller/HomeController.php](https://github.com/aledefreitas/teste-tripadvisor-parser/blob/master/src/Controller/HomeController.php)

Lógica da Controller do teste.

[src/Shell/Task/UrlParserTask.php](https://github.com/aledefreitas/teste-tripadvisor-parser/blob/master/src/Shell/Task/UrlParserTask.php)
[src/Shell/TripAdvisorParserShell.php](https://github.com/aledefreitas/teste-tripadvisor-parser/blob/master/src/Shell/TripAdvisorParserShell.php)

Lógica do Worker em background do teste.

[src/Template/](https://github.com/aledefreitas/teste-tripadvisor-parser/blob/master/src/Template/)
[src/View/](https://github.com/aledefreitas/teste-tripadvisor-parser/blob/master/src/View/)

Lógica da View do teste.

#### Node

[server/](https://github.com/aledefreitas/teste-tripadvisor-parser/tree/master/server)

Lógica do servidor de NodeJS utilizado para transmissão dos dados que foram executados em background.
