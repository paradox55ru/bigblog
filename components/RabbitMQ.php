<?php

namespace app\components;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;

class RabbitMQ
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $config = Yii::$app->params['rabbitmq'];
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );
        $this->channel = $this->connection->channel();
    }

    public function publish($queue, $messageBody)
    {
        // Создаем сообщение
        $message = new AMQPMessage($messageBody);
        // Отправляем сообщение в указанную очередь
        $this->channel->basic_publish($message, '', $queue);
    }

    public function consume($queue, callable $callback)
    {
        // Устанавливаем обработчик для получения сообщений
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
