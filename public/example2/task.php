<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';

use PhpAmqpLib\Message\AMQPMessage;


$connection = RabbitMQConnection::getConnection();
$channel = $connection->channel();
$channel->queue_declare('worker-example', false, false, false, false);

$date = new DateTime();

$data = [
    'event' => 'hello',
    'payload' => [
        'message' => 'Hello Word #' . rand(),
        'date' => $date->format('Y-m-d H:i:s')
    ]
];

$message = json_encode($data);

$channel->basic_publish(
    new AMQPMessage($message),
    '',
    'worker-example'
);

$channel->close();
$connection->close();

header("Content-Type: application/json");
echo json_encode([
    'message' => 'success'
]);





