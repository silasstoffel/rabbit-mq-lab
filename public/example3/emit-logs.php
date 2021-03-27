<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';

use PhpAmqpLib\Message\AMQPMessage;

$date = new DateTime();
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

$data = [
    'event' => 'log',
    'payload' => [
        'message' => 'Log #' . rand(),
        'date' => $date->format('Y-m-d H:i:s')
    ]
];
$str = json_encode($data);
$message = new AMQPMessage($str);

$channel->basic_publish($message, 'logs');

$channel->close();
$connection->close();

header("Content-Type: application/json");
echo json_encode(['message' => 'Log created']);

