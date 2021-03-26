<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';


$connection = RabbitMQConnection::getConnection();
$channel = $connection->channel();
$channel->queue_declare('worker-example', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL;

$callback = function ($message) {
    $date = new DateTime();
    $body = json_decode($message->body, true);
    $toString = $body['payload']['message'];
    echo 'Starting ...' . PHP_EOL;
    $m = sprintf(
        '[x] [%s]: %s',
        $date->format('Y-m-d H:i:s'),
        $toString
    );
    $seconds = rand(1, 15);
    sleep($seconds);
    echo $m . PHP_EOL;
    $date = new DateTime();
    echo sprintf('Finish in %s ', $date->format('Y-m-d H:i:s'));
    echo PHP_EOL;
    $message->ack();
};

$channel->basic_consume(
    'worker-example',
    '',
    false,
    false,
    false,
    false,
    $callback
);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();





