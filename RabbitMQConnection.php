<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection
{

    private static ?AMQPStreamConnection $connection = null;

    public static function getConnection()
    {
        if (is_null(self::$connection)) {
            self::create();
        }
        return self::$connection;
    }

    public function close()
    {
        if (self::$connection) {
            self::$connection->close();
        }
    }

    private static function create()
    {
        self::$connection = new AMQPStreamConnection(
            'app-rabbitmq',
            5672,
            'rabbitmq',
            'a1b2c3'
        );
    }
}
