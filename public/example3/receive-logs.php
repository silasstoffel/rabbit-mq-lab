<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';


$connection = RabbitMQConnection::getConnection();
$channel = $connection->channel();

$exchangeName = 'logs';
$exchangeType = 'fanout';
$exchangePassive = false;
$exchangeDurable = false;
$exchangeAutoDelete = false;

$channel->exchange_declare(
    $exchangeName,
    $exchangeType,
    $exchangePassive,
    $exchangeDurable,
    $exchangeAutoDelete
);

list($queueName, ,) = $channel->queue_declare(
    '',
    false,
    false,
    true,
    false
);
$channel->queue_bind($queueName, $exchangeName);

echo '[*] Waiting for logs messages. To exit press CTRL+C', PHP_EOL;

$callback = function ($message) use($queueName) {
    $date = new DateTime();
    $body = json_decode($message->body, true);
    $toString = $body['payload']['message'];
    $m = sprintf(
        '[x] [%s]: %s | queue: %s',
        $date->format('Y-m-d H:i:s'),
        $toString,
        $queueName
    );
    echo $m . PHP_EOL;
};

$channel->basic_consume(
    $queueName,
    '',
    false,
    true,
    false,
    false,
    $callback
);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
