<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';


$connection = RabbitMQConnection::getConnection();
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL;

$callback = function ($message) {
    $date = new DateTime();
    $m = sprintf(
        '[x] [%s]: %s',
        $date->format('Y-m-d H:i:s'),
        $message->body
    );
    echo $m . PHP_EOL;
};

$channel->basic_consume(
    'hello',
    '',
    false,
    true,
    false,
    false,
    $callback
);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();





