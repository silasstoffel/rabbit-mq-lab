# rabbit-mq-lab com php

Projeto de estudos de filas com [RabbitMQ](https://www.rabbitmq.com/).

## Setup

O projeto foi estruturado para trabalhar com docker compose.

- Rodar `docker compose up -d`
- Acessar area administrativa do RabbitMQ `http://localhost:8090`
- Credenciais: Login: rabbitmq | Password: a1b2c3 


## Considerações

Os pontos de estudos foram pensados em cenário onde a página web envia eventos para fila.
O acompanhamento dessas mensagens é visível apenas pelo terminal.
