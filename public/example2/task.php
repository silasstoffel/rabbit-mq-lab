<?php

require_once '../../vendor/autoload.php';
require_once '../../RabbitMQConnection.php';

use PhpAmqpLib\Message\AMQPMessage;


$connection = RabbitMQConnection::getConnection();
$channel = $connection->channel();

$durable = isset($_GET['durable']) && $_GET['durable'] === 'true';

$channel->queue_declare('worker-example', false, $durable, false, false);

$date = new DateTime();

$data = [
    'event' => 'hello',
    'payload' => [
        'message' => 'Hello Word #' . rand(),
        'date' => $date->format('Y-m-d H:i:s')
    ]
];

$message = json_encode($data);

$str = new AMQPMessage(
    $message,
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

$channel->basic_publish($str,'','worker-example');

$channel->close();
$connection->close();

header("Content-Type: application/json");
echo json_encode([
    'message' => 'success',
    'durable' => $durable
]);





